# SimplePHP MVC Framework

Minimal PHP MVC skeleton. No dependencies beyond Composer's autoloader.

## Requirements
- PHP 8.1+
- Composer
- Apache (with `mod_rewrite`) or Nginx

## Quick start

```bash
composer install
cp parameters.env.example parameters.env   # fill in your values
```

Point your web server document root to the project root (the folder containing `index.php`).

## Routing

URLs map to controllers via the `page` query parameter:

| URL | Controller class |
|-----|-----------------|
| `?page=Index` | `Controllers\Index` |
| `?page=Sec.Login` | `Controllers\Sec\Login` |
| `?page=Admin.Users` | `Controllers\Admin\Users` |

Dots (`.`) in the page name become namespace separators (`\`).

## Adding a controller

```php
// src/Controllers/About.php
namespace Controllers;

class About extends PublicController
{
    public function run(): void
    {
        \Views\Renderer::render('about', ['heading' => 'About Us']);
    }
}
```

Create `src/Views/templates/about.view.tpl` and you're done.

## Template syntax

| Tag | Meaning |
|-----|---------|
| `{{VAR}}` | Output variable |
| `{{~VAR}}` | Output from root (global) scope |
| `{{foreach LIST}} … {{endfor LIST}}` | Loop |
| `{{if VAR}} … {{endif VAR}}` | Conditional |
| `{{ifnot VAR}} … {{endifnot VAR}}` | Inverse conditional |
| `{{include path/to/partial}}` | Inline another template |
| `{{{page_content}}}` | Layout injection point |

## Private (auth-required) controllers

Extend `PrivateController` instead of `PublicController`. Unauthenticated
requests are automatically redirected to the login page.

```php
namespace Controllers\Dashboard;

class Home extends \Controllers\PrivateController
{
    public function run(): void
    {
        \Views\Renderer::render('dashboard/home', []);
    }
}
```

## Database

`Dao\Dao::getConn()` returns a shared PDO singleton configured from
`parameters.env`. Example DAO:

```php
namespace Dao;

class UserDao extends Dao
{
    public static function findByEmail(string $email): array|false
    {
        $stmt = self::getConn()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}
```

## File structure

```
index.php                   Entry point / router
composer.json
parameters.env              Environment config (never commit)
nav.config.json             Navigation menu entries
.htaccess                   Apache rewrite rules
public/
  css/style.css             Base stylesheet
src/
  Controllers/
    IController.php         Interface
    PublicController.php    Base for public pages
    PrivateController.php   Base for auth-required pages
    AuthExceptions.php      PrivateNoAuthException / PrivateNoLoggedException
    Index.php               Home controller
    Error.php               Error controller
    NoAuth.php              403 controller
    Sec/
      Login.php
      Logout.php
  Dao/
    Dao.php                 PDO singleton
  Utilities/
    Context.php             Global state bag
    DotEnv.php              .env file parser
    Nav.php                 Navigation builder
    Security.php            Session-based auth helpers
    Site.php                Bootstrap + redirect helpers
  Views/
    Renderer.php            Template engine
    templates/
      layout.view.tpl       Public layout
      privatelayout.view.tpl Private layout
      index.view.tpl
      error.view.tpl
      noauth.view.tpl
      security/
        login.view.tpl
```
