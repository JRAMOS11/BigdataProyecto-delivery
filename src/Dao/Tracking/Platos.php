<?php

namespace Dao\Tracking;

use Dao\Table;

class Platos extends Table
{
    public static function getAll()
    {
        $sqlstr = "
            SELECT
                id,
                nombre,
                descripcion,
                precio,
                stock,
                disponible,
                imagen
            FROM platos
            WHERE disponible = 1
            ORDER BY nombre;
        ";

        return self::obtenerRegistros(
            $sqlstr,
            []
        );
    }

    public static function getById(int $id)
    {
        $sqlstr = "
            SELECT
                id,
                nombre,
                descripcion,
                precio,
                stock,
                disponible,
                imagen
            FROM platos
            WHERE id = :id;
        ";

        return self::obtenerUnRegistro(
            $sqlstr,
            [
                'id' => $id
            ]
        );
    }

    public static function reducirStock(
        int $id,
        int $cantidad
    ) {
        $conn = self::getConn();

        $stmt = $conn->prepare("
            UPDATE platos
            SET stock = stock - :cantidad
            WHERE id = :id
            AND stock >= :cantidad2;
        ");

        $stmt->bindParam(
            ':id',
            $id,
            \PDO::PARAM_INT
        );

        $stmt->bindParam(
            ':cantidad',
            $cantidad,
            \PDO::PARAM_INT
        );

        $stmt->bindParam(
            ':cantidad2',
            $cantidad,
            \PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public static function aumentarStock(
        int $id,
        int $cantidad
    ) {
        $sqlstr = "
            UPDATE platos
            SET stock = stock + :cantidad
            WHERE id = :id;
        ";

        return self::executeNonQuery(
            $sqlstr,
            [
                'id' => $id,
                'cantidad' => $cantidad
            ]
        );
    }
}