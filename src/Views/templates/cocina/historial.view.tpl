<section style="padding-top:2rem;">
  <h1 style="font-family:'Playfair Display',serif; font-size:2rem; margin-bottom:1.5rem;">Historial de Pedidos</h1>

  <div style="margin-bottom:1rem;">
    <a href="index.php?page=Cocina.Cocina" style="color:var(--toasted); font-size:.9rem;">← Volver a pedidos activos</a>
  </div>

  <div style="overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; background:var(--bg-surface); border-radius:8px; overflow:hidden;">
      <thead>
        <tr style="border-bottom:2px solid var(--tomato);">
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">#</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Plato</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Cliente</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Cantidad</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Estado</th>
          <th style="padding:.75rem 1rem; text-align:left; color:var(--text-muted); font-size:.78rem; text-transform:uppercase; letter-spacing:.04em;">Fecha</th>
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
            <span class="{{estadoClass}}">{{estadoDsc}}</span>
          </td>
          <td style="padding:.75rem 1rem; color:var(--text-muted); font-size:.85rem;">{{creado_en}}</td>
        </tr>
        {{endfor pedidos}}
      </tbody>
    </table>
  </div>
</section>