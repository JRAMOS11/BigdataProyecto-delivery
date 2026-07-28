<style>
  .welcome-page {
    width: 100%;
    padding: 1rem 0 2rem;
  }

  .welcome-copy {
    margin-bottom: 1.5rem;
  }

  .eyebrow {
    margin: 0 0 0.35rem;
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .welcome-copy h1 {
    margin: 0 0 0.5rem;
    font-size: 2rem;
  }

  .welcome-copy p {
    margin: 0 0 1rem;
    color: #b8b8b8;
    line-height: 1.5;
  }

  .welcome-copy strong {
    color: white;
  }

  .welcome-menu-btn {
    display: inline-block;
    padding: 0.7rem 1.2rem;
    border-radius: 25px;
    background-color: #dc3545;
    color: white;
    font-weight: bold;
    text-decoration: none;
  }

  .welcome-menu-btn:hover {
    background-color: #bb2d3b;
    color: white;
  }

  .ofertas-seccion {
    margin-top: 2rem;
  }

  .ofertas-titulo {
    margin-bottom: 1rem;
  }

  .ofertas-titulo h2 {
    margin: 0;
    font-size: 1.8rem;
    color: white;
  }

  .ofertas-titulo p {
    margin-top: 0.3rem;
    color: #b8b8b8;
    font-size: 0.95rem;
  }

  .ofertas-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
  }

  .oferta-card {
    background-color: white;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.25);
    transition: transform 0.2s;
  }

  .oferta-card:hover {
    transform: translateY(-5px);
  }

  .oferta-imagen {
    width: 100%;
    height: 170px;
    object-fit: cover;
    display: block;
  }

  .oferta-contenido {
    padding: 1rem;
  }

  .oferta-etiqueta {
    display: inline-block;
    background-color: #dc3545;
    color: white;
    font-size: 0.72rem;
    font-weight: bold;
    padding: 0.3rem 0.7rem;
    border-radius: 20px;
    margin-bottom: 0.6rem;
  }

  .oferta-contenido h3 {
    margin: 0 0 0.5rem;
    color: #222;
    font-size: 1.2rem;
  }

  .oferta-contenido p {
    color: #666;
    font-size: 0.88rem;
    line-height: 1.4;
    min-height: 50px;
  }

  .oferta-precio {
    color: #dc3545;
    font-size: 1.05rem;
    font-weight: bold;
    margin-bottom: 0.8rem;
  }

  .oferta-boton {
    display: block;
    width: 100%;
    padding: 0.7rem;
    border-radius: 8px;
    background-color: #ff4d4d;
    color: white;
    text-decoration: none;
    text-align: center;
    font-weight: bold;
    box-sizing: border-box;
  }

  .oferta-boton:hover {
    background-color: #d93636;
    color: white;
  }

  @media screen and (max-width: 900px) {
    .ofertas-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media screen and (max-width: 600px) {
    .ofertas-grid {
      grid-template-columns: 1fr;
    }

    .welcome-copy h1 {
      font-size: 1.6rem;
    }
  }
</style>

<section class="welcome-page">

  {{if isLogged}}

  <div class="welcome-copy">

    <p class="eyebrow">
      Bienvenido
    </p>

    <h1>
      Hola, {{userName}}
    </h1>

    <p>
      Iniciaste sesión como
      <strong>{{userRol}}</strong>.
      Aprovecha las ofertas del día o entra al menú para armar tu carrito.
    </p>

    <a
      href="index.php?page=Tracking_Menu"
      class="welcome-menu-btn"
    >
      Ir al menú
    </a>

  </div>

  <section class="ofertas-seccion">

    <div class="ofertas-titulo">

      <h2>
        Ofertas del día
      </h2>

      <p>
        Promociones especiales disponibles por tiempo limitado.
      </p>

    </div>

    <div class="ofertas-grid">

      <article class="oferta-card">

        <img
          src="http://localhost/NWEB/SEMINARIO-TALLER-DE-SOFTWARE/public/img/platos/pizza.jpg"
          alt="Oferta de pizzas"
          class="oferta-imagen"
        >

        <div class="oferta-contenido">

          <span class="oferta-etiqueta">
            3x2
          </span>

          <h3>
            Compra 2 pizzas y recibe 1 gratis
          </h3>

          <p>
            Pide dos pizzas margarita y recibe una tercera sin costo adicional.
          </p>

          <div class="oferta-precio">
            Promoción especial del día
          </div>

          <a
            href="index.php?page=Tracking_Menu"
            class="oferta-boton"
          >
            Ver promoción
          </a>

        </div>

      </article>

      <article class="oferta-card">

        <img
          src="http://localhost/NWEB/SEMINARIO-TALLER-DE-SOFTWARE/public/img/platos/hamburguesa.jpg"
          alt="Combo de hamburguesa"
          class="oferta-imagen"
        >

        <div class="oferta-contenido">

          <span class="oferta-etiqueta">
            Combo
          </span>

          <h3>
            Hamburguesa con refresco
          </h3>

          <p>
            Disfruta una hamburguesa clásica acompañada de un refresco natural.
          </p>

          <div class="oferta-precio">
            Precio especial
          </div>

          <a
            href="index.php?page=Tracking_Menu"
            class="oferta-boton"
          >
            Ver promoción
          </a>

        </div>

      </article>

      <article class="oferta-card">

        <img
          src="http://localhost/NWEB/SEMINARIO-TALLER-DE-SOFTWARE/public/img/platos/alitas.jpg"
          alt="Oferta de alitas"
          class="oferta-imagen"
        >

        <div class="oferta-contenido">

          <span class="oferta-etiqueta">
            20% descuento
          </span>

          <h3>
            Alitas BBQ en promoción
          </h3>

          <p>
            Ordena unas alitas BBQ y recibe un descuento especial durante el día.
          </p>

          <div class="oferta-precio">
            Antes L. 160.00 — Ahora L. 128.00
          </div>

          <a
            href="index.php?page=Tracking_Menu"
            class="oferta-boton"
          >
            Ver promoción
          </a>

        </div>

      </article>

    </div>

  </section>

  {{endif isLogged}}

  {{ifnot isLogged}}

  <div class="welcome-copy">

    <p class="eyebrow">
      Delivery APP
    </p>

    <h1>
      Bienvenido a {{SITE_TITLE}}
    </h1>

    <p>
      Para ver el menú, aprovechar las ofertas, armar tu carrito y realizar un pedido debes iniciar sesión.
    </p>

    <a
      href="index.php?page=Sec.Login"
      class="welcome-menu-btn"
    >
      Iniciar sesión
    </a>

  </div>

  {{endifnot isLogged}}

</section>
