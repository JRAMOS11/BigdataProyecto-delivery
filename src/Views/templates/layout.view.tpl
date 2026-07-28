<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{SITE_TITLE}}</title>
  <link rel="stylesheet" href="{{BASE_DIR}}public/css/global.css">
  <link rel="stylesheet" href="{{BASE_DIR}}public/css/style.css">
</head>
<body>
  <header>
    <input type="checkbox" class="menu_toggle" id="menu_toggle" />
    <label for="menu_toggle" class="menu_toggle_icon">
      <div class="hmb dgn pt-1"></div>
      <div class="hmb hrz"></div>
      <div class="hmb dgn pt-2"></div>
    </label>
    <div class="site-title">{{SITE_TITLE}}</div>
    <nav id="menu">
      <ul>
        {{foreach PUBLIC_NAVIGATION}}
          <li><a href="{{nav_url}}">{{nav_label}}</a></li>
        {{endfor PUBLIC_NAVIGATION}}
      </ul>
    </nav>
    <a href="index.php?page=Sec.Login" class="btn-login-header">Iniciar Sesion</a>
  </header>

  <main>
    {{{page_content}}}
  </main>

  <footer>
    <p>&copy; {{CURRENT_YEAR}} {{SITE_TITLE}}</p>
  </footer>

{{if flash_msg}}
<script>
  alert("{{flash_msg}}");
</script>
{{endif flash_msg}}
</body>
</html>
