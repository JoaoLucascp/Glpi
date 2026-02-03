# Newbase Plugin - Checklist de Implementação

## Status: 2.1.0 - Refatoração Completa

Data: 3 de Fevereiro de 2026

---

## ✅ FASE 1: ESTRUTURA BASE (CONCLUÍDA)

### Setup e Configuração
- [x] `setup.php` - Plugin versioning e prerequisites
- [x] `hook.php` - Instalação/desinstalação com migrations
- [x] `composer.json` - PSR-4 autoloading
- [x] `VERSION` - Controle de versão
- [x] `README.md` - Documentação principal

### Classes Base
- [x] `src/Common.php` - Classe abstrata base com métodos utilitários
- [x] `src/Menu.php` - Menu do plugin
- [x] `src/Config.php` - Configurações

### Banco de Dados
- [x] Schema com 6 tabelas principais
- [x] Foreign keys com CASCADE
- [x] Índices otimizados
- [x] Charset utf8mb4

---

## ✅ FASE 2: CLASSES MODELO (EM PROGRESSO)

### Endereços
- [x] `src/Address.php` - Classe base (estrutura)
- [ ] `src/Address.php` - Type hints completos
- [ ] `src/Address.php` - Documentação completa
- [ ] `src/AddressHandler.php` - Integração com ViaCEP

### Empresas
- [x] `src/CompanyData.php` - Classe base (estrutura)
- [ ] `src/CompanyData.php` - Type hints completos
- [ ] `src/CompanyData.php` - Validações de CNPJ
- [ ] `src/CompanyData.php` - Integração com APIs

### Sistemas Telefônicos
- [x] `src/System.php` - Classe base (estrutura)
- [ ] `src/System.php` - Type hints completos
- [ ] `src/System.php` - Gerenciamento de tipos

### Tarefas
- [x] `src/Task.php` - Classe base (estrutura)
- [ ] `src/Task.php` - Type hints completos
- [ ] `src/Task.php` - Cálculo de quilometragem

### Assinaturas
- [x] `src/TaskSignature.php` - Classe base (estrutura)
- [ ] `src/TaskSignature.php` - Type hints completos
- [ ] `src/TaskSignature.php` - Validação de assinatura

---

## ✅ FASE 3: CONTROLLERS (EM PROGRESSO)

### Dashboard
- [x] `front/index.php` - Estrutura básica
- [ ] `front/index.php` - Estatísticas completas
- [ ] `front/index.php` - Gráficos com ChartJS
- [ ] `front/index.php` - Relatórios rápidos

### Configuração
- [x] `front/config.php` - Página de configuração
- [ ] `front/config.php` - Formulário de opções
- [ ] `front/config.php` - Save/load de settings

### Endereços
- [ ] `front/address.php` - Lista com filtros
- [ ] `front/address.form.php` - Formulário com mapa
- [ ] `front/address.form.php` - Integração ViaCEP

### Empresas
- [ ] `front/companydata.php` - Lista com search
- [ ] `front/companydata.form.php` - Formulário completo
- [ ] `front/companydata.form.php` - Integração CNPJ

### Sistemas
- [ ] `front/system.php` - Lista de sistemas
- [ ] `front/system.form.php` - Formulário
- [ ] `front/system.form.php` - Configuração por tipo

### Tarefas
- [ ] `front/task.php` - Lista com status
- [ ] `front/task.form.php` - Formulário completo
- [ ] `front/task.form.php` - GPS e assinatura

### Relatórios
- [ ] `front/report.php` - Relatório de tarefas
- [ ] `front/report.php` - Relatório de sistemas
- [ ] `front/report.php` - Exportar para PDF/Excel

---

## ✅ FASE 4: AJAX HANDLERS (EM PROGRESSO)

### Busca de CNPJ
- [x] `ajax/cnpj_proxy.php` - Refatorado com segurança
- [ ] Testes com CNPJs válidos/inválidos
- [ ] Logging de todas as chamadas

### Busca de Endereço
- [ ] `ajax/searchAddress.php` - ViaCEP integration
- [ ] Validação de CEP
- [ ] Retorno de endereço completo

### Busca de Empresa
- [ ] `ajax/searchCompany.php` - Busca em banco local
- [ ] Filtro por CNPJ, nome, email
- [ ] Paginação

