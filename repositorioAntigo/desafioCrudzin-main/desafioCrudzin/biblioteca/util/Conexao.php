<?php

class Conexao {

    private static ?PDO $conexao = null;

    public static function getConexao(): PDO {

        if (self::$conexao == null) {

            $opcoes = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            self::$conexao = new PDO(
                "mysql:host=localhost;dbname=tarefas_db",
                "root",
                "bancodedados",
                $opcoes
            );
        }

        return self::$conexao;
    }
}