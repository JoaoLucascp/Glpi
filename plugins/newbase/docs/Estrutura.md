# inicio

Te dou acesso as pastas, não crie scripts para eu fazer.
Aplique as correções diretamente no plugin Newbase, não mecha nos código do glpi.
Não quebre meu código.
Sou iniciante, não sei programa. Mas tenho expertise em informática.

Esta é a documentação do meu plugin: `D:\laragon\www\glpi\plugins\newbase\docs\DOCUMENTACAO_TECNICA_V2.1.0.md`

Coloque no arquivo `DOCUMENTACAO_TECNICA_V2.1.0.md` todos os erros encontrado e as correções aplicadas por você.

Observação: Não coloque na documentação erros repetidos e nem soluções repetidas.

apenas novos erros e novas soluções.

Preciso saber como resolver esse erro:

## Ambiente de Desenvolvimento

```yaml
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
```

## Plugin Newbase - Informações Completas

```yaml
Nome do Plugin: Newbase
Versão: 2.1.0
Compatibilidade GLPI: 10.0.20+
PHP Mínimo: 8.1+
Autor: João Lucas
Descrição Completa: Sistema completo de Gestão de documentação de empresas
para GLPI com gerenciamento de empresas, documentação de servidor telefônico baseado em asterisk, documentação de servidor telefônico em nuvem baseado em asterisk, documentação de sistema Chatbot Omnichannel, documentação de linha fixa, . Gestão de tarefas com geolocalização, assinatura digital e cálculo de quilometragem.
Licença: GPLv2+
```

## Documentação Oficial

```yaml
- GLPI Developer Docs: https://glpi-developer-documentation.readthedocs.io/
- GLPI API Docs: https://github.com/glpi-project/glpi/blob/master/apirest.md
- Leaflet Docs: https://leafletjs.com/reference.html
- Brasil API: https://brasilapi.com.br/docs
- ViaCEP: https://viacep.com.br/
```

## Comunidade

```yaml
- Fórum GLPI: https://forum.glpi-project.org/
- GitHub Issues: https://github.com/glpi-project/glpi/issues
- Telegram BR: https://t.me/glpibr
- Service Desk Brasil: https://blog.servicedeskbrasil.com.br/plugin-fields/
- GitHub Oaugustus: https://github.com/oaugustus/blog/blob/master/glpi/desenvolvimento-de-plugins.md
```

## Estrutura de pastas do plugin

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

**Estrutura da aba Empresas:**

```yaml
*glpi_plugin_newbase_company
├── Dados da Empresa:
│   ├── Buscar CNPJ automaticamente e preencher os campos abaixo
│   ├── Id
│   ├── Nome Fantasia
│   ├── Nome Razao Social
│   ├── E-mail
│   └── Telefone
├── Endereço:
│   ├── Buscar CEP automaticamente e preencher os campos abaixo
│   ├── Numero
│   ├── Complemento
│   ├── Birro
│   ├── Rua
│   ├── Cidade
│   ├── Estado
│   ├── Pais
│   ├── Latitude
│   └── Longitude
├── Status:
│    ├── Com contrato ativo
│    ├── Sem contrato
│    └── Contrato cancelado
└── Observações: (Campo de texto para observações adicionais).
```

---

**Exemplo da estrutura da aba Servidor IPBX:**