### Ações de Tarefa
- [ ] `ajax/taskActions.php` - Mudança de status
- [ ] Captura de GPS inicial/final
- [ ] Salvar assinatura

### Dados do Mapa
- [ ] `ajax/mapData.php` - Retornar pontos de tarefa
- [ ] Clustering de pontos
- [ ] Rotas entre pontos

### Cálculo de Quilometragem
- [ ] `ajax/calculateMileage.php` - Haversine formula
- [ ] Cache de resultados
- [ ] Validação de pontos válidos

### Upload de Assinatura
- [ ] `ajax/signatureUpload.php` - Receber canvas
- [ ] Converter para imagem
- [ ] Salvar com segurança

---

## ✅ FASE 5: ASSETS (PARCIAL)

### CSS
- [x] `css/newbase.css` - Estilos principais (estrutura)
- [ ] Responsivo para mobile
- [ ] Tema escuro (opcional)
- [ ] Animações

### JavaScript
- [x] `js/newbase.js` - Core (estrutura)
- [x] `js/forms.js` - Validação (estrutura)
- [ ] `js/map.js` - Leaflet integration
- [ ] `js/signature.js` - Canvas signature
- [ ] `js/mileage.js` - Cálculo quilometragem
- [ ] `js/mobile.js` - Features mobile

### Bibliotecas
- [x] `js/jquery.mask.min.js` - Máscara de input (instalado)
- [ ] Leaflet.js - Mapas interativos
- [ ] Chart.js - Gráficos
- [ ] SignaturePad.js - Assinatura digital

---

## ✅ FASE 6: INTERNACIONALIZAÇÃO (ESTRUTURA)

### Português Brasileiro
- [x] `locales/pt_BR.po` - Arquivo de tradução (base)
- [x] `locales/pt_BR.mo` - Arquivo compilado (base)
- [ ] Revisão de todas as strings
- [ ] Adicionar formatos de data/hora

### Futuras Localidades
- [ ] `locales/en_US.po` - Inglês (futuro)
- [ ] `locales/es_ES.po` - Espanhol (futuro)
- [ ] `locales/fr_FR.po` - Francês (futuro)

---

## ✅ FASE 7: DOCUMENTAÇÃO (PARCIAL)

### Técnica
- [x] `DEVELOPMENT_GUIDE.md` - Guia de desenvolvimento
- [x] `REFACTORING_REPORT.md` - Relatório de correções
- [ ] `docs/API.md` - Documentação de API
- [ ] `docs/DATABASE.md` - Documentação de BD
- [ ] `docs/INSTALLATION.md` - Guia de instalação

### Usuário
- [ ] `docs/USER_MANUAL.md` - Manual do usuário
- [ ] `docs/FAQ.md` - Perguntas frequentes
- [ ] `docs/TROUBLESHOOTING.md` - Resolução de problemas

---

## ✅ FASE 8: TESTES (NÃO INICIADO)

### Unitários
- [ ] Testes das classes modelo
- [ ] Testes de validação (CNPJ, CEP, etc)
- [ ] Testes de utilitários

### Integração
- [ ] Testes de controllers
- [ ] Testes de AJAX handlers
- [ ] Testes de banco de dados

### Segurança
- [ ] Testes CSRF
- [ ] Testes SQL injection
- [ ] Testes XSS
- [ ] Testes de permissões

### Performance
- [ ] Benchmark de queries
- [ ] Teste de carga
- [ ] Análise de memória

---

## ✅ FASE 9: PUBLICAÇÃO (NÃO INICIADO)

### Preparação
- [ ] Versão final 2.1.0
- [ ] Teste em GLPI clean
- [ ] Teste com dados reais
- [ ] Revisão de segurança final

### Marketplace GLPI
- [ ] Submeter para aprovação
- [ ] Aguardar review
- [ ] Publicar no marketplace

### GitHub
- [ ] Push da versão 2.1.0
- [ ] Criar release notes
- [ ] Adicionar badges

---

## ✅ FASE 10: MANUTENÇÃO (CONTÍNUO)

### Rotina
- [ ] Monitorar issues
- [ ] Responder pull requests
- [ ] Atualizar para GLPI 11.0+ (quando lançar)
- [ ] Manutenção de dependências

### Melhorias Futuras
- [ ] Integração com Twilio (SMS)
- [ ] Integração com Google Maps (API)
- [ ] Dashboard avançado com BI
- [ ] Mobile app nativa

---

## 📊 PROGRESSO GERAL

