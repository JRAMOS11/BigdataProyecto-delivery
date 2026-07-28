<section class="cart-page">
    <div class="cart-header">
        <div>
            <h1>Carrito de compras</h1>
            <p>Revisa tus platillos antes de confirmar el pedido.</p>
        </div>
        <a href="index.php?page=Tracking_Menu" class="caps-secondary-link">Volver al menu</a>
    </div>

    {{ifnot hasItems}}
    <div class="empty-state">
        <h2>Tu carrito esta vacio</h2>
        <p>Agrega platillos desde el menu para confirmar todo en un solo pedido.</p>
    </div>
    {{endifnot hasItems}}

    {{if hasItems}}
    <div class="cart-table-wrap">
        <table class="caps-products-table">
            <thead>
                <tr>
                    <th>Plato</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                {{foreach items}}
                <tr>
                    <td>
                        <strong>{{nombre}}</strong>
                        <span class="cart-desc">{{descripcion}}</span>
                    </td>
                    <td>L. {{precio}}</td>
                    <td>
                        <form method="POST" action="index.php?page=Tracking_Carrito" class="cart-inline-form">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="platoId" value="{{id}}">
                            <input type="number" name="cantidad" min="0" max="{{stock}}" value="{{cantidad}}">
                            <button type="submit">Actualizar</button>
                        </form>
                    </td>
                    <td>L. {{subtotal}}</td>
                    <td>
                        <form method="POST" action="index.php?page=Tracking_Carrito">
                            <input type="hidden" name="accion" value="quitar">
                            <input type="hidden" name="platoId" value="{{id}}">
                            <button type="submit">Quitar</button>
                        </form>
                    </td>
                </tr>
                {{endfor items}}
            </tbody>
        </table>
    </div>

    <div class="cart-summary">
        <div>
            <span>Total</span>
            <strong>L. {{cartTotal}}</strong>
        </div>
        <form method="POST" action="index.php?page=Tracking_Carrito">
            <input type="hidden" name="accion" value="vaciar">
            <button type="submit" class="caps-secondary-btn">Vaciar</button>
        </form>
        <form method="POST" action="index.php?page=Tracking_Carrito">
            <input type="hidden" name="accion" value="confirmar">
            <button type="submit">Confirmar pedido</button>
        </form>
    </div>
    {{endif hasItems}}
</section>
