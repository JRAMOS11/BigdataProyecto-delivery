<section class="pedidos-page">

    <h1>Mis Pedidos</h1>

    <form class="pedido-filter" method="GET" action="index.php">
        <input type="hidden" name="page" value="Tracking_MisPedidos" />
        <label for="estado">Filtrar por estado</label>
        <select id="estado" name="estado">
            {{foreach estadoOptions}}
            <option value="{{value}}" {{selected}}>{{label}}</option>
            {{endfor estadoOptions}}
        </select>
        <button type="submit">Aplicar</button>
    </form>

    {{ifnot hasPedidos}}
    <div class="empty-state">
        <h2>No hay pedidos para mostrar</h2>
        <p>No encontramos pedidos con el filtro "{{estadoFiltroDsc}}". Puedes volver al menu y realizar un pedido.</p>
    </div>
    {{endifnot hasPedidos}}

    {{if hasPedidos}}
    <table class="caps-products-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Plato</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Seguimiento</th>
                <th>Fecha</th>
                <th>Accion</th>
            </tr>
        </thead>

        <tbody>

            {{foreach pedidos}}

            <tr>

                <td>{{id}}</td>

                <td>{{nombre}}</td>

                <td>{{cantidad}}</td>

                <td>L. {{precio}}</td>

                <td>L. {{total}}</td>

                <td class="{{estadoClass}}">
                    {{estadoDsc}}
                </td>

                <td>
                    <!-- Seguimiento visual del avance del pedido para el cliente. -->
                    <div class="tracking-line">
                        <span class="{{pasoPendiente}}">Pendiente</span>
                        <span class="{{pasoProceso}}">En proceso</span>
                        <span class="{{pasoListo}}">Listo</span>
                        <span class="{{pasoEntregado}}">Entregado</span>
                    </div>
                </td>

                <td>{{creado_en}}</td>

                <td>

                    {{if puedeCancelar}}

                    <form action="index.php?page=Tracking_CancelarPedido" method="POST">

                        <input type="hidden" name="pedidoId" value="{{id}}" />
                        <button type="submit">Cancelar</button>

                    </form>

                    {{endif puedeCancelar}}

                </td>

            </tr>

            {{endfor pedidos}}

        </tbody>

    </table>
    {{endif hasPedidos}}

    <div class="row my-4 flex-end">

        <button type="button" id="btnRegresar" class="caps-secondary-btn">Volver al Menu</button>

    </div>

</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("btnRegresar")
        .addEventListener("click", () => {
            window.location.assign(
                "index.php?page=Tracking_Menu"
            );
        });
});
</script>
