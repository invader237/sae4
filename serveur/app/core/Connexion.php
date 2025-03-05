<?php declare(strict_types=1);

require_once('./app/core/VarEnv.php');

class Database {
    private static ?PDO $instance = null;

    private function construct() {}
    private function clone() {}

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $db_config = [
                'SGBD' => SGBD,
                'HOST' => DB_HOST,
                'DB_NAME' => DB_NAME,
                'USER' => DB_USER,
                'PASSWORD' => DB_PASSWORD
            ];

            try {
                self::$instance = new PDO(
                    "{$db_config['SGBD']}:host={$db_config['HOST']};dbname={$db_config['DB_NAME']};charset=utf8",
                    $db_config['USER'],
                    $db_config['PASSWORD'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
