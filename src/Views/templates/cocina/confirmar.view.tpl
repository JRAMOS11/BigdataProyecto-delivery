<style>
main { padding: 0 !important; max-width: 100% !important; }
</style>

<div class="confirmar-barra">☑ CONFIRMACIÓN DE PEDIDO</div>

<div class="confirmar-body">

    <div class="confirmar-icon">✓</div>
    <h1>Confirmación de Pedido</h1>
    <div class="confirmar-linea"></div>

    {{if flash_msg}}
    <div class="confirmar-success">✔ {{flash_msg}}</div>
    {{endif flash_msg}}

    {{if error}}
    <div style="background:rgba(235,94,48,.15); color:var(--tomato); border-left:3px solid var(--tomato); padding:.75rem 1.25rem; border-radius:6px; margin-bottom:1.5rem; font-weight:600; text-align:left;">
      ⚠ Conflicto de concurrencia: este pedido fue modificado por otro operador. Los datos han sido actualizados.
    </div>
    {{endif error}}

    <table class="confirmar-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Cliente</th>
          <th>Platos</th>
          <th>Cantidad</th>
          <th>Estado</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        {{foreach pedidos}}
        <tr>
          <td>{{id}}</td>
          <td>{{cliente_nombre}}</td>
          <td>{{platos_nombres}}</td>
          <td>{{total_items}}</td>
          <td><span class="estado-badge-pend">pendiente</span></td>
          <td style="display:flex; gap:8px;">
            <form method="POST" action="index.php?page=Cocina.ActualizarEstado">
              <input type="hidden" name="id" value="{{id}}">
              <input type="hidden" name="version" value="{{version}}">
              <input type="hidden" name="estado" value="pendiente">
              <input type="hidden" name="accion" value="confirmar">
              <button type="submit" class="btn-confirmar-p">✓ Confirmar orden</button>
            </form>
            <form method="POST" action="index.php?page=Cocina.ActualizarEstado">
              <input type="hidden" name="id" value="{{id}}">
              <input type="hidden" name="version" value="{{version}}">
              <input type="hidden" name="estado" value="pendiente">
              <input type="hidden" name="accion" value="rechazar">
              <button type="submit" class="btn-rechazar-p">✗ Rechazar</button>
            </form>
          </td>
        </tr>
        {{endfor pedidos}}
      </tbody>
    </table>

    <div style="margin-top:1.5rem; text-align:left;">
      <a href="index.php?page=Cocina.Cocina" class="cocina-back">← Ver pedidos en proceso</a>
    </div>

</div>