```
Fase 1 (Estrutura Base):       ████████████████████ 100%
Fase 2 (Classes Modelo):       ██████░░░░░░░░░░░░░░  30%
Fase 3 (Controllers):          ██░░░░░░░░░░░░░░░░░░   5%
Fase 4 (AJAX Handlers):        ██░░░░░░░░░░░░░░░░░░   5%
Fase 5 (Assets):               ████░░░░░░░░░░░░░░░░  15%
Fase 6 (i18n):                 ██████░░░░░░░░░░░░░░  20%
Fase 7 (Documentação):         █████░░░░░░░░░░░░░░░  15%
Fase 8 (Testes):               ░░░░░░░░░░░░░░░░░░░░   0%
Fase 9 (Publicação):           ░░░░░░░░░░░░░░░░░░░░   0%
Fase 10 (Manutenção):          ░░░░░░░░░░░░░░░░░░░░   0%

TOTAL:                         ██████░░░░░░░░░░░░░░  18%
```

---

## 🎯 METAS POR SPRINT

### Sprint 1 (Próximas 2 semanas)
- [ ] Completar type hints em todas as classes modelo
- [ ] Refatorar todos os controllers
- [ ] Implementar validações em formulários

### Sprint 2 (Próximas 2 semanas)
- [ ] Implementar todos os AJAX handlers
- [ ] Adicionar testes unitários básicos
- [ ] Melhorar UI/UX

### Sprint 3 (Próximas 2 semanas)
- [ ] Testes de segurança completos
- [ ] Documentação técnica completa
- [ ] Versão RC (Release Candidate)

### Sprint 4 (Próximas 2 semanas)
- [ ] Testes finais
- [ ] Publicação v2.1.0 estável
- [ ] Suporte ao usuário

---

## 🔗 DEPENDÊNCIAS EXTERNAS

### APIs Integradas
- [x] Brasil API - CNPJ (https://brasilapi.com.br)
- [x] ReceitaWS - CNPJ alternativo (https://receitaws.com.br)
- [ ] ViaCEP - Busca de endereço (https://viacep.com.br)
- [ ] Google Maps - Mapas (opcional)
- [ ] Twilio - SMS (futuro)

### Bibliotecas JavaScript
- [x] jQuery (incluído no GLPI)
- [x] jQuery Mask (incluído)
- [ ] Leaflet.js - Mapas
- [ ] Chart.js - Gráficos
- [ ] SignaturePad.js - Assinatura

### Extensões PHP Necessárias
- [x] curl - Requisições HTTP
- [x] json - Encoding/decoding JSON
- [x] gd - Processamento de imagens
- [x] mysqli - Banco de dados
- [x] mbstring - Strings multibyte

---

## 📞 CONTATOS E RECURSOS

### Equipe
- **Desenvolvedor**: João Lucas
- **Email**: joao.lucas@newtel.com.br
- **GitHub**: https://github.com/JoaoLucascp/Glpi

### Fóruns e Comunidades
- [GLPI Forum](https://forum.glpi-project.org/)
- [GLPI GitHub](https://github.com/glpi-project/glpi)
- [Telegram BR](https://t.me/glpibr)

### Referências
- [GLPI Docs](https://glpi-developer-documentation.readthedocs.io/)
- [PHP PSR-12](https://www.php-fig.org/psr/psr-12/)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)

---

## 📝 NOTAS IMPORTANTES

1. **Compatibilidade**: Testar com GLPI 10.0.20 mínimo
2. **PHP 8.3+**: Usar strict_types=1 em todos os arquivos
3. **Segurança**: Nunca confiar em input do usuário
4. **Performance**: Otimizar queries, adicionar índices
5. **Backup**: Sempre fazer backup antes de instalar
6. **Logs**: Revisar logs regularmente em `/glpi/files/_log/`

---

**Última Atualização**: 3 de Fevereiro de 2026
**Versão**: 2.1.0
**Status**: Em Desenvolvimento Ativo

---

## 🎉 PRÓXIMAS AÇÕES

1. ✅ **FEITO**: Refatoração completa da estrutura base
2. ✅ **FEITO**: Segurança aprimorada em AJAX handlers
3. 📋 **TODO**: Completar type hints nas classes modelo
4. 📋 **TODO**: Implementar testes unitários
5. 📋 **TODO**: Preparar para publicação v2.1.0

---
