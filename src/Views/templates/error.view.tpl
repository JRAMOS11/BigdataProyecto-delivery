<section class="error-page">
  <h1>{{CLIENT_ERROR_CODE}}</h1>
  <p>{{CLIENT_ERROR_MSG}}</p>
  <a href="index.php">← Go home</a>

  {{if DEVELOPMENT}}
  <hr>
  <pre>{{ERROR_MSG}}</pre>
  {{endif DEVELOPMENT}}
</section>
