<?php

namespace Database;

require_once(__DIR__ . '/DbConnect.php');

use PDOException;

/**
 * データベース初期設定クラス
 *
 * @author naito
 * @version ver1.0.0 2024/03/17
 */
class InitialData
{
    /* @var PDO データーベース接続情報 */
    protected $dbh;

    public function __construct()
    {
        echo '************データベース初期設定開始************' . PHP_EOL;
        $dbConnect = new DbConnect();
        $this->dbh = $dbConnect->dbConnect();
    }

    /**
     * データベース初期設定
     */
    public function InitialData(): void
    {
        try {
            $this->initialDeleteTable();
            $this->initialCreateTable();
            $this->dbh = null;
            echo '************データベース初期設定終了************' . PHP_EOL;
        } catch (PDOException $e) {
            exit('【データベース初期設定エラー】' . PHP_EOL . $e->getMessage() . PHP_EOL);
        }
    }

    /**
     * テーブル作成
     */
    public function initialCreateTable(): void
    {
        $sql = <<<EOI
        CREATE TABLE page_analytics(
            domain_code VARCHAR (50) COMMENT 'ドメインコード'
            , page_title VARCHAR (100) COMMENT 'ページタイトル'
            , count_views INTEGER NOT NULL COMMENT '閲覧回数'
            , total_response_size INTEGER NOT NULL COMMENT '合計応答サイズ'
            , PRIMARY KEY (domain_code, page_title)
        ) COMMENT 'アクセス解析テーブル';
        EOI;

        $result = $this->dbh->query($sql);

        if ($result) {
            echo 'テーブル作成完了' . PHP_EOL;
            $result = null;
        } else {
            exit('【テーブル作成エラー】' . PHP_EOL);
        }
    }

    /**
     * テーブル削除
     */
    public function initialDeleteTable(): void
    {
        $sql = 'DROP TABLE IF EXISTS page_analytics';

        $result = $this->dbh->query($sql);

        if ($result) {
            echo 'テーブル削除完了' . PHP_EOL;
            $result = null;
        } else {
            exit('【テーブル削除エラー】' . PHP_EOL);
        }
    }
}

/**
 * 実行
 */
$dbConnect = new InitialData();
$dbConnect->InitialData();
