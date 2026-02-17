<?php
/**
 * 🔍 VERIFICAÇÃO COMPLETA - Todos os Arquivos
 * 
 * Este script verifica TODOS os arquivos do plugin para garantir conformidade
 */

define('GLPI_USE_CSRF_CHECK', false);
require_once(__DIR__ . '/../../../../inc/includes.php');
Session::checkLoginUser();

$title = "Verificação Completa - Plugin Newbase v2.1.0";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 { font-size: 32px; margin-bottom: 10px; }
        .content { padding: 40px; }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        .section h3 {
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .file-check {
            padding: 12px;
            margin: 8px 0;
            background: white;
            border-radius: 6px;
            border-left: 4px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .file-check.ok { border-left-color: #28a745; }
        .file-check.error { border-left-color: #dc3545; }
        .file-check.warning { border-left-color: #ffc107; }
        .icon { font-size: 20px; min-width: 25px; }
        .file-name { font-weight: 600; color: #333; flex: 1; }
        .file-status { font-size: 13px; color: #666; }
        .summary {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
        }
        .summary .score { font-size: 64px; font-weight: bold; margin: 20px 0; }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px;
        }
        .code {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
            margin-top: 8px;
            overflow-x: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🔍 Verificação Completa</h1>
        <p>Plugin Newbase v2.1.0 - Análise de Conformidade</p>
    </div>

    <div class="content">
        
        <?php
        $total = 0;
        $passed = 0;
        $issues = [];
        
        // ================================================================
        // 1. VERIFICAR ARQUIVOS FRONT (FORM HANDLERS)
        // ================================================================
        echo '<div class="section">';
        echo '<h3>📁 Arquivos Front (Form Handlers)</h3>';
        
        $frontFiles = [
            'companydata.form.php',
            'task.form.php',
            'system.form.php'
        ];
        
        foreach ($frontFiles as $file) {
            $total++;
            $filePath = __DIR__ . '/../' . $file;
            
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $hasCSRF = (strpos($content, 'Session::checkCSRF($_POST)') !== false);
                $hasException = (strpos($content, 'try') !== false && strpos($content, 'catch') !== false);
                
                if ($hasCSRF && $hasException) {
                    $passed++;
                    echo "<div class='file-check ok'>";
                    echo "<span class='icon'>✅</span>";
                    echo "<span class='file-name'>$file</span>";
                    echo "<span class='file-status'>CSRF validado com try/catch</span>";
                    echo "</div>";
                } elseif ($hasCSRF) {
                    $issues[] = "$file: CSRF presente mas sem tratamento de exceção";
                    echo "<div class='file-check warning'>";
                    echo "<span class='icon'>⚠️</span>";
                    echo "<span class='file-name'>$file</span>";
                    echo "<span class='file-status'>CSRF OK, falta try/catch</span>";
                    echo "</div>";
                } else {
                    $issues[] = "$file: Validação CSRF ausente";
                    echo "<div class='file-check error'>";
                    echo "<span class='icon'>❌</span>";
                    echo "<span class='file-name'>$file</span>";
                    echo "<span class='file-status'>CSRF NÃO encontrado</span>";
                    echo "</div>";
                }
            } else {
                echo "<div class='file-check error'>";
                echo "<span class='icon'>❌</span>";
                echo "<span class='file-name'>$file</span>";
                echo "<span class='file-status'>Arquivo não encontrado</span>";
                echo "</div>";
            }
        }
        echo '</div>';
        
        // ================================================================
        // 2. VERIFICAR ARQUIVOS AJAX
        // ================================================================
        echo '<div class="section">';
        echo '<h3>🔌 Arquivos AJAX</h3>';
        
        $ajaxDir = __DIR__ . '/../../ajax/';
        if (is_dir($ajaxDir)) {
            $ajaxFiles = scandir($ajaxDir);
            foreach ($ajaxFiles as $file) {
                if (substr($file, -4) === '.php' && $file !== '.php-cs-fixer.dist.php') {
                    $total++;
                    $content = file_get_contents($ajaxDir . $file);
                    $hasCSRF = (strpos($content, 'Session::checkCSRF') !== false);
                    
                    if ($hasCSRF) {
                        $passed++;
                        echo "<div class='file-check ok'>";
                        echo "<span class='icon'>✅</span>";
                        echo "<span class='file-name'>$file</span>";
                        echo "<span class='file-status'>CSRF validado</span>";
                        echo "</div>";
                    } else {
                        $issues[] = "ajax/$file: Validação CSRF ausente";
                        echo "<div class='file-check error'>";
                        echo "<span class='icon'>❌</span>";
                        echo "<span class='file-name'>$file</span>";
                        echo "<span class='file-status'>CSRF NÃO encontrado</span>";
                        echo "</div>";
                    }
                }
            }
        }
        echo '</div>';
        
        // ================================================================
        // 3. VERIFICAR CLASSES SRC
        // ================================================================
        echo '<div class="section">';
        echo '<h3>📦 Classes (src/)</h3>';
        
        $srcDir = __DIR__ . '/../../src/';
        $classFiles = ['CompanyData.php', 'Task.php', 'System.php'];
        
        foreach ($classFiles as $file) {
            $total++;
            $filePath = $srcDir . $file;
            
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $hasHidden = (strpos($content, "Html::hidden('_glpi_csrf_token')") !== false);
                $hasCloseForm = (strpos($content, 'Html::closeForm()') !== false);
                
                if ($hasHidden && !$hasCloseForm) {
                    $passed++;
                    echo "<div class='file-check ok'>";
                    echo "<span class='icon'>✅</span>";
                    echo "<span class='file-name'>$file</span>";
                    echo "<span class='file-status'>Token manual correto</span>";
                    echo "</div>";
                } elseif ($hasHidden && $hasCloseForm) {
                    $issues[] = "$file: Possível duplicação de token (hidden + closeForm)";
                    echo "<div class='file-check warning'>";
                    echo "<span class='icon'>⚠️</span>";
                    echo "<span class='file-name'>$file</span>";
                    echo "<span class='file-status'>Atenção: hidden + closeForm</span>";
                    echo "</div>";
                } else {
                    echo "<div class='file-check error'>";
                    echo "<span class='icon'>❌</span>";
                    echo "<span class='file-name'>$file</span>";
                    echo "<span class='file-status'>Token não adicionado</span>";
                    echo "</div>";
                }
            }
        }
        echo '</div>';
        
        // ================================================================
        // 4. VERIFICAR SESSÃO CSRF
        // ================================================================
        echo '<div class="section">';
        echo '<h3>🔐 Status da Sessão</h3>';
        
        $total++;
        if (isset($_SESSION['_glpi_csrf_token']) && !empty($_SESSION['_glpi_csrf_token'])) {
            $passed++;
            $tokenPreview = substr($_SESSION['_glpi_csrf_token'], 0, 20) . '...';
            echo "<div class='file-check ok'>";
            echo "<span class='icon'>✅</span>";
            echo "<span class='file-name'>Token CSRF na Sessão</span>";
            echo "<span class='file-status'>$tokenPreview</span>";
            echo "</div>";
        } else {
            $issues[] = "Token CSRF não está na sessão atual";
            echo "<div class='file-check error'>";
            echo "<span class='icon'>❌</span>";
            echo "<span class='file-name'>Token CSRF Ausente</span>";
            echo "<span class='file-status'>Faça logout e login novamente</span>";
            echo "</div>";
        }
        echo '</div>';
        
        // ================================================================
        // 5. PROBLEMAS ENCONTRADOS
        // ================================================================
        if (!empty($issues)) {
            echo '<div class="section">';
            echo '<h3>⚠️ Problemas Encontrados</h3>';
            foreach ($issues as $issue) {
                echo "<div class='file-check error'>";
                echo "<span class='icon'>🔴</span>";
                echo "<span class='file-status'>$issue</span>";
                echo "</div>";
            }
            echo '</div>';
        }
        
        // ================================================================
        // RESUMO
        // ================================================================
        $percentage = $total > 0 ? round(($passed / $total) * 100) : 0;
        ?>
        
        <div class="summary">
            <h2>Status Geral</h2>
            <div class="score"><?php echo $percentage; ?>%</div>
            <p style="font-size: 18px; margin-bottom: 20px;">
                <?php echo $passed; ?> de <?php echo $total; ?> verificações aprovadas
            </p>
            
            <?php if ($percentage >= 90): ?>
                <p style="font-size: 20px; margin-bottom: 20px;">
                    🎉 <strong>EXCELENTE!</strong> O plugin está em conformidade!
                </p>
                <a href="../companydata.form.php?id=0" class="btn">🚀 Testar Formulário</a>
            <?php elseif ($percentage >= 70): ?>
                <p style="font-size: 18px; margin-bottom: 20px;">
                    ⚠️ <strong>ATENÇÃO!</strong> Algumas correções são necessárias.
                </p>
                <a href="." class="btn">🔧 Ver Documentação</a>
            <?php else: ?>
                <p style="font-size: 18px; margin-bottom: 20px;">
                    ❌ <strong>CRÍTICO!</strong> Múltiplos problemas detectados.
                </p>
                <a href="." class="btn">📖 Ver Guia de Correção</a>
            <?php endif; ?>
        </div>
        
    </div>
</div>

</body>
</html>
