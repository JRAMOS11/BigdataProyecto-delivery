<style>
    .menu-cliente {
        width: 100%;
        padding: 1rem 0 2rem;
    }

    .menu-encabezado {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .menu-titulo h1 {
        margin: 0;
        font-size: 2rem;
    }

    .menu-titulo p {
        margin: 0.35rem 0 0;
        color: #777;
        font-size: 0.95rem;
    }

    .menu-opciones {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
    }

    .menu-enlace {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.65rem 1rem;
        border-radius: 25px;
        font-weight: bold;
        text-decoration: none;
        transition: 0.2s;
    }

    .menu-enlace:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }

    .enlace-pedidos {
        background-color: #198754;
        color: white;
    }

    .enlace-carrito {
        background-color: #ffc107;
        color: #222;
    }

    .productos-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1rem;
        width: 100%;
    }

    .producto-card {
        min-width: 0;
        background-color: white;
        border: 1px solid #dddddd;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        transition: 0.2s;
    }

    .producto-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .producto-imagen-contenedor {
        position: relative;
        width: 100%;
        height: 130px;
        background-color: #eeeeee;
        overflow: hidden;
    }

    .producto-imagen {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    .producto-stock {
        position: absolute;
        top: 8px;
        right: 8px;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: bold;
    }

    .stock-disponible {
        background-color: rgba(25, 135, 84, 0.95);
        color: white;
    }

    .stock-agotado {
        background-color: rgba(220, 53, 69, 0.95);
        color: white;
    }

    .producto-contenido {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .producto-nombre {
        margin: 0 0 6px;
        font-size: 17px;
        font-weight: bold;
        color: #222;
    }

    .producto-descripcion {
        color: #666;
        font-size: 12px;
        line-height: 1.4;
        margin: 0 0 8px;
        min-height: 34px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .producto-precio {
        color: #dc3545;
        font-size: 17px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .producto-form {
        margin-top: auto;
    }

    .cantidad-grupo {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 8px;
    }

    .cantidad-grupo label {
        color: #555;
        font-size: 12px;
        font-weight: bold;
    }

    .cantidad-input {
        width: 65px;
        padding: 6px;
        color: #222;
        background-color: white;
        border: 1px solid #cccccc;
        border-radius: 6px;
        text-align: center;
    }

    .btn-agregar,
    .btn-agotado {
        width: 100%;
        padding: 9px 5px;
        border: none;
        border-radius: 7px;
        font-size: 12px;
        font-weight: bold;
    }

    .btn-agregar {
        background-color: #dc3545;
        color: white;
        cursor: pointer;
    }

    .btn-agregar:hover {
        background-color: #bb2d3b;
    }

    .btn-agotado {
        background-color: #777;
        color: white;
        cursor: not-allowed;
    }

    .mensaje-vacio {
        grid-column: 1 / -1;
        text-align: center;
        background-color: white;
        border: 1px solid #dddddd;
        border-radius: 12px;
        padding: 2rem;
        color: #666;
    }

    @media screen and (max-width: 1200px) {
        .productos-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media screen and (max-width: 900px) {
        .productos-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media screen and (max-width: 650px) {
        .productos-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .producto-imagen-contenedor {
            height: 120px;
        }

        .menu-titulo h1 {
            font-size: 1.6rem;
        }
    }

    @media screen and (max-width: 420px) {
        .productos-grid {
            grid-template-columns: 1fr;
        }

        .producto-imagen-contenedor {
            height: 180px;
        }
    }
</style>

<section class="menu-cliente">

    <div class="menu-encabezado">

        <div class="menu-titulo">

            <h1>Menú del Restaurante</h1>

            <p>
                Selecciona tus comidas favoritas y agrégalas al carrito.
            </p>

        </div>

        <div class="menu-opciones">

            <a
                href="index.php?page=Tracking_MisPedidos"
                class="menu-enlace enlace-pedidos"
            >
                Mis pedidos
            </a>

            <a
                href="index.php?page=Tracking_Carrito"
                class="menu-enlace enlace-carrito"
            >
                Carrito ({{cartCount}})
            </a>

        </div>

    </div>

    <div class="productos-grid">

        {{foreach platos}}

        <article class="producto-card">

            <div class="producto-imagen-contenedor">

                <img
                src="http://localhost/NWEB/delivery_app/public/{{imagen}}"
                alt="{{nombre}}"
                class="producto-imagen"
                >

                {{if platoDisponible}}

                <span class="producto-stock stock-disponible">
                    {{stock}} disponibles
                </span>

                {{endif platoDisponible}}

                {{ifnot platoDisponible}}

                <span class="producto-stock stock-agotado">
                    Agotado
                </span>

                {{endifnot platoDisponible}}

            </div>

            <div class="producto-contenido">

                <h2 class="producto-nombre">
                    {{nombre}}
                </h2>

                <p class="producto-descripcion">
                    {{descripcion}}
                </p>

                <div class="producto-precio">
                    L. {{precio}}
                </div>

                {{if platoDisponible}}

                <form
                    action="index.php?page=Tracking_Carrito"
                    method="POST"
                    class="producto-form"
                >

                    <input
                        type="hidden"
                        name="accion"
                        value="agregar"
                    >

                    <input
                        type="hidden"
                        name="platoId"
                        value="{{id}}"
                    >

                    <div class="cantidad-grupo">

                        <label for="cantidad-{{id}}">
                            Cantidad:
                        </label>

                        <input
                            id="cantidad-{{id}}"
                            class="cantidad-input"
                            type="number"
                            name="cantidad"
                            min="1"
                            max="{{stock}}"
                            value="1"
                            required
                        >

                    </div>

                    <button
                        type="submit"
                        class="btn-agregar"
                    >
                        Agregar al carrito
                    </button>

                </form>

                {{endif platoDisponible}}

                {{ifnot platoDisponible}}

                <button
                    type="button"
                    class="btn-agotado"
                    disabled
                >
                    Producto agotado
                </button>

                {{endifnot platoDisponible}}

            </div>

        </article>

        {{endfor platos}}

    </div>

</section>