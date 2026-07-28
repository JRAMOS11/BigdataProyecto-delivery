<?php

namespace Dao;

class UsuarioDAO extends Dao
{
    public static function getUsuarioByEmail(string $email): array|false
    {
        $sql = "
            SELECT *
            FROM usuarios
            WHERE email = :email
            LIMIT 1;
        ";

        $stmt = self::getConn()->prepare($sql);
        $stmt->execute([
            "email" => $email
        ]);

        return $stmt->fetch();
    }

    public static function crearUsuario(
        string $nombre,
        string $email,
        string $password
    ): bool {

        $sql = "
            INSERT INTO usuarios
            (nombre,email,password,rol)
            VALUES
            (:nombre,:email,:password,'cliente');
        ";

        $stmt = self::getConn()->prepare($sql);

        return $stmt->execute([
            "nombre" => $nombre,
            "email" => $email,
            "password" => password_hash(
                $password,
                PASSWORD_DEFAULT
            )
        ]);
    }
}