```yaml
*glpi_plugin_newbase_ipbx
└── Informações do Servidor:
│   ├── Modelo (Campo de texto - Ex:Newcloud).
│   ├── Versão servidor (Campo de número - EX:3.19).
│   ├── IP interno (Campo de número IP - Ex:192.168.0.0).
│   ├── IP externo (Campo de número IP - Ex:192.168.0.0:xx).
│   ├── Porta acesso Web (Campo de número para portas de acesso web - Ex:xxx.xxx.xx.x:2080).
│   ├── Senha acesso Web (Campo de número para senhas de acesso WEB - Ex:12345).
│   ├── Porta acesso SSH (Campo de número para portas SSH Ex:xxx.xxx.xx.x:2022).
│   ├── Senha acesso SSH (Campo de número para senhas de acesso SSH - Ex:12345).
│   ├── Observações (Campo de texto para observações adicionais).
└── Ramais: (1 - Formulario dos ramais do Servidor Telefonia - Ex:ramais, Senhas, IP, Nome).
    ├── Faixa de ramais (Coluna 1.1 - Campo de número - Ex:2002).
    ├── Senhas (Coluna 1.2 - Campo de número - Ex:12345).
    ├── IP dos aparelhos (Coluna 1.3 - Campo de número - Ex:192.168.xx.x).
    ├── Nome dos usuários (Coluna 1.4 - Campo de texto - x:José).
    ├── Localidade (Coluna 1.5 - Campo de texto - EX:Financeiro).
    ├── Gravação (Coluna 1.6 - Campo de seleção Sim e Não).
    └── Observações (Coluna 1.7 - Campo de texto para  observações adicionais).
```

---

**Exemplo da estrutura da aba Servidor Cloud:**

```yaml
*glpi_plugin_newbase_cloud
└── Informações do Servidor:
│   ├── Modelo (Campo de texto - Ex:Newcloud).
│   ├── Versão servidor (Campo de número - EX:3.19).
│   ├── IP interno (Campo de número IP - Ex:192.168.0.0).
│   ├── IP externo (Campo de número IP - Ex:192.168.0.0:xx).
│   ├── Porta acesso Web (Campo de número para portas de acesso web - Ex:xxx.xxx.xx.x:2080).
│   ├── Senha acesso Web (Campo de número para senhas de acesso WEB - Ex:12345).
│   ├── Porta acesso SSH (Campo de número para portas SSH Ex:xxx.xxx.xx.x:2022).
│   ├── Senha acesso SSH (Campo de número para senhas de acesso SSH - Ex:12345).
│   ├── Observações (Campo de texto para observações adicionais).
└── Ramais: (1 - Formulario dos ramais do Servidor Telefonia - Ex:ramais, Senhas, IP, Nome).
    ├── Faixa de ramais (Coluna 1.1 - Campo de número - Ex:2002).
    ├── Senhas (Coluna 1.2 - Campo de número - Ex:12345).
    ├── IP dos aparelhos (Coluna 1.3 - Campo de número - Ex:192.168.xx.x).
    ├── Nome dos usuários (Coluna 1.4 - Campo de texto - x:José).
    ├── Localidade (Coluna 1.5 - Campo de texto - EX:Financeiro).
    ├── Gravação (Coluna 1.6 - Campo de seleção Sim e Não).
    └── Observações (Coluna 1.7 - Campo de texto para  observações adicionais).
```

---

**Exemplo da estrutura da aba Dispositivos:**

```yaml
*glpi_plugin_newbase_dispositivos
└── Informações dispositivos: (2 - Formulario dos dispositiovos interligados ao Servidor Telefonia).
    ├── Tipo de dispositivo (Coluna 2.1 - Campo de texto - Ex:FXS, FXO, ATA, Aligera, E1).
    ├── IP do dispositivo (Coluna 2.2 - Campo de número para IP do dispositivo - Ex:192.xxx.xx.x).
    ├── Senha do dispositivo (Coluna 2.3 - Campo de número para senha de acesso ao dispotisivo - Ex:12345).
    └── Observações (Coluna 2.4 - Campo de texto para observações adicionais).
```

---

**Exemplo da estrutura da aba Rede:**

