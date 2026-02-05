<?php
/**
 * Compilador de arquivos .po para .mo
 * Versão simplificada e funcional
 */

function compilePO($lang) {
    $poFile = __DIR__ . "/locales/$lang.po";
    $moFile = __DIR__ . "/locales/$lang.mo";
    
    if (!file_exists($poFile)) {
        echo "❌ $lang.po não encontrado\n";
        return false;
    }
    
    echo "📝 Compilando $lang...\n";
    
    // Ler e parse do .po
    $content = file_get_contents($poFile);
    $lines = explode("\n", $content);
    
    $translations = [];
    $msgid = '';
    $msgstr = '';
    $inMsgid = false;
    $inMsgstr = false;
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (empty($line) || $line[0] === '#') {
            continue;
        }
        
        if (strpos($line, 'msgid ') === 0) {
            // Salvar anterior
            if ($msgid !== '' && $msgstr !== '') {
                $translations[$msgid] = $msgstr;
            }
            
            // Novo msgid
            $msgid = stripslashes(substr($line, 7, -1));
            $msgstr = '';
            $inMsgid = true;
            $inMsgstr = false;
            
        } elseif (strpos($line, 'msgstr ') === 0) {
            $msgstr = stripslashes(substr($line, 8, -1));
            $inMsgid = false;
            $inMsgstr = true;
            
        } elseif ($line[0] === '"' && strlen($line) > 1) {
            $str = stripslashes(substr($line, 1, -1));
            if ($inMsgid) {
                $msgid .= $str;
            } elseif ($inMsgstr) {
                $msgstr .= $str;
            }
        }
    }
    
    // Último par
    if ($msgid !== '' && $msgstr !== '') {
        $translations[$msgid] = $msgstr;
    }
    
    // Remover entrada vazia do header
    if (isset($translations[''])) {
        unset($translations['']);
    }
    
    echo "   Traduções encontradas: " . count($translations) . "\n";
    
    // Criar .mo file
    $mo = '';
    
    // Magic number (little endian)
    $mo .= pack('V', 0x950412de);
    // Format revision
    $mo .= pack('V', 0);
    // Number of strings
    $numStrings = count($translations);
    $mo .= pack('V', $numStrings);
    // Offset of table with original strings
    $origTableOffset = 28;
    $mo .= pack('V', $origTableOffset);
    // Offset of table with translation strings
    $transTableOffset = $origTableOffset + $numStrings * 8;
    $mo .= pack('V', $transTableOffset);
    // Size of hash table (we don't use it)
    $mo .= pack('V', 0);
    // Offset of hash table
    $mo .= pack('V', 0);
    
    // Build string tables
    $origStrings = '';
    $transStrings = '';
    $origTable = '';
    $transTable = '';
    
    $origOffset = $transTableOffset + $numStrings * 8;
    $transOffset = $origOffset;
    
    foreach ($translations as $orig => $trans) {
        $origLen = strlen($orig);
        $transLen = strlen($trans);
        
        // Original table entry
        $origTable .= pack('V', $origLen);
        $origTable .= pack('V', $transOffset);
        $origStrings .= $orig . "\0";
        $transOffset += $origLen + 1;
        
        // Translation table entry  
        $transTable .= pack('V', $transLen);
        $transTable .= pack('V', $transOffset);
        $transStrings .= $trans . "\0";
        $transOffset += $transLen + 1;
    }
    
    // Assemble everything
    $mo .= $origTable;
    $mo .= $transTable;
    $mo .= $origStrings;
    $mo .= $transStrings;
    
    file_put_contents($moFile, $mo);
    
    echo "✅ $lang.mo criado com sucesso!\n\n";
    return true;
}

echo "\n";
echo "==========================================\n";
echo "  COMPILADOR DE TRADUÇÕES - NEWBASE\n";
echo "==========================================\n\n";

$success = true;
$success = compilePO('pt_BR') && $success;
$success = compilePO('en_GB') && $success;

if ($success) {
    echo "==========================================\n";
    echo "  ✅ COMPILAÇÃO CONCLUÍDA COM SUCESSO!\n";
    echo "==========================================\n\n";
    echo "Próximos passos:\n";
    echo "1. Reinicie o Apache (F12 no Laragon)\n";
    echo "2. Limpe o cache do navegador\n";
    echo "3. Teste mudando o idioma no GLPI\n\n";
} else {
    echo "❌ Houve erros na compilação\n\n";
    exit(1);
}
