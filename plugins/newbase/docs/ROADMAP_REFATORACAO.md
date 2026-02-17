# 🚀 ROADMAP DE REFATORAÇÃO - Plugin Newbase v2.1.0 → v2.2.0

**Data:** 17 de Fevereiro de 2026
**Status:** EM PROGRESSO
**Progresso:** 2/5 Fases Completas (40%)

---

## 📊 VISÃO GERAL

```
[████████░░] 40% Completo

FASE 1: ✅ COMPLETA - Código Comum (AjaxHandler)
FASE 2: ✅ COMPLETA - Validações (Common.php)
FASE 3: ⏳ PENDENTE - Type Hints
FASE 4: ⏳ PENDENTE - Guard Clauses + PHPDoc
FASE 5: ⏳ PENDENTE - Refatoração AJAX
```

---

## ✅ FASE 1: CRIAÇÃO DE AjaxHandler.php [COMPLETA]

### Arquivo Criado
- **Local:** `d:\laragon\www\glpi\plugins\newbase\src\AjaxHandler.php` (253 linhas)

### Métodos Implementados
```php
✅ sendResponse()          → Resposta JSON padronizada
✅ checkCSRFToken()        → Validação CSRF dupla (header + fallback)
✅ checkPermissions()      → Verificação de permissões
✅ validateRequest()       → Autenticação + CSRF
✅ fetchCurl()             → Requisições HTTP centralizadas
✅ validateInput()         → Validação de entrada com regras
✅ setSecurityHeaders()    → Headers de segurança AJAX
```

### Impacto
- **Redução:** ~90 linhas de código duplicado eliminadas
- **Segurança:** Centralização de CSRF validation
- **Manutenibilidade:** 7 arquivos ajax/ podem usar AjaxHandler

---

## ✅ FASE 2: EXPANSÃO DE Common.php [COMPLETA]

### Métodos Adicionados
```php
✅ validateCEP()             → Valida CEP (8 dígitos)
✅ validateEmail()           → Valida email
✅ validatePhone()           → Valida telefone brasileiro
✅ validateCoordinates()     → Valida GPS coordinates
✅ fetchAddressByCEP()       → Consulta ViaCEP API
✅ fetchCoordinatesByCEP()   → Consulta Nominatim (OpenStreetMap)
```

### Impacto
- **Linhas Adicionadas:** ~200 linhas de métodos validados
- **Duplicação Eliminada:** validateCEP (2 ocorrências), validateCNPJ (consolidado)
- **Funcionalidade:** Geolocalização + validações centralizadas

---

## ⏳ FASE 3: TYPE HINTS (PRÓXIMO PASSO)

### Arquivos a Modificar - Prioridade Alta

#### `src/Task.php` (CRÍTICO)
```php
// ANTES
public function prepareInputForAdd($input)
public function prepareInputForUpdate($input)
public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
public static function dropdown($options = [])

// DEPOIS
public function prepareInputForAdd(array $input): array|bool
public function prepareInputForUpdate(array $input): array|bool
public function getTabNameForItem(CommonGLPI $item, int $withtemplate = 0): ?string
public static function dropdown(array $options = []): ?int
```

**Linhas a Modificar:** 459, 506, 609, 741

---

#### `src/System.php` (CRÍTICO)
```php
public function prepareInputForAdd(array $input): array|bool     // Linha 358
public function prepareInputForUpdate(array $input): array|bool  // Linha 394
public static function getSpecificValueToDisplay($field, $values, array $options = []): string
public static function dropdown(array $options = []): ?int
```

**Linhas a Modificar:** 278, 312, Diversos

---

#### `src/Address.php` (ALTO)
```php
public function prepareInputForAdd(array $input): array|bool
public function prepareInputForUpdate(array $input): array|bool
```

**Linhas Estimadas:** 335, 387

---

#### `src/CompanyData.php` (ALTO)
```php
public function prepareInputForAdd(array $input): array|bool
public function prepareInputForUpdate(array $input): array|bool
public static function dropdown(array $options = []): ?int
```

---

### Arquivos a Modificar - Prioridade Média

#### `src/TaskSignature.php`
```php
public static function saveSignature(int $task_id, string $signature_data, string $signer_name = ''): int|false
```

#### `src/Config.php`
```php
private static function validateBoolean($value): int
private static function validateInteger($value): int
```

#### `src/Menu.php`
```php
public static function canView(): bool  // Atual: TypeError em cast
```

---

## ⏳ FASE 4: GUARD CLAUSES + PHPDoc