```yaml
*glpi_plugin_newbase_rede
└── Informações de rede: (3 - Formulario das informações de rede - Ex:IP, Mascara, Gateway).
    ├── IP (Coluna 3.1 - Campo de número para IP da rede).
    ├── Máscara de Rede (Coluna 3.2 - Campo de número para máscara da rede).
    ├── Gateway (Coluna 3.3 - Campo de número para gateway da rede).
    ├── DNS Primário (Coluna 3.4 - Campo de número para DNS da  rede).
    ├── DNS Secundário (Coluna 3.5 - Campo de número para DNS da rede).
    └── Observações (Coluna 3.6 - Campo de texto para observações adicionais).
```

---

**Exemplo da estrutura aba Chatbot:**

```yaml
*glpi_plugin_newbase_chabot
└── Configurações do Chatbot:
│   ├── Modelo (Campo de texto para modelo de chatbot - Ex:Newbot, Newbot+IA).
│   ├── ID (Campo de texte para ID* de identifcação do Chatbot - Ex:4152).
│   ├── Data da ativação (Campo de número para data de ativação - Ex:14/12/2025).
│   ├── Número de telefone (Campo de número para número* autenticado no Chatbot - Ex:(27)90000-0000).
│   ├── Link de acesso (Campo de link clicavel para link  de acesso a interface - Ex:https://chatbot/home.com.br).
│   ├── Plano contratado (Campo de texto para plano contratado - Ex:Pro, ultimate, basico).
│   ├── Quantidade de usuários (Campo de número para quantidade de usuários contratado - Ex:30 - usuários).
│   ├── Quantidade de supervisores (Campo de número para quantidade de supervisores contratado - (Ex:6 - usuários).
│   ├── Quantidade de administradores (Campo de número para quantidade  de administradores contratado - Ex:2 - usuários).
│   ├── Login de admin (Campo de texto para login - Ex:admin).
│   ├── Senha de admin (Campo de número para senha de admin - Ex:senha123).
│   ├── login Super-admin (Campo de texto para login de super-admin - Ex:super-admin).
│   ├── Super-senha (Campo de número para senha de super-admin - Ex:senha123).
│   ├── Nome do responsavel (Campo de texto para nome dos responsaveis - Ex:Rafael - gestor)
│   ├── Número do responsavel (Campo de número para número dos responsaveis - Ex:(27)9 9000-0000)
│   ├── E-mail do responsavel (Campo de texto para e-mail dos responsaveis - Ex:empresa@gmail.com)
│   ├── Redes sociais (Campo de texto para redes sociais autenticadas no Chatbot - Ex:Facebook,Instagram, WhatsApp).
│   ├── Comunicação em massa (1 - Formulario de informações do sistema de comunicação em massa - Ex:Nome, Data, Número).
│   ├── Nome do sistema (Coluna 1.1 - Campo de texto para nome do sistema - Ex:Fireblick).
│   ├── Data de ativação (Coluna 1.2 - Campo de número para data de ativação - Ex:14/12/2025).
│   ├── Número Autenticado (Coluna 1.3 - Campo de número para telefone autenticado - Ex:(27) 3372-7000).
│   ├── Tipo de homologação (Coluna 1.4 - Campo de texto para tipo de homologação - Ex:Meta ou WhatsApp Busines).
│   ├── Likn de acesso (Coluna 1.5 - Campo de link clicavel para link de acesso - Ex:https://chatbot/home.com.br).
│   ├── Login (Coluna 1.6 - Campo de texto para login de acesso ao sistema - Ex:admin).
│   ├── Senha (Coluna 1.7 - Campo de número para senha de acesso ao sistema - Ex:senha123).
│   ├── Responsavel (Coluna 1.8 - Campo de texto para nome do responsavel - Ex:Rafael - gestor)
├── Restrições (Planilha 1.9 - Informações de restrições com colunas auto ajustaveis (Ex:Data,Duração, Número).
│   ├── Data da restrição (Coluna 1.9.1 - Campo de número para data da restrição - Ex:14/12/2025).
│   ├── Duração (Coluna 1.9.1 - Campo de número para duração da restrição - Ex:24H).
│   └── Número restrito (Coluna 1.9.1 - Campo de número para telefone que foi restrito - Ex:(27)3372-7000).
│   └── Números secundarios (Coluna 1.10 - Campo de número para telefone secundarios - Ex:(27)3372-7000).
├── Usuários (2 - Formulario dos usuários cadastrados no Chatbot com colunas auto ajustaveis - Ex:Nome, Login, Senha).
│   ├── Nome (Coluna 2.1 - Campo de texto para nome dos usuários - Ex:Miguel).
│   ├── Login (Coluna 2.2 - Campo de texto para logins dos usuários - Ex:bot.miguel).
│   ├── Senha (Coluna 2.3 - Campo de número para senhas dos usuários - Ex:Senha123).
│   ├── E-mail (Coluna 2.4 - Campo de texto para e-mail dos usuários (Ex:empresa@gmail.com).
│   ├── Tipo (Coluna 2.5 - Campo de texto para tipo de aceesso dos usuários (Ex:Administrador ou Agente).
│   └── Observações (Coluna 2.6 - Campo de texto para observações adicionais.
└── Observações (Campo de texto para observações adicionais.
```

