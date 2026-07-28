<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{SITE_TITLE}}</title>
  <link rel="stylesheet" href="{{BASE_DIR}}public/css/global.css" />
  <link rel="stylesheet" href="{{BASE_DIR}}public/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  {{foreach SiteLinks}}
    <link rel="stylesheet" href="{{~BASE_DIR}}{{this}}" />
  {{endfor SiteLinks}}
  {{foreach BeginScripts}}
    <script src="{{~BASE_DIR}}{{this}}"></script>
  {{endfor BeginScripts}}
</head>
<body>

  <header>
    <input type="checkbox" class="menu_toggle" id="menu_toggle" />
    <label for="menu_toggle" class="menu_toggle_icon" aria-label="Abrir menu" role="button">
      <span class="hmb dgn pt-1"></span>
      <span class="hmb hrz"></span>
      <span class="hmb dgn pt-2"></span>
    </label>
    <span class="site-title">{{SITE_TITLE}}</span>
    <nav role="navigation" aria-label="Menu de usuario">
      <ul>
        <li><a href="index.php?page=Index"><i class="fas fa-home"></i>&nbsp;Inicio</a></li>
        {{foreach NAVIGATION}}
          <li><a href="{{nav_url}}">{{nav_label}}</a></li>
        {{endfor NAVIGATION}}
        <li class="nav-divider nav-logout">
          <a href="index.php?page=Sec.Logout"><i class="fas fa-sign-out-alt"></i>&nbsp;Salir</a>
        </li>
      </ul>
    </nav>
    {{with login}}
    <div class="header-user">
      <i class="fas fa-user-circle"></i>
      <span>{{userName}}</span>
      <a href="index.php?page=Sec.Logout" title="Cerrar sesion"><i class="fas fa-sign-out-alt"></i></a>
    </div>
    {{endwith login}}
  </header>

  <main class="{{mainClass}}">
    {{{page_content}}}
  </main>

  <footer>
    <p>&copy; 2026 {{SITE_TITLE}}</p>
  </footer>

  {{foreach EndScripts}}
    <script src="{{~BASE_DIR}}{{this}}"></script>
  {{endfor EndScripts}}

  {{if flash_msg}}
<script>
  alert("{{flash_msg}}");
</script>
{{endif flash_msg}}

</body>
</html>
