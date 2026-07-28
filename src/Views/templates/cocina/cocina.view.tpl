<section>
  <h1 style="font-family:'Playfair Display',serif; font-size:2rem; margin-bottom:1.5rem; padding-top:2rem;">Panel de Cocina</h1>

  {{if error}}
  <div style="background:rgba(235,94,48,.15); color:var(--tomato); border-left:3px solid var(--tomato); padding:.75rem 1.25rem; border-radius:6px; margin-bottom:1.5rem; font-weight:600;">
    ⚠ Conflicto de concurrencia: este pedido fue modificado por otro operador. Los datos han sido actualizados.
  </div>
  {{endif error}}

  <div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; background:var(--bg-surface); border-radius:8px; overflow:hidden;">
      <thead>
        <tr style="border-bottom:2px solid var(--tomato);">
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">#</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Plato</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Cliente</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Cantidad</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Estado</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Acción</th>
        </tr>
      </thead>
      <tbody>
        {{foreach pedidos}}
        <tr style="border-bottom:1px solid var(--border);">
          <td style="padding:.75rem 1rem; color:var(--text-muted);">{{id}}</td>
          <td style="padding:.75rem 1rem;">{{platos_nombres}}</td>
          <td style="padding:.75rem 1rem; color:var(--text-muted);">{{cliente_nombre}}</td>
          <td style="padding:.75rem 1rem;">{{total_items}}</td>
          <td style="padding:.75rem 1rem;">
            <span style="background:var(--bg-dark); border:1px solid var(--border); padding:.2rem .75rem; border-radius:2rem; font-size:.82rem;">{{estado}}</span>
          </td>
          <td style="padding:.75rem 1rem;">
            <form method="POST" action="index.php?page=Cocina.ActualizarEstado">
              <input type="hidden" name="id" value="{{id}}">
              <input type="hidden" name="version" value="{{version}}">
              <input type="hidden" name="estado" value="{{estado}}">
              <button type="submit">{{accion_estado_texto}}</button>
            </form>
          </td>
        </tr>
        {{endfor pedidos}}
      </tbody>
    </table>
  </div>
</section>
