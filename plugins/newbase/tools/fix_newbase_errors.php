<?php
/**
 * Script de Correção Automática - Plugin Newbase
 * 
 * Corrige os seguintes erros:
 * 1. Array to string conversion em Search.php (linhas 752 e 913)
 * 2. SCSS _generate.scss file not found
 * 3. Validação e limpeza de getSearchOptions()
 * 
 * @version 1.0.0
 * @author João Lucas (Newtel Soluções)
 * @license GPLv2+
 */

class NewbaseErrorFixer {
    
    private $plugin_dir;
    private $glpi_dir;
    private $backup_dir;
    private $errors = [];
    private $warnings = [];
    private $success = [];
    
    public function __construct($plugin_dir = null) {
        $this->plugin_dir = $plugin_dir ?: dirname(__DIR__);
        $this->glpi_dir = dirname(dirname($this->plugin_dir));
        $this->backup_dir = $this->plugin_dir . '/backup_fixes_' . date('Y_m_d_H_i_s');
        
        echo "╔════════════════════════════════════════════════════════╗\n";
        echo "║     NEWBASE - FERRAMENTA DE CORREÇÃO AUTOMÁTICA        ║\n";
        echo "║     Versão 1.0.0 - Plugin: " . basename($this->plugin_dir) . "\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";
    }
    
    /**
     * Executa todas as correções
     */
    public function fixAll() {
        echo "🔍 Iniciando verificação e correção dos erros...\n\n";
        
        // Criar diretório de backup
        if (!is_dir($this->backup_dir)) {
            mkdir($this->backup_dir, 0755, true);
            echo "✅ Diretório de backup criado: {$this->backup_dir}\n\n";
        }
        
        // Executar correções
        $this->fixCompanyDataSearchOptions();
        $this->fixWidgetScssError();
        $this->validateAllClasses();
        $this->cleanupCache();
        
        // Exibir relatório
        $this->displayReport();
    }
    
    /**
     * CORREÇÃO 1: Fix Array to string conversion em CompanyData.php
     */
    private function fixCompanyDataSearchOptions() {
        echo "📋 [1/4] Corrigindo getSearchOptions() em CompanyData.php...\n";
        
        $file = $this->plugin_dir . '/src/CompanyData.php';
        
        if (!file_exists($file)) {
            $this->errors[] = "Arquivo não encontrado: {$file}";
            echo "❌ Arquivo não encontrado: CompanyData.php\n\n";
            return;
        }
        
        // Fazer backup
        copy($file, $this->backup_dir . '/CompanyData.php.backup');
        
        $content = file_get_contents($file);
        $original_content = $content;
        
        // PADRÃO 1: Remover arrays em datatype
        // 'datatype' => ['string'] --> 'datatype' => 'string'
        $content = preg_replace(
            "/(['\"]datatype['\"]\s*=>\s*)\[(['\"])([a-z_]+)(['\"]\]\s*(?:,|}))/",
            "$1'$3'",
            $content
        );
        
        // PADRÃO 2: Corrigir múltiplos elementos em array
        // 'datatype' => ['string', 'other'] --> 'datatype' => 'string'
        $content = preg_replace(
            "/(['\"]datatype['\"]\s*=>\s*)\[(['\"])([a-z_]+)['\"],\s*['\"][a-z_]+['\"]\]/",
            "$1'$3'",
            $content
        );
        
        // PADRÃO 3: Adicionar validação na função getSearchOptions()
        if (strpos($content, 'public function getSearchOptions()') !== false) {
            $validation = <<<'PHP'
        // Validação - Garante que não há arrays em datatype
        foreach ($tab as &$field) {
            if (is_array($field) && isset($field['datatype']) && is_array($field['datatype'])) {
                $field['datatype'] = reset($field['datatype']); // Pega o primeiro elemento
            }
        }
        unset($field);
        
PHP;
            
            // Inserir validação antes do return
            $content = preg_replace(
                '/(\s+return \$tab;)/i',
                "\n{$validation}\n        return \$tab;",
                $content,
                1
            );
        }
        
        // Verificar se houve mudanças
        if ($content !== $original_content) {
            file_put_contents($file, $content);
            $this->success[] = "CompanyData.php corrigido com sucesso";
            echo "✅ getSearchOptions() corrigido\n";
            echo "   - Removidos arrays em campos 'datatype'\n";
            echo "   - Adicionada validação automática\n";
        } else {
            $this->warnings[] = "Nenhuma alteração necessária em CompanyData.php";
            echo "⚠️  CompanyData.php já está em conformidade\n";
        }
        echo "\n";
    }
    
