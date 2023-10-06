<?php

namespace app\Core;

use Exception;
use PDO;

class DatabaseService
{
    private static $dbConnect;

    /**
     * @return PDO
     */
    public static function getConnect(): PDO
    {
        if (!self::$dbConnect) {
            try {
                self::$dbConnect = new PDO('sqlite:' . __DIR__ . '/../ecoledirecte.db');
                self::$dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }

        return self::$dbConnect;
    }
}