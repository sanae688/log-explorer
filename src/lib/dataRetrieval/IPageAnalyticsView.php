<?php

namespace DataRetrieval;

use PDO;

/**
 * ビュー解析インターフェイス
 *
 * @author naito
 * @version ver1.0.0 2024/03/17
 */
interface IPageAnalyticsView
{
    /**
     * データ取得
     *
     * @param PDO $dbh データーベース接続情報
     * @param string $inputValue 入力値
     * @return array<mixed> 取得結果
     */
    public function findPageAnalyticsView(PDO $dbh, string $inputValue): array;
}
