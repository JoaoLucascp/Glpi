<?php
/**
 * SCRIPT DE DIAGNÓSTICO CSRF - Plugin Newbase
 * 
 * Este script verifica se o token CSRF está sendo gerado corretamente
 * 
 * COMO USAR:
 * 1. Acesse: http://glpi.test/plugins/newbase/test_csrf.php
 * 2. Verifique se o token é o mesmo em múltiplas chamadas
 * 3. Delete este arquivo após o teste
 * 
 * @package   Plugin - Newbase
 * @author    João Lucas
 * @license   GPLv2+
 */

// Carregar o núcleo do GLPI
include('../../inc/includes.php');

// Verificar se usuário está logado
Session::checkLoginUser();

// Configurar headers
header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste CSRF Token - Newbase</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #0066cc;
        }
        .token {
            font-family: 'Courier New', monospace;
            background: #e9ecef;
            padding: 10px;
            border-radius: 4px;
            word-break: break-all;
            margin: 10px 0;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .info {
            color: #0066cc;
            font-weight: bold;
        }
        button {
            background: #0066cc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        button:hover {
            background: #0052a3;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔒 Diagnóstico de Token CSRF</h1>
        
        <div class="test-section">
            <h3>1️⃣ Token da Sessão Atual</h3>
            <p><span class="info">Método:</span> $_SESSION['_glpi_csrf_token']</p>
            <div class="token"><?php echo $_SESSION['_glpi_csrf_token'] ?? 'Token não encontrado na sessão'; ?></div>
            <p class="success">✓ Este é o token armazenado na sessão do usuário</p>
        </div>

        <div class="test-section">
            <h3>2️⃣ Token via Html::hidden() - Método Oficial GLPI</h3>
            <p><span class="info">Método:</span> Html::hidden('_glpi_csrf_token')</p>
            <?php 
            // Capturar a saída do Html::hidden
            ob_start();
            echo Html::hidden('_glpi_csrf_token');
            $hidden_field = ob_get_clean();
            
            // Extrair o valor do token
            preg_match('/value=["\']([^"\']+)["\']/', $hidden_field, $matches);
            $token_from_hidden = $matches[1] ?? 'Não encontrado';
            ?>
            <div class="token"><?php echo $token_from_hidden; ?></div>
            <p><strong>Campo HTML gerado:</strong></p>
            <pre style="background: #e9ecef; padding: 10px; border-radius: 4px; overflow-x: auto;"><?php echo htmlspecialchars($hidden_field); ?></pre>
            <p class="success">✓ Este é o método usado no formulário corrigido</p>
        </div>

        <div class="test-section">
            <h3>3️⃣ Comparação de Tokens</h3>
            <?php
            $token_session = $_SESSION['_glpi_csrf_token'] ?? '';
            
            // Obter token do Html::hidden
            ob_start();
            echo Html::hidden('_glpi_csrf_token');
            $hidden = ob_get_clean();
            preg_match('/value=["\']([^"\']+)["\']/', $hidden, $matches);
            $token_html = $matches[1] ?? '';
            
            if ($token_session && $token_html && $token_session === $token_html) {
                echo "<p class='success'>✅ PERFEITO! Os tokens são idênticos!</p>";
                echo "<p>✓ Token da Sessão = Token do Html::hidden()</p>";
                echo "<p>✓ O formulário funcionará corretamente!</p>";
            } else {
                echo "<p class='error'>❌ ERRO: Os tokens são diferentes ou ausentes!</p>";
                echo "<p>Token Sessão: <code>{$token_session}</code></p>";
                echo "<p>Token Html::hidden: <code>{$token_html}</code></p>";
                echo "<p>Reinicie o Apache e tente novamente.</p>";
            }
            ?>
        </div>

        <div class="test-section">
            <h3>4️⃣ Informações da Sessão</h3>
            <p><strong>Usuário:</strong> <?php echo $_SESSION['glpiname'] ?? 'Desconhecido'; ?></p>
            <p><strong>ID do Usuário:</strong> <?php echo $_SESSION['glpiID'] ?? 'N/A'; ?></p>
            <p><strong>Session ID:</strong> <span class="token"><?php echo session_id(); ?></span></p>
        </div>

        <div class="test-section">
            <h3>5️⃣ Teste Prático - Formulário de Exemplo</h3>
            <p>Este é um exemplo de como o token CSRF deve ser adicionado ao formulário:</p>
            <form method="post" action="#" style="background: #e9ecef; padding: 15px; border-radius: 4px;">
                <?php echo Html::hidden('_glpi_csrf_token'); ?>
                <input type="text" name="test_field" placeholder="Campo de teste" style="padding: 8px; margin: 10px 0; width: 100%;">
                <button type="button" onclick="alert('Este é apenas um exemplo visual. Não submeta este formulário.')">Exemplo Visual (não submeter)</button>
            </form>
            <p class="success">✓ Código usado: <code>echo Html::hidden('_glpi_csrf_token');</code></p>
        </div>

        <div class="warning">
            <h3>⚠️ IMPORTANTE</h3>
            <p><strong>DELETE ESTE ARQUIVO</strong> após o teste por questões de segurança!</p>
            <p>Comando PowerShell: <code>del D:\laragon\www\glpi\plugins\newbase\test_csrf.php</code></p>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <button onclick="location.reload()">🔄 Recarregar Página</button>
            <button onclick="window.location.href='front/companydata.form.php?id=0'">➕ Testar Formulário</button>
            <button onclick="if(confirm('Tem certeza? Esta janela será fechada.')) window.close()">❌ Fechar</button>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <p><strong>Como interpretar os resultados:</strong></p>
            <ul>
                <li>✅ Tokens IDÊNTICOS = Funcionará corretamente</li>
                <li>❌ Tokens DIFERENTES = Ainda há problema de configuração</li>
                <li>❌ Token AUSENTE = Sessão não inicializada corretamente</li>
            </ul>
            <p><strong>Solução Aplicada:</strong></p>
            <ul>
                <li>❌ NÃO usar: <code>Session::getNewCSRFToken()</code> - gera token novo a cada vez</li>
                <li>❌ NÃO usar: <code>Session::getCSRFToken()</code> - não existe no GLPI 10.0.20</li>
                <li>✅ USAR: <code>Html::hidden('_glpi_csrf_token')</code> - método oficial do GLPI</li>
                <li>✅ USAR: <code>$_SESSION['_glpi_csrf_token']</code> - acesso direto à sessão</li>
            </ul>
            <p><strong>Próximo passo:</strong> Teste criar uma empresa em: <a href="front/companydata.form.php?id=0">Nova Empresa</a></p>
        </div>
    </div>
</body>
</html>
