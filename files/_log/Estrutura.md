# inicio

Te dou acesso as pastas, não crie scripts para eu fazer.
Aplique as correções diretamente no plugin Newbase, não mecha nos código do glpi.
Não quebre meu código.
Sou iniciante, não sei programa. Mas tenho expertise em informática.

Esta é a documentação do meu plugin: D:\laragon\www\glpi\plugins\newbase\docs\DOCUMENTACAO_TECNICA_V2.1.0.md

Coloque no arquivo DOCUMENTACAO_TECNICA_V2.1.0.md todos os erros encontrado e as correções aplicadas por você.

Observação: Não coloque na documentação erros repetidos e nem soluções repetidas.

apenas novos erros e novas soluções.

Preciso saber como resolver esse erro:

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

```css
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
    │   ├── DOCUMENTACAO_TECNICA_V2.1.0_ATUALIZADA.md
    │   └── ROADMAP_REFATORACAO.md
    ├── 📁front
    │   ├── 📁tools
    │   │   └── verificacao_completa.php
    │   ├── .php-cs-fixer.dist.php
    │   ├── companydata.form.php
    │   ├── companydata.php
    │   ├── index.php
    │   ├── report.php
    │   ├── system.form.php
    │   ├── system.php
    │   ├── task.form.php
    │   └── task.php
    ├── 📁install
    │   └── 📁mysql
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
    │   ├── en_GB.mo
    │   ├── en_GB.po
    │   ├── pt_BR.mo
    │   ├── pt_BR.po
    │   └── README.md
    ├── 📁src
    │   ├── .php-cs-fixer.dist.php
    │   ├── Address.php
    │   ├── AddressHandler.php
    │   ├── AjaxHandler.php
    │   ├── Common.php
    │   ├── CompanyData.php
    │   ├── Config.php
    │   ├── Menu.php
    │   ├── System.php
    │   ├── Task.php
    │   └── TaskSignature.php
    ├── 📁templates
    │   └──config.html.twig
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
```

Exemplo de Ativação no GLPI:

