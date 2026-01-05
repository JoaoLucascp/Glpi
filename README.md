# Newbase - Plugin de Gestão de Dados para GLPI

## ⚠️ ATENÇÃO - IMPORTANTE (02/01/2026)

**O plugin foi completamente corrigido e está pronto para instalação!**

### Se você está instalando pela PRIMEIRA VEZ:
✅ Siga o guia **INSTALLATION_GUIDE.md**

### Se você JÁ TENTOU instalar antes e teve ERROS:
1. ✅ Leia **CORRECTIONS_SUMMARY.md** para entender as correções
2. ✅ Execute o cleanup: `php tools/cleanup_db.php`
3. ✅ Siga o guia **INSTALLATION_GUIDE.md**

---

Plugin para GLPI que fornece gerenciamento completo de dados de empresas (Pessoa Jurídica), endereços, sistemas de comunicação, tarefas com geolocalização, assinatura digital e cálculo de quilometragem.

## Requisitos

- **GLPI:** 10.0.20 ou superior
- **PHP:** 8.1 ou superior
- **MySQL:** 8.0 ou superior
- **Extensões PHP:** json, mbstring, mysqli, curl

## Instalação

Consulte o [Guia de Instalação](doc/GUIA_INSTALACAO.md) para instruções detalhadas.

**Passos rápidos:**

1. Extrair para `glpi/plugins/newbase`
2. Acessar **Setup > Plugins** no GLPI
3. Instalar e ativar o plugin "Newbase"
4. Configurar permissões em **Administração > Perfis**

## Recursos

- ✅ Gestão completa de dados de empresas (CNPJ, email, telefone)
- ✅ Gerenciamento de endereços com CEP automático
- ✅ Sistemas de comunicação (IPBX, PABX, Chatbot)
- ✅ Tarefas com geolocalização e cálculo de quilometragem
- ✅ Assinatura digital com upload
- ✅ Interface responsiva (mobile-friendly)
- ✅ Sistema completo de permissões
- ✅ Relatórios e estatísticas

## Desenvolvimento

Consulte [doc/DESENVOLVIMENTO.md](doc/DESENVOLVIMENTO.md) para informações sobre estrutura, padrões de código e como estender o plugin.

## ⚠️ Aviso

Este é um **plugin não oficial** para GLPI. Use por sua conta e risco. Sempre faça backup do banco de dados antes de instalar ou atualizar.

## Licença

GPLv2+ - Veja o arquivo [LICENSE](LICENSE) para detalhes completos.

## Autoria

- **Autor:** João Lucas
- **Baseado em:** GLPI Framework

---

Para mais informações, consulte a documentação em `doc/`.
