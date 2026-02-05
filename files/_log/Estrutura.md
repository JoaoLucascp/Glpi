# By: Perplexitypro

Te dou acesso as pastas, não crie scripts para eu fazer.
Aplique as correções diretamente no plugin Newbase, não mecha nos código do glpi.
Não quebre meu código.
Sou iniciante, não sei programa. Mas tenho expertise em informática.

## Ambiente de Desenvolvimento

GLPI Versão: 10.0.20
PHP: 8.3.26
MySQL: 8.4.6 (InnoDB, charset utf8mb4)
Servidor: Apache 2.4.65 com SSL
Editor: VS Code + IA
Sistema Operacional: Windows 11 Pro
Framework: GLPI Native (CommonDBTM, sem frameworks externos)
Padrões: PSR-12, SOLID principles
Compilância: GPLv2+
Banco de Dados: MySQL via GLPI Database Abstraction Layer
Autoloader: Composer PSR-12
VM: Laragon 2025 8.3.0 local

## Plugin Newbase - Informações Completas

Nome do Plugin: Newbase
Versão: 2.1.0
Compatibilidade GLPI: 10.0.20+
PHP Mínimo: 8.1+
Autor: João Lucas
Descrição Completa: Sistema completo de Gestão de documentação de empresas
para GLPI com gerenciamento de empresas, documentação de servidor telefônico baseado em asterisk, documentação de servidor telefônico em nuvem baseado em asterisk, documentação de sistema Chatbot Omnichannel, documentação de linha fixa, . Gestão de tarefas com geolocalização, assinatura digital e cálculo de quilometragem.
Licença: GPLv2+

## Documentação Oficial

- GLPI Developer Docs: https://glpi-developer-documentation.readthedocs.io/
- GLPI API Docs: https://github.com/glpi-project/glpi/blob/master/apirest.md
- Leaflet Docs: https://leafletjs.com/reference.html
- Brasil API: https://brasilapi.com.br/docs
- ViaCEP: https://viacep.com.br/

## Comunidade

- Fórum GLPI: https://forum.glpi-project.org/
- GitHub Issues: https://github.com/glpi-project/glpi/issues
- Telegram BR: https://t.me/glpibr
- Service Desk Brasil: https://blog.servicedeskbrasil.com.br/plugin-fields/
- GitHub Oaugustus: https://github.com/oaugustus/blog/blob/master/glpi/desenvolvimento-de-plugins.md

Estrutura de pastas do plugin:

Plugins
└── 📁newbase
    ├── 📁ajax
    │   ├── .php-cs-fixer.dist.php
    │   ├── calculateMileage.php
    │   ├── cnpj_proxy.php
    │   ├── mapData.php
    │   ├── searchAddress.php
    │   ├── searchCompany.php
    │   ├── signatureUpload.php
    │   └── taskActions.php
    ├── 📁css
    │   ├── forms.css
    │   ├── newbase.css
    │   └── responsive.css
    ├── 📁docs
    │   ├── CHECKLIST.md
    │   ├── CORRECOES_APLICADAS_2.md
    │   ├── CORRECOES_APLICADAS.md
    │   ├── CORRECTIONS_APPLIED.md
    │   ├── EXECUTIVE_SUMMARY.md
    │   ├── FINAL_SUMMARY.md
    │   ├── GUIA_DE_TESTES.md
    │   ├── GUIA_DIDATICO_COMPLETO_NEWBASE.md
    │   ├── QUICK_COMMANDS.md
    │   ├── QUICK_TEST_GUIDE.md
    │   └── README_CORRECOES.md
    ├── 📁front
    │   ├── .php-cs-fixer.dist.php
    │   ├── companydata.form.php
    │   ├── companydata.php
    │   ├── config.php
    │   ├── index.php
    │   ├── report.php
    │   ├── system.form.php
    │   ├── system.php
    │   ├── task.form.php
    │   └── task.php
    ├── 📁install
    │   └── 📁mysql
    │       ├── 2.0.0.sql
    │       └── 2.1.0.sql
    ├── 📁js
    │   ├── forms.js
    │   ├── jquery.mask.min.js
    │   ├── map.js
    │   ├── mileage.js
    │   ├── mobile.js
    │   ├── newbase.js
    │   └── signature.js
    ├── 📁locales
    │   ├── .php-cs-fixer.dist.php
    │   ├── pt_BR.mo
    │   └── pt_BR.po
    ├── 📁src
    │   ├── .php-cs-fixer.dist.php
    │   ├── Address.php
    │   ├── AddressHandler.php
    │   ├── Common.php
    │   ├── CompanyData.php
    │   ├── Config.php
    │   ├── Menu.php
    │   ├── System.php
    │   ├── Task.php
    │   └── TaskSignature.php
    ├── 📁tools
    │   └── 📁correcao
    │       ├── CORRECAO_BOM_MANUAL.txt
    │       └── REMOVER_BOM_FINAL.py
    ├── 📁vendor
    │   ├── 📁bin
    │   │   ├── phpcbf
    │   │   ├── phpcbf.bat
    │   │   ├── phpcs
    │   │   ├── phpcs.bat
    │   │   ├── phpstan
    │   │   ├── phpstan.bat
    │   │   ├── phpstan.phar
    │   │   └── phpstan.phar.bat
    │   ├── 📁composer
    │   │   ├── ... (arquivos de autoloading)
    │   ├── 📁phpstan
    │   │   └── ...
    │   ├── 📁squizlabs
    │   │   └── 📁php_codesniffer
    │   │       └── ...
    │   └── autoload.php
    ├── .gitignore
    ├── .php-cs-fixer.dist.php
    ├── .php-cs-fixer.php
    ├── CHANGELOG.md
    ├── composer.json
    ├── composer.lock
    ├── CONTRIBUTING.md
    ├── hook.php
    ├── Makefile
    ├── newbase.xml
    ├── phpstan.neon
    ├── phpunit.xml
    ├── README.md
    ├── setup.php
    ├── VERSION
    └── ...

Exemplo de Ativação no GLPI:

1. Acesse: [http://glpi.test/public]
2. login como administrador (Login: glpi, Senha: glpi)
3. Vá em: Configurar > Plugins
4. Localize NewBase
5. Clique em Instalar
6. Clique em Ativar