---

**Exemplo da estrutura do aba Linha Telefonica:**

```yaml
*glpi_plugin_newbase_linha_fixa
└── Informações operadora: (Formulario da operadora registrada no Servidor Telefonia.
    ├── Número Piloto (Campo de número para número piloto da linha - Ex:(27)3372-7000).
    ├── Tipo da linha (Campo de texto para tipo da linha - EX:Linha analogica, Linha Sip).
    ├── Operadora (Campo de número para operadora da linha telefonica - Ex:Zafex, Nvoip).
    ├── Quantidade de canais (Campo de número para Quantos canais contratado - Ex:30 canais).
    ├── Quantidade de DDR (Campo de número  - Ex:33727000 - 7100).
    ├── Portabilidade (Campo seletora para portabilidade feita ou não - Ex:Sim, Não).
    ├── Data Portabilidade (Campo de número para data da portabilidade - Ex:17/12/2025).
    ├── Operadora Anterior (Campo de texto para operadora anterior - Ex:Vivo, Oi, Claro).
    ├── Data Ativação (Campo de número para data da ativação da linha telefonica - EX:14/12/2025).
    ├── Data vencimento (Campo de número para data de vencimento da linha telefonica - EX:14/12/2025).
    ├── Status da Linha (Campo seletor para status da linha - EX:Ativado, Portando, Cancelado, Pausado).
    ├── IP Proxy da Operadora (Campo de número para IP Proxy da operadora - Ex:192.xxx.xx.x).
    ├── Porta do Proxy (Campo de número para portas do Proxy da operadora - Ex:5060).
    ├── IP Tráfego do áudio (Campo de número para IP do trafego de audio da operadora - Ex:192.xxx.xx.x).
    └── Observações (Campo de texto para observações adicionais).
```

---

## EXEMPLO DA ESTRUTURA DO SISTEMA DE TAREFAS

**Exemplo das funções da aba Tarefas:**