### Padrão a Aplicar
```php
/**
 * Prepare input for create operation
 *
 * @param array $input Input data from form
 * @return array|bool Modified input on success, false on validation failure
 * @throws InvalidArgumentException If input format is invalid
 */
public function prepareInputForAdd(array $input): array|bool
{
    // GUARD CLAUSES PRIMEIRO
    if (empty($input)) {
        return false;
    }

    if (!is_array($input)) {
        return false;
    }

    // Validações específicas
    if (isset($input['status'])) {
        $validStatuses = array_keys(self::getStatuses());
        if (!in_array($input['status'], $validStatuses, true)) {
            return false;
        }
    }

    // Lógica do método
    return parent::prepareInputForAdd($input);
}
```

### Benefício
- Código mais legível
- Facilita refatoração futura
- Reduz indentação

---

## ⏳ FASE 5: REFATORAÇÃO ENDPOINTS AJAX

### Arquivos a Refatorar (7 total)

| Arquivo                     | Linhas Atuais | Linhas Alvo | Mudança Principal                |
| --------------------------- | ------------- | ----------- | -------------------------------- |
| `ajax/calculateMileage.php` | 450+          | 200         | Usar AjaxHandler::fetchCurl()    |
| `ajax/cnpj_proxy.php`       | 450+          | 200         | Substituir sendResponse()        |
| `ajax/mapData.php`          | 400+          | 180         | Usar setSecurityHeaders()        |
| `ajax/searchAddress.php`    | 408           | 190         | Usar validateInput()             |
| `ajax/searchCompany.php`    | 380+          | 180         | Usar AjaxHandler::sendResponse() |
| `ajax/signatureUpload.php`  | 420+          | 200         | Usar AjaxHandler completo        |
| `ajax/taskActions.php`      | 410+          | 190         | Usar AjaxHandler para transições |

**Total Redução Estimada:** ~1,500 linhas → ~1,200 linhas (20% redução)

---

## 📋 CHECKLIST DE IMPLEMENTAÇÃO

### FASE 3: Type Hints
- [ ] Task.php - linhas 459, 506, 609, 741
- [ ] System.php - linhas 278, 312, Diversos
- [ ] Address.php - linhas 335, 387
- [ ] CompanyData.php - 3 métodos
- [ ] TaskSignature.php - saveSignature()
- [ ] Config.php - validateBoolean(), validateInteger()
- [ ] Menu.php - canView()

### FASE 4: Guard Clauses + PHPDoc
- [ ] Adicionar guard clauses em todos prepareInputForAdd/Update
- [ ] Adicionar PHPDoc @param/@return em 20+ métodos
- [ ] Validar PSR-12 com PHP CodeSniffer

### FASE 5: Refatoração AJAX
- [ ] calculateMileage.php - usar AjaxHandler::fetchCurl()
- [ ] cnpj_proxy.php - usar AjaxHandler::sendResponse()
- [ ] mapData.php - usar AjaxHandler::setSecurityHeaders()
- [ ] searchAddress.php - usar AjaxHandler::validateInput()
- [ ] searchCompany.php - usar AjaxHandler
- [ ] signatureUpload.php - usar AjaxHandler completo
- [ ] taskActions.php - usar AjaxHandler para transições

### Finalização
- [ ] Testar PSR-12 conformance
- [ ] Executar testes unitários
- [ ] Verificar CSRF em todos endpoints
- [ ] Documentar v2.2.0 no arquivo .md

---

## 🎯 PRÓXIMOSPASSOS RECOMENDADOS

### Próximo Passo Imediato: FASE 3 (Type Hints)

**Por quê?**
- Reduz erros em tempo de desenvolvimento
- Prepara para refatoração AJAX
- Conformidade com PSR-12

**Como começar:**
1. Abrir `src/Task.php`
2. Modificar linhas 459, 506, 609, 741 com type hints
3. Executar testes
4. Repetir para System.php e Address.php

**Tempo Estimado:** 30-45 minutos

---

## 📞 PRÓXIMAS AÇÕES

Gostaria que você:

1. **Confirme** se quer continuar com FASE 3 (Type Hints)
2. **Escolha** se prefere:
   - Option A: Fazer type hints em order (Task → System → Address)
   - Option B: Fazer refatoração paralela (AJAX + Type hints)
   - Option C: Primeiro terminar guard clauses antes de AJAX

3. **Indique** se há prioridades diferentes na sua visão

---

**Arquivo de Acompanhamento:** ROADMAP_REFATORACAO.md
**Última Atualização:** 17 de Fevereiro de 2026
**Próxima Revisão:** Após FASE 3
