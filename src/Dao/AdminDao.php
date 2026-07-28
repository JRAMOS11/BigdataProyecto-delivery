<?php
namespace Dao;

class AdminDao extends Dao
{
    // ── Dashboard ─────────────────────────────────────────
    public static function getResumen(): array
    {
        $conn = self::getConn();

        $totalPedidos  = $conn->query('SELECT COUNT(*) FROM pedidos')->fetchColumn();
        $pendientes    = $conn->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente'")->fetchColumn();
        $enProceso     = $conn->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'en_proceso'")->fetchColumn();
        $listos        = $conn->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'listo'")->fetchColumn();
        $totalClientes = $conn->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn();
        $stockBajo     = $conn->query('SELECT COUNT(*) FROM platos WHERE stock <= 2 AND disponible = 1')->fetchColumn();

        return compact('totalPedidos', 'pendientes', 'enProceso', 'listos', 'totalClientes', 'stockBajo');
    }

    // ── Usuarios ──────────────────────────────────────────
    public static function getAllUsuarios(): array
    {
        $stmt = self::getConn()->prepare(
            'SELECT id, nombre, email, rol, creado_en FROM usuarios ORDER BY creado_en DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function cambiarRol(int $id, string $rol): bool
    {
        $stmt = self::getConn()->prepare('UPDATE usuarios SET rol = :rol WHERE id = :id');
        $stmt->execute(['rol' => $rol, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public static function eliminarUsuario(int $id): bool
    {
        $stmt = self::getConn()->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // ── Platos ───────────────────────────────────────────
    public static function getAllPlatos(): array
    {
        $stmt = self::getConn()->prepare(
            'SELECT id, nombre, descripcion, precio, stock, disponible FROM platos ORDER BY id ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function toggleDisponible(int $id, int $disponibleActual): bool
    {
        $nuevo = $disponibleActual ? 0 : 1;
        $stmt  = self::getConn()->prepare('UPDATE platos SET disponible = :d WHERE id = :id');
        $stmt->execute(['d' => $nuevo, 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public static function actualizarStock(int $id, int $stock): bool
    {
        $stmt = self::getConn()->prepare('UPDATE platos SET stock = :s WHERE id = :id');
        $stmt->execute(['s' => max(0, $stock), 'id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public static function crearPlato(
        string $nombre,
        string $descripcion,
        float $precio,
        int $stock,
        int $disponible
    ): bool {
        $stmt = self::getConn()->prepare('
            INSERT INTO platos (nombre, descripcion, precio, stock, disponible)
            VALUES (:nombre, :descripcion, :precio, :stock, :disponible)
        ');
        return $stmt->execute([
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => max(0, $precio),
            'stock' => max(0, $stock),
            'disponible' => $disponible ? 1 : 0,
        ]);
    }

    public static function actualizarPlato(
        int $id,
        string $nombre,
        string $descripcion,
        float $precio,
        int $stock,
        int $disponible
    ): bool {
        $stmt = self::getConn()->prepare('
            UPDATE platos
            SET nombre = :nombre,
                descripcion = :descripcion,
                precio = :precio,
                stock = :stock,
                disponible = :disponible
            WHERE id = :id
        ');
        $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => max(0, $precio),
            'stock' => max(0, $stock),
            'disponible' => $disponible ? 1 : 0,
        ]);
        return $stmt->rowCount() > 0;
    }
}
