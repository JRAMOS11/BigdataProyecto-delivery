<?php
 
namespace Dao;
 
class PedidoDao extends Dao
{
   
    public static function getAll(): array
    {
        $stmt = self::getConn()->prepare("
            SELECT p.id, p.estado, p.version, p.creado_en,
                u.nombre as cliente_nombre,
                GROUP_CONCAT(pl.nombre SEPARATOR ', ') as platos_nombres,
                SUM(pp.cantidad) as total_items
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
            JOIN pedido_platos pp ON pp.pedido_id = p.id
            JOIN platos pl ON pp.plato_id = pl.id
            WHERE p.estado IN ('en_proceso', 'listo')
            GROUP BY p.id, p.estado, p.version, p.creado_en, u.nombre
            ORDER BY p.creado_en ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public static function getPendientes(): array
    {
        $stmt = self::getConn()->prepare("
            SELECT p.id, p.estado, p.version, p.creado_en,
                u.nombre as cliente_nombre,
                GROUP_CONCAT(pl.nombre SEPARATOR ', ') as platos_nombres,
                SUM(pp.cantidad) as total_items
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
            JOIN pedido_platos pp ON pp.pedido_id = p.id
            JOIN platos pl ON pp.plato_id = pl.id
            WHERE p.estado = 'pendiente'
            GROUP BY p.id, p.estado, p.version, p.creado_en, u.nombre
            ORDER BY p.creado_en ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public static function getHistorial(): array
    {
        $stmt = self::getConn()->prepare("
            SELECT p.id, p.estado, p.version, p.creado_en,
                u.nombre as cliente_nombre,
                GROUP_CONCAT(pl.nombre SEPARATOR ', ') as platos_nombres,
                SUM(pp.cantidad) as total_items
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
            JOIN pedido_platos pp ON pp.pedido_id = p.id
            JOIN platos pl ON pp.plato_id = pl.id
            WHERE p.estado IN ('entregado', 'cancelado')
            GROUP BY p.id, p.estado, p.version, p.creado_en, u.nombre
            ORDER BY p.creado_en DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public static function getById(int $id): array|false
    {
        $stmt = self::getConn()->prepare("
            SELECT p.id, p.estado, p.version, p.creado_en,
                   u.nombre as cliente_nombre,
                   GROUP_CONCAT(pl.nombre SEPARATOR ', ') as platos_nombres,
                   SUM(pp.cantidad) as total_items
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
            JOIN pedido_platos pp ON pp.pedido_id = p.id
            JOIN platos pl ON pp.plato_id = pl.id
            WHERE p.id = :id
            GROUP BY p.id, p.estado, p.version, p.creado_en, u.nombre
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
 
    public static function getItems(int $pedido_id): array
    {
        $stmt = self::getConn()->prepare("
            SELECT plato_id, cantidad
            FROM pedido_platos
            WHERE pedido_id = :pedido_id
        ");
        $stmt->execute(['pedido_id' => $pedido_id]);
        return $stmt->fetchAll();
    }
 

    public static function actualizarEstado(int $id, string $nuevoEstado, int $version): bool
    {
        $conn = self::getConn();
        $conn->beginTransaction();
        try {
           
            $check = $conn->prepare('
                SELECT version, estado FROM pedidos WHERE id = :id FOR UPDATE
            ');
            $check->execute(['id' => $id]);
            $pedido = $check->fetch();
 
            if (!$pedido || (int)$pedido['version'] !== $version) {
                $conn->rollBack();
                return false; // Conflict detected
            }
 
            $stmt = $conn->prepare('
                UPDATE pedidos
                SET estado = :estado, version = version + 1
                WHERE id = :id
            ');
            $stmt->execute(['estado' => $nuevoEstado, 'id' => $id]);
            $conn->commit();
            return true;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}