    /**
     * CORREÇÃO 2: Fix SCSS _generate.scss file not found
     */
    private function fixWidgetScssError() {
        echo "🎨 [2/4] Corrigindo erro de SCSS no Dashboard/Widget.php...\n";
        
        $file = $this->glpi_dir . '/src/Dashboard/Widget.php';
        
        if (!file_exists($file)) {
            $this->warnings[] = "Widget.php não encontrado (pode estar em outra versão do GLPI)";
            echo "⚠️  Widget.php não encontrado\n";
            echo "   (Seu GLPI pode estar em outra localização)\n\n";
            return;
        }
        
        // Fazer backup
        copy($file, $this->backup_dir . '/Widget.php.backup');
        
        $content = file_get_contents($file);
        $original_content = $content;
        
        // CORREÇÃO: Envolver compileString em try-catch
        $pattern = '/(\$compiled\s*=\s*\$compiler->compileString\(\$css,\s*\$path\);)/';
        
        if (preg_match($pattern, $content)) {
            $replacement = <<<'PHP'
try {
                $compiled = $compiler->compileString($css, $path);
            } catch (\Exception $e) {
                // Se SCSS falhar, retorna o CSS original sem compilação
                // Evita exceção "file not found for @import"
                error_log('GLPI Dashboard Widget SCSS Compilation Error: ' . $e->getMessage());
                $compiled = ['css' => $css];
            }
PHP;
            
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        if ($content !== $original_content) {
            file_put_contents($file, $content);
            $this->success[] = "Widget.php corrigido com sucesso";
            echo "✅ Tratamento de exceção SCSS adicionado\n";
            echo "   - try-catch adicionado em compileString()\n";
            echo "   - Fallback para CSS sem compilação\n";
        } else {
            $this->warnings[] = "Widget.php já possui tratamento ou padrão não encontrado";
            echo "⚠️  Widget.php já está otimizado ou padrão não detectado\n";
        }
        echo "\n";
    }
    
    /**
     * CORREÇÃO 3: Validar todas as classes do plugin
     */
    private function validateAllClasses() {
        echo "✔️  [3/4] Validando classes do plugin Newbase...\n";
        
        $src_dir = $this->plugin_dir . '/src';
        
        if (!is_dir($src_dir)) {
            $this->errors[] = "Diretório src não encontrado";
            echo "❌ Diretório src não encontrado\n\n";
            return;
        }
        
        $php_files = glob($src_dir . '/*.php');
        $validated = 0;
        $issues_found = 0;
        
        foreach ($php_files as $file) {
            $content = file_get_contents($file);
            
            // Verificar sintaxe PHP
            $output = [];
            $return_var = 0;
            exec("php -l " . escapeshellarg($file), $output, $return_var);
            
            if ($return_var === 0) {
                $validated++;
            } else {
                $this->errors[] = "Erro de sintaxe em: " . basename($file);
                $issues_found++;
                echo "❌ Erro de sintaxe: " . basename($file) . "\n";
            }
            
            // Verificar por padrões problemáticos
            if (preg_match('/[\'"]datatype[\'\"]\s*=>\s*\[/', $content)) {
                echo "⚠️  Possível array em datatype detectado em: " . basename($file) . "\n";
                $issues_found++;
            }
        }
        
        echo "✅ {$validated} arquivo(s) validado(s)\n";
        
        if ($issues_found > 0) {
            echo "⚠️  {$issues_found} aviso(s) encontrado(s)\n";
        }
        echo "\n";
    }
    
    /**
     * CORREÇÃO 4: Limpar cache
     */
    private function cleanupCache() {
        echo "🗑️  [4/4] Limpando cache do plugin...\n";
        
        $cache_dirs = [
            $this->plugin_dir . '/tmp',
            $this->plugin_dir . '/cache',
        ];
        
        $cleaned = 0;
        
        foreach ($cache_dirs as $dir) {
            if (is_dir($dir)) {
                $files = glob($dir . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                        $cleaned++;
                    } elseif (is_dir($file) && $file !== '.' && $file !== '..') {
                        $this->rrmdir($file);
                    }
                }
            }
        }
        
        echo "✅ {$cleaned} arquivo(s) de cache removido(s)\n";
        $this->success[] = "Cache limpo com sucesso";
        echo "\n";
    }
    
    /**
     * Remover diretório recursivamente
     */
    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
    
    /**
     * Exibir relatório final
     */
    private function displayReport() {
        echo "╔════════════════════════════════════════════════════════╗\n";
        echo "║                  RELATÓRIO FINAL                       ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";
        
        if (!empty($this->success)) {
            echo "✅ SUCESSOS (" . count($this->success) . "):\n";
            foreach ($this->success as $msg) {
                echo "   • {$msg}\n";
            }
            echo "\n";
        }
        
        if (!empty($this->warnings)) {
            echo "⚠️  AVISOS (" . count($this->warnings) . "):\n";
            foreach ($this->warnings as $msg) {
                echo "   • {$msg}\n";
            }
            echo "\n";
        }
        
        if (!empty($this->errors)) {
            echo "❌ ERROS (" . count($this->errors) . "):\n";
            foreach ($this->errors as $msg) {
                echo "   • {$msg}\n";
            }
            echo "\n";
        }
        
        echo "📁 Backup salvo em: {$this->backup_dir}\n\n";
        
        echo "🎯 PRÓXIMOS PASSOS:\n";
        echo "   1. Desative o plugin em: Configurar > Plugins > NewBase\n";
        echo "   2. Reative o plugin: Clique em 'Ativar'\n";
        echo "   3. Teste as funcionalidades: CompanyData, Tasks, etc.\n";
        echo "   4. Verifique o log: var/log/glpi.log\n\n";
        
        echo "📞 Suporte: Contate o desenvolvedor se problemas persistirem.\n\n";
    }
}

