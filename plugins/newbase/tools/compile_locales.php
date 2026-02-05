<?php
/**
 * Script para compilar arquivos .po para .mo
 * Execute: php compile_locales.php
 */

$locales_dir = __DIR__ . '/locales';
$languages = ['pt_BR', 'en_GB'];

foreach ($languages as $lang) {
    $po_file = $locales_dir . '/' . $lang . '.po';
    $mo_file = $locales_dir . '/' . $lang . '.mo';
    
    if (!file_exists($po_file)) {
        echo "Arquivo não encontrado: $po_file\n";
        continue;
    }
    
    echo "Compilando $lang...\n";
    
    // Ler arquivo .po
    $po_content = file_get_contents($po_file);
    
    // Parse do arquivo .po
    $translations = [];
    $current_msgid = '';
    $current_msgstr = '';
    $in_msgid = false;
    $in_msgstr = false;
    
    $lines = explode("\n", $po_content);
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (empty($line) || $line[0] === '#') {
            continue;
        }
        
        if (strpos($line, 'msgid ') === 0) {
            if ($current_msgid && $current_msgstr) {
                $translations[$current_msgid] = $current_msgstr;
            }
            $current_msgid = substr($line, 7, -1);
            $current_msgstr = '';
            $in_msgid = true;
            $in_msgstr = false;
        } elseif (strpos($line, 'msgstr ') === 0) {
            $current_msgstr = substr($line, 8, -1);
            $in_msgid = false;
            $in_msgstr = true;
        } elseif ($line[0] === '"') {
            $str = substr($line, 1, -1);
            if ($in_msgid) {
                $current_msgid .= $str;
            } elseif ($in_msgstr) {
                $current_msgstr .= $str;
            }
        }
    }
    
    if ($current_msgid && $current_msgstr) {
        $translations[$current_msgid] = $current_msgstr;
    }
    
    // Criar arquivo .mo
    $mo = '';
    
    // Header .mo
    $mo .= pack('L', 0x950412de); // Magic number
    $mo .= pack('L', 0);           // Version
    $mo .= pack('L', count($translations)); // Number of strings
    $mo .= pack('L', 28);          // Offset of original strings
    $mo .= pack('L', 28 + count($translations) * 8); // Offset of translated strings
    $mo .= pack('L', 0);           // Size of hash table
    $mo .= pack('L', 0);           // Offset of hash table
    
    $ids = '';
    $strs = '';
    $offsets_ids = [];
    $offsets_strs = [];
    
    foreach ($translations as $id => $str) {
        $offsets_ids[] = [strlen($ids), strlen($id)];
        $ids .= $id . "\0";
        
        $offsets_strs[] = [strlen($strs), strlen($str)];
        $strs .= $str . "\0";
    }
    
    $offset = 28 + count($translations) * 8 * 2;
    
    foreach ($offsets_ids as $offset_id) {
        $mo .= pack('L', $offset_id[1]);
        $mo .= pack('L', $offset + $offset_id[0]);
    }
    
    $offset += strlen($ids);
    
    foreach ($offsets_strs as $offset_str) {
        $mo .= pack('L', $offset_str[1]);
        $mo .= pack('L', $offset + $offset_str[0]);
    }
    
    $mo .= $ids;
    $mo .= $strs;
    
    file_put_contents($mo_file, $mo);
    
    echo "✓ $lang compilado com sucesso!\n";
    echo "  Traduções: " . count($translations) . "\n\n";
}

echo "Compilação concluída!\n";
