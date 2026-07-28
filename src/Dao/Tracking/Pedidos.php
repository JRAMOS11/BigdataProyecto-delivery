<?php
 
namespace Dao\Tracking;
 
use Dao\Table;
 
class Pedidos extends Table
{
    public static function insertPedido(
        int $usuario_id,
        int $plato_id,
        int $cantidad
    ) {
        $conn = self::getConn();
        $conn->beginTransaction();
        try {
            $stmt = $conn->prepare("
                INSERT INTO pedidos (usuario_id, estado)
                VALUES (:usuario_id, 'pendiente')
            ");
            $stmt->bindParam(':usuario_id', $usuario_id, \PDO::PARAM_INT);
            $stmt->execute();
 
            $pedido_id = (int) $conn->lastInsertId();
 
            $stmt2 = $conn->prepare("
                INSERT INTO pedido_platos (pedido_id, plato_id, cantidad)
                VALUES (:pedido_id, :plato_id, :cantidad)
            ");
            $stmt2->bindParam(':pedido_id', $pedido_id, \PDO::PARAM_INT);
            $stmt2->bindParam(':plato_id',  $plato_id,  \PDO::PARAM_INT);
            $stmt2->bindParam(':cantidad',  $cantidad,  \PDO::PARAM_INT);
            $stmt2->execute();
 
            $conn->commit();
            return $pedido_id;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public static function insertPedidoConItems(int $usuario_id, array $items)
    {
        $conn = self::getConn();
        $conn->beginTransaction();

        try {
            $itemsValidos = [];

            foreach ($items as $plato_id => $cantidad) {
                $plato_id = (int)$plato_id;
                $cantidad = (int)$cantidad;

                if ($plato_id <= 0 || $cantidad <= 0) {
                    continue;
                }

                $stmtStock = $conn->prepare("
                    SELECT id, stock, disponible
                    FROM platos
                    WHERE id = :id
                    FOR UPDATE
                ");
                $stmtStock->execute(['id' => $plato_id]);
                $plato = $stmtStock->fetch(\PDO::FETCH_ASSOC);

                if (!$plato || (int)$plato['disponible'] !== 1 || (int)$plato['stock'] < $cantidad) {
                    $conn->rollBack();
                    return false;
                }

                $itemsValidos[$plato_id] = $cantidad;
            }

            if (count($itemsValidos) === 0) {
                $conn->rollBack();
                return false;
            }

            $stmtPedido = $conn->prepare("
                INSERT INTO pedidos (usuario_id, estado)
                VALUES (:usuario_id, 'pendiente')
            ");
            $stmtPedido->execute(['usuario_id' => $usuario_id]);
            $pedido_id = (int)$conn->lastInsertId();

            $stmtItem = $conn->prepare("
                INSERT INTO pedido_platos (pedido_id, plato_id, cantidad)
                VALUES (:pedido_id, :plato_id, :cantidad)
            ");
            $stmtUpdateStock = $conn->prepare("
                UPDATE platos
                SET stock = stock - :cantidad
                WHERE id = :plato_id
                AND stock >= :cantidad2
            ");

            foreach ($itemsValidos as $plato_id => $cantidad) {
                $stmtItem->execute([
                    'pedido_id' => $pedido_id,
                    'plato_id' => $plato_id,
                    'cantidad' => $cantidad,
                ]);
                $stmtUpdateStock->execute([
                    'plato_id' => $plato_id,
                    'cantidad' => $cantidad,
                    'cantidad2' => $cantidad,
                ]);
            }

            $conn->commit();
            return $pedido_id;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
 
    public static function getPedidosByUser(
        int $usuario_id,
        string $estado = ''
    ) {
        $params = ['usuario_id' => $usuario_id];
        $estadoFilter = '';

        // El controlador valida el estado; aqui solo se agrega el filtro si aplica.
        if ($estado !== '') {
            $estadoFilter = ' AND p.estado = :estado';
            $params['estado'] = $estado;
        }

        $sqlstr = "
            SELECT
                p.id,
                p.usuario_id,
                pp.plato_id,
                pp.cantidad,
                p.estado,
                p.creado_en,
                pl.nombre,
                pl.precio
            FROM pedidos p
            INNER JOIN pedido_platos pp ON pp.pedido_id = p.id
            INNER JOIN platos pl ON pl.id = pp.plato_id
            WHERE p.usuario_id = :usuario_id
            $estadoFilter
            ORDER BY p.creado_en DESC;
        ";
 
        return self::obtenerRegistros(
            $sqlstr,
            $params
        );
    }
 
    public static function getPedidoById(
        int $id
    ) {
        $sqlstr = "
            SELECT
                p.id,
                p.usuario_id,
                pp.plato_id,
                pp.cantidad,
                p.estado,
                p.creado_en
            FROM pedidos p
            INNER JOIN pedido_platos pp ON pp.pedido_id = p.id
            WHERE p.id = :id
            LIMIT 1;
        ";
 
        return self::obtenerUnRegistro(
            $sqlstr,
            ['id' => $id]
        );
    }
 
    public static function cancelarPedido(
        int $id
    ) {
        $sqlstr = "
            UPDATE pedidos
            SET estado = 'cancelado'
            WHERE id = :id
            AND estado = 'pendiente';
        ";
 
        return self::executeNonQuery(
            $sqlstr,
            ['id' => $id]
        );
    }
 
    public static function getLastInsertId()
    {
        return self::getConn()->lastInsertId();
    }
}