1. Acesse: [http://glpi.test/public]
2. login como administrador (Login: glpi, Senha: glpi)
3. Vá em: Configurar > Plugins
4. Localize NewBase
5. Clique em Instalar
6. Clique em Ativar

## EXEMPLO DA ESTRUTURA DE CAMPOS DO PLUGIN NEWBASE

**Exemplo da estrutura do campo Dados Pessoais:**

```css
*Dados Pessoais*
├── Dados Pessoais
│   ├── Buscar CNPJ automaticamente e preencher os campos
│   ├── Id
│   ├── Nome
│   ├── E-mail
│   ├── Telefone
│   ├── Nome razao social
│   ├── Nome fantasia
│   ├── Inscricao estadual
│   └── Inscricao municipal
│
├── Endereço
│   ├── Buscar CEP automaticamente e preencher os campos
│   ├── Numero
│   ├── Complemento
│   ├── Birro
│   ├── Cidade
│   ├── Estado
│   ├── Pais
│   ├── Latitude
│   └── Longitude
└── Status
    ├── Com contrato ativo
    ├── Sem contrato
    └── Contrato cancelado
```

---

**Exemplo da estrutura do campo IPBX/PABX:**

```css
*IPBX/PABX*
├── Modelo                          # Caixa de texto onde o usuário irá digitar o *modelo* do servidor (Ex:Newcloud)
├── Versão                          # Caixa de número onde o usuário irá digitar a *versão* do servidor (EX:3.19)
├── IP interno                      # Caixa de número onde o usuário irá digitar *IP* de acesso interno ao servidor IPBX/PABX da minha rede de internet (Ex:192.168.0.0).
── IP externo                       # Caixa de número onde o usuário irá digitar *IP* de acesso remoto ao servidor IPBX/PABX de outra rede de internet (Ex:192.168.0.0:xx).
├── Porta de acesso Web             # Caixa de número onde o usuário irá digitar a porta *WEB* de acesso a interface IPBX/PABX de outra rede de internet (Ex:xxx.xxx.xx.x:2080).
├── Senha da interface Web          # Caixa de número onde o usuário irá digitar a senha de acesso *WEB* do IPBX/PABX de outra rede de internet (Ex:12345).
├── Porta de acesso SSH             # Caixa de número onde o usuário irá digitar a porta *SSH* de acesso remoto ao IPBX/PABX de outra rede de internet (Ex:xxx.xxx.xx.x:2022).
├── Senha de acesso remoto SSH      # Caixa de número onde o usuário irá digitar a senha de acesso *SSH* do IPBX/PABX de outra rede de internet (Ex:12345).
├── Observações                     # Caixa de texto onde o usuário irá digitar *observações* adicionais.
├── Ramais                          # 1 - Planilha dos *ramais* criados no servidor IPBX/PABX com colunas auto ajustaveis (Ex:ramais, Senhas, IP, Nome).
|    ├── Faixa de ramais            # Coluna 1.1 - Caixa de número onde o usuário irá digitar o *número* do ramal criado no IPBX/PABX (Ex:2002).
|    ├── Senhas                     # Coluna 1.2 - Caixa de número onde o usuário irá digitar a *senha* criada para registrar o ramal no IPBX/PABX (Ex:12345).
|    ├── IP do aparelho             # Coluna 1.3 - Caixa de número onde o usuário irá digitar o *IP* do aparelho telefonico (Ex:192.168.xx.x).
|    ├── Nome                       # Coluna 1.4 - Caixa de texto onde o usuário irá digitar *nome* da pessoa que utiliza o ramal (Ex:José).
|    ├── Localidade                 # Coluna 1.5 - Caixa de texto onde o usuário irá digitar o nome do *local* aonde o ramal foi instalado (EX:Financeiro).
|    └── Gravação                   # Coluna 1.6 - Caixa de seleção onde o usuário irá selecionar para saber se o ramal pode *gravar* as ligações ou não.
|         └── Observações           # Coluna 1.7 - Caixa de texto onde o usuário irá digitar *observações* adicionais.
├── Informações da operadora        # *Operadora* registrada no servidor IPBX/PABX.
|    ├── Número piloto              # Caixa de número onde o usuário irá digitar o *número piloto* registrado no IPBX/PABX (Ex:(27)3372-8000).
|    ├── Tipo do tronco             # Caixa de texto onde o usuário irá digitar o *tipo do tronco* registrado no IPBX/PABX (Ex:SIP).
|    ├── Operadora                  # Caixa de texto onde o usuário irá digitar o nome da *operadora* registrado no IPBX/PABX (Ex:ZAFEX).
|    ├── Quantidade de DDR          # Caixa de número onde o usuário irá digitar range de *DDR* que a operadora disponibilizou (Ex:33727000 - 7100).
|    ├── Canais                     # Caixa de número onde o usuário irá digitar a quantidade de *canais* disponibilizado pela operadora (Ex:10).
|    ├── IP Proxy da Operadora      # Caixa de número onde o usuário irá digitar *IP Proxy* da operadora (Ex:192.xxx.xx.x).
|    ├── Porta do Proxy             # Caixa de número onde o usuário irá digitar a *porta* do Proxy da operadora (Ex:5060).
|    └── IP Tráfego do áudio        # Caixa de número onde o usuário irá digitar o *IP* de trafego do audio da operadora (Ex:192.xxx.xx.x).
|         └── Observações           # Caixa de texto onde o usuário irá digitar *observações* adicionais.
├── Informações do dispositivo      # 2 - Planilha dos *dispositiovos* interligados ao servidor IPBX/PABX com colunas auto ajustaveis (Ex:Tipo do dispositivo, IP)
|    ├── Tipo de dispositivo        # Coluna 2.1 - Caixa de texto onde o usuário irá digitar o *tipo do dispositivo* (Ex:FXS, FXO, ATA, Aligera, E1)
|    ├── IP do dispositivo          # Coluna 2.2 - Caixa de número onde o usuário irá digitar o *IP* de acesso ao dispositivo (Ex:192.xxx.xx.x)
|    └── Senha do dispositivo       # Coluna 2.3 - Caixa de número onde o usuário irá digitar a *senha* de acesso a interface do dispotisivo (Ex:12345)
|         └── Observações           # Coluna 2.4 - Caixa de texto onde o usuário irá digitar *observações* adicionais.
└── Informações da rede             # 3 - Planilha das informações de *rede* com as seguintes informações a serem preenchidas (Ex:IP, Mascara, Gateway)
          ├── IP                    # Coluna 3.1 - Caixa de número onde o usuário irá digitar *IP* da minha rede
          ├── Máscara de Rede       # Coluna 3.2 - Caixa de número onde o usuário irá digitar *máscara* da minha rede
          ├── Gateway               # Coluna 3.3 - Caixa de número onde o usuário irá digitar *gateway* da minha rede
          ├── DNS Primário          # Coluna 3.4 - Caixa de número onde o usuário irá digitar *DNS* da minha rede
          ├── DNS Secundário        # Coluna 3.5 - Caixa de número onde o usuário irá digitar *DNS* da minha rede
          └── Observações           # Coluna 3.6 - Caixa de texto onde o usuário irá digitar *observações* adicionais.
```

---

**Exemplo da estrutura do campo IPBX Cloud:**

```css
*IPBX Cloud*
├── Modelo                          # Caixa de texto onde o usuário irá digitar o *modelo* do servidor (Ex:Newcloud)
├── Versão                          # Caixa de número onde o usuário irá digitar a *versão* do servidor (EX:3.19)
├── IP interno                      # Caixa de número onde o usuário irá digitar *IP* de acesso interno ao servidor IPBX/PABX da minha rede de internet (Ex:192.168.0.0).
├── IP externo                      # Caixa de número onde o usuário irá digitar *IP* de acesso remoto ao servidor IPBX/PABX de outra rede de internet (Ex:192.168.0.0:xx).
├── Porta de acesso Web             # Caixa de número onde o usuário irá digitar a porta *WEB* de acesso a interface IPBX/PABX de outra rede de internet (Ex:xxx.xxx.xx.x:2080).
├── Senha da interface Web          # Caixa de número onde o usuário irá digitar a senha de acesso *WEB* do IPBX/PABX de outra rede de internet (Ex:12345).
├── Porta de acesso SSH             # Caixa de número onde o usuário irá digitar a porta *SSH* de acesso remoto ao IPBX/PABX de outra rede de internet (Ex:xxx.xxx.xx.x:2022).
├── Senha de acesso remoto SSH      # Caixa de número onde o usuário irá digitar a senha de acesso *SSH* do IPBX/PABX de outra rede de internet (Ex:12345).
├── Observações                     # Caixa de texto onde o usuário irá digitar *observações* adicionais.
├── Ramais                          # 1 - Planilha dos *ramais* criados no servidor IPBX/PABX com colunas auto ajustaveis (Ex:ramais, Senhas, IP, Nome).
|    ├── Faixa de ramais            # Coluna 1.1 - Caixa de número onde o usuário irá digitar o *número* do ramal criado no IPBX/PABX (Ex:2002).
|    ├── Senhas                     # Coluna 1.2 - Caixa de número onde o usuário irá digitar a *senha* criada para registrar o ramal no IPBX/PABX (Ex:12345).
|    ├── IP do aparelho             # Coluna 1.3 - Caixa de número onde o usuário irá digitar o *IP* do aparelho telefonico (Ex:192.168.xx.x).
|    ├── Nome                       # Coluna 1.4 - Caixa de texto onde o usuário irá digitar *nome* da pessoa que utiliza o ramal (Ex:José).
|    ├── Localidade                 # Coluna 1.5 - Caixa de texto onde o usuário irá digitar o nome do *local* aonde o ramal foi instalado (EX:Financeiro).
|    └── Gravação                   # Coluna 1.6 - Caixa de seleção onde o usuário irá selecionar para saber se o ramal pode *gravar* as ligações ou não.
|         └── Observações           # Coluna 1.7 - Caixa de texto onde o usuário irá digitar *observações* adicionais.
├── Informações da operadora        # *Operadora* registrada no servidor IPBX/PABX.
|    ├── Número piloto              # Caixa de número onde o usuário irá digitar o *número piloto* registrado no IPBX/PABX (Ex:(27)3372-8000).
|    ├── Tipo do tronco             # Caixa de texto onde o usuário irá digitar o *tipo do tronco* registrado no IPBX/PABX (Ex:SIP).
|    ├── Operadora                  # Caixa de texto onde o usuário irá digitar o nome da *operadora* registrado no IPBX/PABX (Ex:ZAFEX).
|    ├── Quantidade de DDR          # Caixa de número onde o usuário irá digitar range de *DDR* que a operadora disponibilizou (Ex:33727000 - 7100).
|    ├── Canais                     # Caixa de número onde o usuário irá digitar a quantidade de *canais* disponibilizado pela operadora (Ex:10).
|    ├── IP Proxy da Operadora      # Caixa de número onde o usuário irá digitar *IP Proxy* da operadora (Ex:192.xxx.xx.x).
|    ├── Porta do Proxy             # Caixa de número onde o usuário irá digitar a *porta* do Proxy da operadora (Ex:5060).
|    └── IP Tráfego do áudio        # Caixa de número onde o usuário irá digitar o *IP* de trafego do audio da operadora (Ex:192.xxx.xx.x).
|         └── Observações           # Caixa de texto onde o usuário irá digitar *observações* adicionais.
├── Informações do dispositivo      # 2 - Planilha dos *dispositiovos* interligados ao servidor IPBX/PABX com colunas auto ajustaveis (Ex:Tipo do dispositivo, IP)
|    ├── Tipo de dispositivo        # Coluna 2.1 - Caixa de texto onde o usuário irá digitar o *tipo do dispositivo* (Ex:FXS, FXO, ATA, Aligera, E1)
|    ├── IP do dispositivo          # Coluna 2.2 - Caixa de número onde o usuário irá digitar o *IP* de acesso ao dispositivo (Ex:192.xxx.xx.x)
|    └── Senha do dispositivo       # Coluna 2.3 - Caixa de número onde o usuário irá digitar a *senha* de acesso a interface do dispotisivo (Ex:12345)
|         └── Observações           # Coluna 2.4 - Caixa de texto onde o usuário irá digitar *observações* adicionais.
└── Informações da rede             # 3 - Planilha das informações de *rede* com colunas auto ajustaveis (Ex:IP, Mascara, Gateway)
     ├── IP                         # Coluna 3.1 - Caixa de número onde o usuário irá digitar *IP* da minha rede
     ├── Máscara de Rede            # Coluna 3.2 - Caixa de número onde o usuário irá digitar *máscara* da minha rede
     ├── Gateway                    # Coluna 3.3 - Caixa de número onde o usuário irá digitar *gateway* da minha rede
     ├── DNS Primário               # Coluna 3.4 - Caixa de número onde o usuário irá digitar *DNS* da minha rede
     ├── DNS Secundário             # Coluna 3.5 - Caixa de número onde o usuário irá digitar *DNS* da minha rede
     └── Observações                # Coluna 3.6 - Caixa de texto onde o usuário irá digitar *observações* adicionais.
```

---

**Exemplo da estrutura do campo Chatbot:**

```css
*Chatbot*
├── Modelo                               # Caixa de texto onde o usuário irá digitar o *modelo* do chatbot (Ex:chatbot, chatbot+IA).
├── ID                                   # Caixa de texte onde o usuário irá digitar o *ID* de identifcação do Chatbot (Ex:4152).
├── Data da ativação                     # Caixa de número onde o usuário irá digitar a *data* de quando foi ativado o Chatbot (Ex:14/12/2025).
├── Número de telefone                   # Caixa de número onde o usuário irá digitar o *número* de contato utilizado para autenticar no Chatbot (Ex:(27)9 9000-0000).
├── Link de acesso                       # Caixa de link clicavel onde o usuário irá digitar o *link* de acesso a interface do chatbot (Ex:https://chatbot/home.com.br).
├── Plano contratado                     # Caixa de texto onde o usuário irá digitar o *plano* que contratou (Ex:Pro, ultimate, basico).
├── Quantidade de usuários               # Caixa de número onde o usuário irá digitar a *quantidade* de usuários que contratou no Chatbot (Ex:30 - usuários).
├── Quantidade de supervisores           # Caixa de número onde o usuário irá digitar a *quantidade* de supervisores que contratou no Chatbot (Ex:6 - usuários).
├── Quantidade de administradores        # Caixa de número onde o usuário irá digitar a *quantidade* de administradores que contratou no Chatbot (Ex:2 - usuários).
├── Login de admin                       # Caixa de texto onde o usuário irá digitar o *login* de admin (Ex:admin).
├── Senha de admin                       # Caixa de número onde o usuário irá digitar a *senha* de admin (Ex:senha123).
├── login Super-admin                    # Caixa de texto onde o usuário irá digitar o *login* de super-admin (Ex:super-admin).
├── Super-senha                          # Caixa de número onde o usuário irá digitar a *senha* de super-admin (Ex:senha123).
├── Nome do responsavel                  # Caixa de texto onde o usuário irá digitar o *nome* do responsavel pelo chatbot (Ex:Rafael - gestor)
├── Número do responsavel                # Caixa de número onde o usuário irá digitar o *número* para contato do responsavel pelo chatbot (Ex:(27)9 9000-0000)
├── E-mail do responsavel                # Caixa de texto onde o usuário irá digitar o *e-mail* do responsavel pelo chatbot (Ex:empresa@gmail.com)
├── Redes sociais                        # Caixa de seleção das redes sociais autenticadas no Chatbot (Ex:Facebook,Instagram, WhatsApp, Integração, Outros).
├── Comunicação em massa                 # 1 - Planilha de informações dos sistemas de *comunicação em massa* com colunas auto ajustaveis (Ex:Nome, Data, Número).
|         ├── Nome do sistema            # Coluna 1.1 - Caixa de texto onde o usuário irá digitar o *nome do sistema* que utiliza para comunicação em massa (Ex:Fireblick).
|         ├── Data de ativação           # Coluna 1.2 - Caixa de número onde o usuário irá digitar a *data de ativação* do sistema de cominicação em massa (Ex:14/12/2025).
|         ├── Número utilizado           # Coluna 1.3 - Caixa de número onde o usuário irá digitar o número de telefone utilizado para comunicação em massa (Ex:(27) 3372-7000).
|         ├── Tipo de homologação        # Coluna 1.4 - Caixa de texto onde o usuário irá digitar o *tipo de homologação* utilizado para comunicação em massa (Ex:Meta ou WhatsApp Busines).
|         ├── Likn de acesso             # Coluna 1.5 - Caixa de link clicavel onde o usuário irá digitar o *link* de acesso a interface da comunicação em massa (Ex:https://chatbot/home.com.br).
|         ├── Login                      # Coluna 1.6 - Caixa de texto onde o usuário irá digitar o *login* de acesso ao sistema de comunicação em massa (Ex:admin).
|         ├── Senha                      # Coluna 1.7 - Caixa de número onde o usuário irá digitar a *senha* de acesso ao sistema de comunicação em massa (Ex:senha123).
|         ├── Responsavel                # Coluna 1.8 - Caixa de texto onde o usuário irá digitar o *nome* do responsavel pelo chatbot (Ex:Rafael - gestor)
|         ├── Restrições                 # Planilha 1.9 - de *restrições* onde será informado as vezes que o sistema sofre um banimento, com colunas auto ajustaveis (Ex:Data,Duração, Número).
|         |    ├── Data da restrição     # Coluna 1.9.1 - Caixa de número onde o usuário irá digitar a *data da restrição* de quando o número sofreu o banimento (Ex:14/12/2025).
|         |    ├── Duração               # Coluna 1.9.1 - Caixa de número onde o usuário irá digitar por quanto tempo *durou* a restrição (Ex:24H).
|         |    └── Número restrito       # Coluna 1.9.1 - Caixa de número onde o usuário irá digitar o *número* que foi restrito (Ex:(27)3372-7000).
|         └── Números secundarios        # Coluna 1.10 - Caixa de número onde o usuário irá digitar os *números* utilizados no sistema de comnicação em massa (Ex:(27)3372-7000).
├── Usuários                             # 2 - Planilha dos *usuários* cadastrados no Chatbot com colunas auto ajustaveis (Ex:Nome, Login, Senha).
|         ├── Nome                       # Coluna 2.1 - Caixa de texto onde o usuário irá digitar os *nome* dos usuários cadastrados no Chatbot (Ex:Miguel).
|         ├── Login                      # Coluna 2.2 - Caixa de texto onde o usuário irá digitar os *logins* dos usuários cadastrados no Chatbot (Ex:192.168.xx.x).
|         ├── Senha                      # Coluna 2.3 - Caixa de número onde o usuário irá digitar as *senhas* dos usuários cadastrados no Chatbot (Ex:Senha123).
|         ├── E-mail                     # Coluna 2.4 - Caixa de texto onde o usuário irá digitar os *e-mail* dos usuários cadastrados no Chatbot (Ex:empresa@gmail.com).
|         ├── Tipo                       # Coluna 2.5 - Caixa de texto onde o usuário irá digitar os *tipo* do nível de aceesso dos usuários (Ex:Administrador, Supervisor, Agente, Agente-supervisor).
|         └── Observações                # Coluna 2.6 - Caixa de texto onde o usuário irá digitar *observações* adicionais.
└── Observações                          # Caixa de texto onde o usuário irá digitar *observações* adicionais.
```

---

**Exemplo da estrutura do campo de Linha Telefonica**

```css
*Linha Telefonica*
├── Número Piloto                   # Caixa de número onde o usuário irá digitar o *número piloto* da linha (Ex:(27)3372-7000).
├── Tipo da linha                   # Caixa de texto onde o usuário irá digitar o *tipo da linha* (EX:Linha analogica, Linha Sip).
├── Operadora                       # Caixa de número onde o usuário irá digitar a *operadora* da linha telefonica (Ex:Zafex, Nvoip).
├── Quantidade de canais            # Caixa de número onde o usuário irá digitar *Quantos canais* contratado (Ex:30 canais).
├── DDR                             # Caixa de número onde o usuário irá digitar o inicio e fim dos *DDR* disponibilizados pela operadora (Ex:3372-7000 até 7085).
├── Portabilidade                   # Caixa seletora onde o usuário irá selecionar se o número foi *portado* ou não (Ex:Sim, Não).
├── Data Portabilidade              # Caixa de número onde o usuário irá digitar a *data* da portabilidade (Ex:17/12/2025).
├── Operadora Anterior              # Caixa de texto onde o usuário irá digitar o nome da *operadora* anterior (Ex:Vivo, Oi, Claro).
├── Data Ativação                   # Caixa de número onde o usuário irá digitar a *data* da ativação da linha telefonica (EX:14/12/2025).
├── Data vencimento                 # Caixa de número onde o usuário irá digitar a *data* de vencimento da linha telefonica (EX:14/12/2025).
├── Status da Linha                 # Caixa seletora onde o usuário irá selecionar o *status* da linha (EX:Ativado, Portando, Cancelado, Pausado).
└── Observações                     # Caixa de texto onde o usuário irá digitar *observações* adicionais.
```

---

## EXEMPLO DA ESTRUTURA DO SISTEMA DE TAREFAS

**Exemplo das funções do campo de Tarefas:**

```css
newbase_tarefas
├── Cadastrar nova tarefa
│   ├── Tarefa para
|   |   ├── Selecione a empresa
|   |   └── Cadastre uma nova empresa
│   ├── Tarefa será executada por
|   |   ├── Selecione o nome do colaborador
|   |   └── Cadastrar um novo colaborador
|   ├── Agendar a tarefa em
|   |   ├── Selecione a data
|   |   └── Selecione a hora
│   ├── Gerar o número da tarefa automaticamente
│   ├── Descrição da tarefa*
│   ├── Selecione o tipo de tarefa*
│   ├── Selecione o nível de prioridade
│   ├── Selecione o tipo de check-in
|   |   ├── Automático
|   |   └── Manual
│   ├── Selecione para enviar um e-mail com tarefa criada
│   └── categoria
│
├── Status e Datas
│   ├── status (Aberto, Em Andamento, Pausado, Resolvido, Fechado)
|   |   ├── Aberto
|   |   ├── Em Andamento
|   |   ├── Pausado
|   |   ├── Resolvido
|   |   └── Fechado
|   ├── Quando for check-in Automatico
│   |   ├── Gerar automaticamente a data e hora de criação da tarefa
│   |   ├── Gerar automaticamente a data e hora de atualização da tarefa
│   |   ├── Gerar automaticamente a data e hora de resolução da tarefa
│   |   └── Gerar automaticamente a data e hora de fechamento da tarefa
|   ├── Quando for check-in Manual
│   |   ├── Colocar manualmente a data e hora de criação da tarefa
│   |   ├── Colocar manualmente a data e hora de atualização da tarefa
│   |   ├── Colocar manualmente a data e hora de resolução da tarefa
│   |   └── Colocar manualmente a data e hora de fechamento da tarefa
│
├── Tempo gasto em horas
│   ├── Calcular e gerar automaticamente o tempo total gasto na tarefa
│   └── Calcular e gerar automaticamente o tempo tecnico gasto na tarefa
|
├── Mapa de Localização
|   ├── Mapa Leaflet
|   ├── Marcar no mapa a localização da tarefa
|   ├── Calcular a distância da minha localização até a localização da tarefa
|   └── Digite o Valor da gasolina a ser calculado de acordo com a distância pecorrida pelo veiculo
|
├── Assinatura digital
|   ├── Captura via mouse/touch
|   ├── Exporta como Base64 (PNG)
|   ├── Armazena nome e CPF do assinante
|   └── Exibe em relatórios/PDF
|
└── Opções
    ├── Excluir tarefa
    ├── Editar tarefa
    └── Reagendar tarefa
```

## Interface Newbase - Guia Visual

**Fluxo Antes vs Depois:**

*ANTES (Comportamento Anterior):*

```yaml
┌─────────────────────────────────────────────────┐
│ Dados Pessoais (Cliente)                        │
├─────────────────────────────────────────────────┤
│                                                 │
│ Abas:                                           │
│ [Detalhes] [IPBX/PABX] [Newcloud] [...]         │
│            ↑                                    │
│            Clica aqui                           │
│                                                 │
│ ┌───────────────────────────────────────────┐   │
│ │ [+ Adicionar Newsic]                          │
│ │                                               │
│ │ ┌─────────────────────────────────────┐       │
│ │ │ ID │ Modelo │ IP  │ Status │ Data       │   │
│ │ ├─────────────────────────────────────┤       │
│ │ │ 1  │ Newcloud │ ... │ Ativo │ ... │         │
│ │ └─────────────────────────────────────┘       │
│ └───────────────────────────────────────────┘   │
│                                                 │
│    Clicando em "+ Adicionar Newsic"             │
│    → REDIRECIONA para nova página               │
│    → Usuário sai do contexto do cliente         │
│    → Precisa voltar manualmente                 │
└─────────────────────────────────────────────────┘
```

*DEPOIS (Novo Comportamento):*

```yaml
┌─────────────────────────────────────────────────┐
│ Campo 1 -  Dados Pessoais                    ▼] │
├─────────────────────────────────────────────────┤
│ Campo 2 -  IPBX/PABX            Clica aqui → ▼] │─→ Exibe os formulrios divididos por cards
├─────────────────────────────────────────────────┤
│   ┌──────────────────────────────────────┐      │
│   │ Informações do Servidor              │      │
│   │ ┌───────────────┬────────────────┐   │      │
│   │ │ Modelo: [___] │ Versão: [___]  │   │      │
│   │ ├───────────────┼────────────────┤   │      │
│   │ │ IP Interno:   │ IP Externo:    │   │      │
│   │ │ [___________] │ [___________]  │   │      │
│   │ ├───────────────┼────────────────┤   │      │
│   │ │ Porta Web:    │ Senha Web:     │   │      │
│   │ │ [___________] │ [____________] │   │      │
│   │ ├───────────────┼────────────────┤   │      │
│   │ │ Porta SSH:    │ Senha SSH:     │   │      │
│   │ │ [___________] │ [____________] │   │      │
│   │ └───────────────┴────────────────┘   │      │
│   │                                      │      │
│   │ Observações:                         │      │
│   │  [_________________________________] │      │
│   │                                      │      │
│   └──────────────────────────────────────┘      │
│                                                 │
│  Sem botão "+ Adicionar Newsic"                 |
│    → Formulário carrega INLINE                  │
│    → Usuário permanece em seu cadastro          │
└─────────────────────────────────────────────────┘
```

**Campos expansíveis disponíveis:**

*Seção 1: Dados Pessoais:*

```yaml
┌───────────────────────────────────────────────────┐
│ Dados Pessoais *                                  │
│ ┌───────────────────────────────────────────────┐ │
│ │ [Selecione para expandir os formularios   ▼]  │ │
│ └───────────────────────────────────────────────┘ │
└───────────────────────────────────────────────────┘
```

*Seção 2: IPBX/PABX:*

```yaml
┌───────────────────────────────────────────────────┐
│ IPBX/PABX *                                       │
│ ┌───────────────────────────────────────────────┐ │
│ │ [Selecione para expandir os formularios   ▼]  │ │
│ └───────────────────────────────────────────────┘ │
├───────────────────────────────────────────────────┤
│     ┌──────────────────────────────────────┐      │
│     │ Informações do Servidor              │      │
│     │ ┌───────────────┬────────────────┐   │      │
│     │ │ Modelo: [___] │ Versão: [___]  │   │      │
│     │ ├───────────────┼────────────────┤   │      │
│     │ │ IP Interno:   │ IP Externo:    │   │      │
│     │ │ [___________] │ [___________]  │   │      │
│     │ ├───────────────┼────────────────┤   │      │
│     │ │ Porta Web:    │ Senha Web:     │   │      │
│     │ │ [___________] │ [____________] │   │      │
│     │ ├───────────────┼────────────────┤   │      │
│     │ │ Porta SSH:    │ Senha SSH:     │   │      │
│     │ │ [___________] │ [____________] │   │      │
│     │ └───────────────┴────────────────┘   │      │
│     │ Observações:                         │      │
│     │  [_________________________________] │      │
│     │                                      │      │
│     └──────────────────────────────────────┘      │
│                                                   │
│     Sem botão "+ Adicionar Newsic"                |
└───────────────────────────────────────────────────┘
```

*Seção 3: IPBX Cloud:*

```yaml
┌───────────────────────────────────────────────────┐
│ IPBX Cloud *                                      │
│ ┌───────────────────────────────────────────────┐ │
│ │ [Selecione para expandir os formularios   ▼]  │ │
│ └───────────────────────────────────────────────┘ │
├───────────────────────────────────────────────────┤
│     ┌──────────────────────────────────────┐      │
│     │ Informações do Servidor              │      │
│     │ ┌───────────────┬────────────────┐   │      │
│     │ │ Modelo: [___] │ Versão: [___]  │   │      │
│     │ ├───────────────┼────────────────┤   │      │
│     │ │ IP Interno:   │ IP Externo:    │   │      │
│     │ │ [___________] │ [___________]  │   │      │
│     │ ├───────────────┼────────────────┤   │      │
│     │ │ Porta Web:    │ Senha Web:     │   │      │
│     │ │ [___________] │ [____________] │   │      │
│     │ ├───────────────┼────────────────┤   │      │
│     │ │ Porta SSH:    │ Senha SSH:     │   │      │
│     │ │ [___________] │ [____________] │   │      │
│     │ └───────────────┴────────────────┘   │      │
│     │ Observações:                         │      │
│     │  [_________________________________] │      │
│     │                                      │      │
│     └──────────────────────────────────────┘      │
│                                                   │
│     Sem botão "+ Adicionar Newcloud"              |
└───────────────────────────────────────────────────┘
```

*Seção 4: Linha Telefonica:*

```yaml
┌───────────────────────────────────────────────────┐
│ Linha Telefonica *                                │
│ ┌───────────────────────────────────────────────┐ │
│ │ [Selecione para expandir os formularios   ▼]  │ │
│ └───────────────────────────────────────────────┘ │
└───────────────────────────────────────────────────┘
```

*Seção 5: Chatbot:*

```yaml
┌───────────────────────────────────────────────────┐
│ Chatbot *                                         │
│ ┌───────────────────────────────────────────────┐ │
│ │ [Selecione para expandir os formularios   ▼]  │ │
│ └───────────────────────────────────────────────┘ │
└───────────────────────────────────────────────────┘
```

*Seções Expandíveis:*

```yaml
┌─────────────────────────────────────┐
│ [+ Adicionar Ramal]                 │
│ [Tabela de Ramais Existentes]       │
├─────────────────────────────────────┤
│ [+ Adicionar Tronco]                │
│ [Tabela de Troncos Existentes]      │
├─────────────────────────────────────┤
│ [+ Adicionar Conversor]             │
│ [Tabela de Conversores Existentes]  │
├─────────────────────────────────────┤
│ [Configurar Rede]                   │
│ [Informações de Rede]               │
└─────────────────────────────────────┘
```

### Funcionalidade de Senhas

```yaml
- Campo em modo "text" (sempre visível)

Exemplo: Senha Web: [senha123456]

```

### Fluxo de Submissão

```yaml
 ┌──────────────────┐
 │ Usuário Clica em │
 │ "IPBX/PABX"      │
 └────────┬─────────┘
          │
          ▼
┌────────────────────┐
│ Formulário Carrega │
│                    │
└────────┬───────────┘
         │
         ▼
 ┌──────────────────┐
 │ Usuário Preenche │
 │ os Campos        │
 └────────┬─────────┘
          │
          ▼
┌───────────────────┐
│ Clica em "Salvar" │
└─────────┬─────────┘
          │
          ▼
┌──────────────────────┐
│ Valida dados no      │
│ banco de dado,       │
│ caso aja duplicidade │
│ retorna um pop-up    │
│ informando que o     │
│ cadastro já existe   │
└──────────┬───────────┘
           │
           ▼
     ┌──────────┐
     │ Sucesso? │
     └─────┬────┘
           │
           ▼
 ┌──────────────────┐
 │ Recarrega Página │
 │ Novo Item        │
 │ Aparece na Lista │
 └──────────────────┘
```

### Responsividade

```yaml
DESKTOP (≥992px)          TABLET (768-991px)      MOBILE (<768px)
┌─────────────────────┐     ┌──────────────┐      ┌──────────────┐
│  [Modelo] [Versão]  │     │   [Modelo]   │      │   [Modelo]   │
├──────────┬──────────┤     ├──────────────┤      ├──────────────┤
│ [IP Int] │ [IP Ext] │     │ [IP Interno] │      │ [IP Interno] │
├──────────┼──────────┤     ├──────────────┤      ├──────────────┤
│ [Porta]  │ [Senha]  │     │ [Porta Web]  │      │ [Porta Web]  │
└──────────┴──────────┘     ├──────────────┤      ├──────────────┤
                            │ [Porta SSH]  │      │ [Porta SSH]  │
                            └──────────────┘      └──────────────┘
```