```yaml
Nova tarefa
├── Cadastrar nova tarefa
│   ├── Tarefa para
│   |   ├── Selecione a empresa:
│   |   └── Cadastre uma nova empresa:
│   ├── Tarefa será executada por
│   |   ├── Selecione o nome do colaborador:
│   |   └── Cadastrar um novo colaborador:
│   ├── Agendar a tarefa para
│   |   ├── Selecione a data:
│   |   └── Selecione a hora:
│   ├── ID da tarefa automatico
│   ├── Descrição da tarefa
│   ├── Selecione o tipo de tarefa
│   ├── Selecione o nível de prioridade
│   ├── check-in manual
│   ├── enviar e-mail com tarefa criada
│   └── categoria
│
├── Status e Datas da tarefa
│   ├── status (Aberto, Em Andamento, Pausado, Resolvido, Fechado)
│   |   ├── Aberto:
│   |   ├── Em Andamento:
│   |   ├── Pausado:
│   |   ├── Resolvido:
│   |   └── Fechado:
│   ├── check-in Manual
│   |   ├── Colocar manualmente a data e hora de criação da tarefa:
│   |   ├── Colocar manualmente a data e hora de atualização da tarefa:
│   |   ├── Colocar manualmente a data e hora de resolução da tarefa:
│   |   └── Colocar manualmente a data e hora de fechamento da tarefa:
│
├── Tempo gasto em horas
│   ├── Calcular e gerar automaticamente o tempo total gasto da tarefa:
│   ├── Calcular e gerar automaticamente o tempo de pausa da tarefa:
│   └── Calcular e gerar automaticamente o tempo tecnico gasto da tarefa:
│
├── Mapa de Localização
│   ├── Mapa Leaflet:
│   ├── Marcar no mapa a localização da tarefa:
│   ├── Calcular a distância da minha localização até a localização da tarefa:
│   └── Digite o Valor da gasolina a ser calculado de acordo com a distância pecorrida pelo veiculo:
│
├── Assinatura digital
│   ├── Captura via mouse/touch:
│   ├── Exporta como Base64 (PNG):
│   ├── Armazena nome e CPF do assinante:
│   └── Exibe em relatórios/PDF:
│
└── Opções
    ├── Excluir tarefa:
    ├── Editar tarefa:
    └── Reagendar tarefa:
```

## Interface Newbase - Guia Visual

**Exemplo Fluxo dos processos:**

```yaml
┌─────────────────────────────────────────────────┐
│ Aba -  Servidor Telefonia      [Clica aqui → ▼] │─→ Exibe os formulrios divididos por cards
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
│    → Formulário carrega INLINE                  │
│    → Usuário permanece em seu cadastro          │
└─────────────────────────────────────────────────┘
```

**Campos expansíveis disponíveis:**

*Aba: Servidor Telefonia:*

```yaml
┌───────────────────────────────────────────────────┐
│ Informações do Servidor                           │
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

*Seção 3: Linha Telefonica:*

```yaml
┌───────────────────────────────────────────────────┐
│ Linha Telefonica                                  │
│ ┌───────────────────────────────────────────────┐ │
│ │ [Selecione para expandir os formularios   ▼]  │ │
│ └───────────────────────────────────────────────┘ │
└───────────────────────────────────────────────────┘
```

*Seção 4: Chatbot:*

```yaml
┌───────────────────────────────────────────────────┐
│ Chatbot                                           │
│ ┌───────────────────────────────────────────────┐ │
│ │ [Selecione para expandir os formularios   ▼]  │ │
│ └───────────────────────────────────────────────┘ │
└───────────────────────────────────────────────────┘
```

*Seções Expandíveis:*

```yaml
┌────────────────────────────────────────┐
│ [+ Adicionar Ramal]                    │
│ [Fomulario de Ramais Existentes]       │
├────────────────────────────────────────┤
│ [+ Adicionar Tronco]                   │
│ [Fomulario de Troncos Existentes]      │
├────────────────────────────────────────┤
│ [+ Adicionar dispositivos]             │
│ [Fomulario de dispositivos Existentes] │
├────────────────────────────────────────┤
│ [Configurar Rede]                      │
│ [Informações de Rede]                  │
└────────────────────────────────────────┘
```

### Funcionalidade de Senhas

```yaml
- Campo em modo "text" (sempre visível)

Exemplo: Senha Web: [senha123456]

```

## Fluxo de Submissão

```yaml
 ┌─────────────────────┐
 │ Usuário Clica em    │
 │ "Sevidor telefonia" │
 └────────┬────────────┘
          │
          ▼
┌──────────────────────┐
│ Formulários Carregam │
│                      │
└────────┬─────────────┘
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