// ═══════════════════════════════════════════════════════════════════════════

// Executar o fixer
if (php_sapi_name() === 'cli') {
    // Uso via linha de comando
    $plugin_dir = isset($argv[1]) ? $argv[1] : dirname(__DIR__);
    $fixer = new NewbaseErrorFixer($plugin_dir);
    $fixer->fixAll();
    exit(0);
} else {
    // Uso via HTTP
    if (!isset($_GET['confirm'])) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Newbase - Ferramenta de Correção</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }
                .container {
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                    max-width: 600px;
                    padding: 40px;
                }
                h1 { color: #333; margin-bottom: 10px; }
                .subtitle { color: #666; margin-bottom: 30px; font-size: 14px; }
                .issues {
                    background: #f5f5f5;
                    border-left: 4px solid #e74c3c;
                    padding: 20px;
                    margin-bottom: 30px;
                    border-radius: 4px;
                }
                .issue-item {
                    display: flex;
                    margin-bottom: 12px;
                }
                .issue-item:last-child { margin-bottom: 0; }
                .issue-icon { margin-right: 12px; font-size: 18px; }
                .issue-text {
                    flex: 1;
                    color: #333;
                    font-size: 14px;
                    line-height: 1.5;
                }
                .buttons {
                    display: flex;
                    gap: 12px;
                }
                button {
                    flex: 1;
                    padding: 12px 24px;
                    border: none;
                    border-radius: 6px;
                    font-size: 14px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s;
                }
                .btn-primary {
                    background: #667eea;
                    color: white;
                }
                .btn-primary:hover {
                    background: #5568d3;
                    transform: translateY(-2px);
                    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
                }
                .btn-secondary {
                    background: #e0e0e0;
                    color: #333;
                }
                .btn-secondary:hover {
                    background: #d0d0d0;
                }
                .warning-box {
                    background: #fff3cd;
                    border: 1px solid #ffc107;
                    border-radius: 6px;
                    padding: 15px;
                    margin-bottom: 20px;
                    color: #856404;
                    font-size: 13px;
                    line-height: 1.6;
                }
                .warning-icon { margin-right: 8px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🔧 Newbase - Ferramenta de Correção</h1>
                <p class="subtitle">Versão 1.0.0</p>
                
                <div class="warning-box">
                    <span class="warning-icon">⚠️</span>
                    Faça um backup completo do seu plugin antes de prosseguir!
                </div>
                
                <div class="issues">
                    <div class="issue-item">
                        <div class="issue-icon">❌</div>
                        <div class="issue-text">
                            <strong>Array to string conversion</strong><br>
                            Campos com datatype como array em getSearchOptions()
                        </div>
                    </div>
                    <div class="issue-item">
                        <div class="issue-icon">❌</div>
                        <div class="issue-text">
                            <strong>SCSS compilation error</strong><br>
                            Arquivo _generate.scss não encontrado
                        </div>
                    </div>
                    <div class="issue-item">
                        <div class="issue-icon">❌</div>
                        <div class="issue-text">
                            <strong>Cache inválido</strong><br>
                            Limpeza de arquivos temporários
                        </div>
                    </div>
                </div>
                
                <div class="buttons">
                    <button class="btn-secondary" onclick="window.location.href='javascript:history.back()'">
                        Cancelar
                    </button>
                    <form method="get" style="flex: 1;">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn-primary" style="width: 100%;">
                            Iniciar Correção
                        </button>
                    </form>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    } else {
        // Executar correções
        $fixer = new NewbaseErrorFixer();
        ob_start();
        $fixer->fixAll();
        $output = ob_get_clean();
        
        // Exibir em HTML
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Newbase - Resultado da Correção</title>
            <style>
                body {
                    font-family: 'Monaco', 'Menlo', monospace;
                    background: #1e1e1e;
                    color: #d4d4d4;
                    padding: 20px;
                    line-height: 1.6;
                }
                pre {
                    background: #252526;
                    padding: 20px;
                    border-radius: 6px;
                    overflow-x: auto;
                    border-left: 4px solid #667eea;
                }
                .success { color: #4ec9b0; }
                .error { color: #f48771; }
                .warning { color: #dcdcaa; }
            </style>
        </head>
        <body>
            <pre><?php echo htmlspecialchars($output); ?></pre>
        </body>
        </html>
        <?php
        exit;
    }
}
?>
