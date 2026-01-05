# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

## [2.0.0] - 2025-12-19

### Adicionado

- Suporte completo a GLPI 10.0.20+
- Sistema de gestão de dados de empresas (Pessoa Jurídica)
  - Validação de CNPJ com algoritmo oficial
  - Integração com API BrasilAPI para busca automática de CNPJ
  - Validação de email e telefone
  - Controle de status de contrato (ativo, inativo, cancelado)
- Gerenciamento de endereços
  - Integração com ViaCEP para busca automática de CEP
  - Suporte a geolocalização (latitude/longitude)
  - Múltiplos endereços por empresa
- Gestão de sistemas de comunicação
  - Suporte a IPBX, PABX, Chatbot, IPBX Cloud
  - Linhas telefônicas
  - Controle de status
- Sistema completo de tarefas
  - Ciclo de vida completo (aberto, em progresso, pausado, concluído)
  - Atribuição de usuários
  - Rastreamento de geolocalização (ponto inicial e final)
  - Cálculo automático de quilometragem usando fórmula de Haversine
  - Mapa interativo com rotas (Leaflet)
  - Captura e upload de assinatura digital
- Interface responsiva para dispositivos móveis
- Sistema completo de permissões por tipo de objeto
- Logging abrangente
- Relatórios e estatísticas
- Dashboard com visão geral
- Integração total com GLPI (CommonDBTM, Hooks, Permissões)

### Técnico

- PHP 8.1+ com strict types
- Padrão PSR-4 para autoloading
- Padrão PSR-12 para estilo de código
- Documentação completa com PHPDoc
- Abordagem security-first
- Extensões PHP requeridas: json, mbstring, mysqli, curl

## [1.0.0] - 2024-01-01

### Adicionado

- Versão inicial do plugin para GLPI 10.0.x
- Funcionalidades básicas de gestão de empresas
- Suporte a endereços simples
- Sistema de tarefas básico
