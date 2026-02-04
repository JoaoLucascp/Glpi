# Contributing to Newbase

Thank you for considering contributing to Newbase! This document provides guidelines for contributing to this GLPI plugin.

## 🤝 How to Contribute

### Reporting Bugs

Before creating bug reports, please check existing issues. When you create a bug report, include:

- **Clear title and description**
- **Steps to reproduce** the behavior
- **Expected behavior**
- **Screenshots** if applicable
- **Environment details**:
  - GLPI version
  - PHP version
  - Browser and version
  - Plugin version

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Clear title and description**
- **Detailed explanation** of the proposed feature
- **Use cases** for the feature
- **Mockups or examples** if applicable

### Pull Requests

1. **Fork** the repository
2. **Create a branch** for your feature (`git checkout -b feature/AmazingFeature`)
3. **Follow coding standards** (see below)
4. **Test your changes** thoroughly
5. **Commit your changes** (`git commit -m 'Add some AmazingFeature'`)
6. **Push to the branch** (`git push origin feature/AmazingFeature`)
7. **Open a Pull Request**

## 📝 Coding Standards

### PHP Code Style

This project follows **PSR-12** coding standards:

```bash
# Check coding standards
composer cs:check

# Fix coding standards automatically
composer cs:fix
```

### Code Quality

- **Always use type hints** for parameters and return types
- **Document all public methods** with PHPDoc blocks
- **Follow SOLID principles**
- **Write readable, maintainable code**

### Example of good code:

```php
<?php

namespace GlpiPlugin\Newbase;

use CommonDBTM;

/**
 * MyClass - Brief description
 * 
 * Detailed description of what this class does.
 *
 * @package GlpiPlugin\Newbase
 */
class MyClass extends CommonDBTM
{
    /**
     * Get something by ID
     *
     * @param int $id Item ID
     * @return array|false Item data or false on failure
     */
    public function getById(int $id): array|false
    {
        global $DB;
        
        $result = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => ['id' => $id]
        ]);
        
        if ($result->count() > 0) {
            return $result->current();
        }
        
        return false;
    }
}
```

## 🔒 Security

### CSRF Protection

Always use CSRF protection in forms and AJAX requests:

```php
// In forms
Session::checkCSRF($_POST);

// In AJAX endpoints
Session::checkCSRF($_POST);
```

### SQL Injection Prevention

Always use GLPI's query builder:

```php
// ✅ GOOD
$result = $DB->request([
    'FROM'  => 'table',
    'WHERE' => ['id' => $id]
]);

// ❌ BAD
$query = "SELECT * FROM table WHERE id = $id";
```

### XSS Prevention

Always escape output:

```php
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
```

## 🧪 Testing

Before submitting a pull request:

1. **Test manually** in a GLPI instance
2. **Run PHP syntax check**: `php -l your-file.php`
3. **Check coding standards**: `composer cs:check`
4. **Run static analysis**: `composer analyse`

## 📚 Documentation

- Update README.md if needed
- Add inline comments for complex logic
- Update CHANGELOG.md following [Keep a Changelog](https://keepachangelog.com/)

## 🌍 Translations

Translations are managed in the `/locales` directory:

1. Extract strings: `vendor/bin/extract-locales`
2. Update `.pot` file
3. Translate `.po` files
4. Compile `.mo` files

## ✅ Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation changes
- `style:` Code style changes (formatting, missing semi colons, etc.)
- `refactor:` Code refactoring
- `test:` Adding or updating tests
- `chore:` Maintenance tasks

### Examples:

```
feat: add GPS tracking to tasks
fix: correct CSRF validation in task form
docs: update installation instructions
style: format code according to PSR-12
refactor: improve address validation logic
test: add unit tests for CompanyData class
chore: update dependencies
```

## 📄 License

By contributing to Newbase, you agree that your contributions will be licensed under the GPL v2+ License.

## 🙋 Questions?

Feel free to open an issue for questions or join our community channels.

---

**Thank you for contributing to Newbase! 🎉**
