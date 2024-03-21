<?php

namespace Database;

use PDO;
use PDOException;

/**
 * データーベース接続クラス
 *
 * @author naito
 * @version ver1.0.0 2024/03/17
 */
class DbConnect
{
    /* @var array PDO-オプション */
    private const PDO_OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
    ];

    /**
     * データーベース接続
     *
     * @return PDO データーベース接続
     */
    public function dbConnect(): PDO
    {
        try {
            $dbh = new PDO($_ENV['MYSQL_DNS'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], self::PDO_OPTIONS);
            echo 'DB接続完了' . PHP_EOL;
            return $dbh;
        } catch (PDOException $e) {
            exit('【DB接続エラー】' . PHP_EOL . $e->getMessage() . PHP_EOL);
        }
    }
}
