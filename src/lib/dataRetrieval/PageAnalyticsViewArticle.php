<?php

namespace DataRetrieval;

require_once(__DIR__ . '/IPageAnalyticsView.php');

use PDO;
use PDOException;

/**
 * ビュー解析クラス（記事数指定）
 *
 * @author naito
 * @version ver1.0.0 2024/03/17
 */
class PageAnalyticsViewArticle implements IPageAnalyticsView
{
    /**
     * データ取得
     *
     * @param PDO データーベース接続情報
     * @param string $inputValue 入力値
     * @return array<mixed> 取得結果
     */
    public function findPageAnalyticsView(PDO $dbh, string $inputValue): array
    {
        try {
            $sql = <<<EOI
            SELECT
                domain_code
                , page_title
                , count_views
            FROM
                page_analytics
            ORDER BY
                count_views DESC
            limit
                ?;
            EOI;

            $prepare = $dbh->prepare($sql);
            $prepare->bindParam(1, $inputValue, PDO::PARAM_INT);
            $prepare->execute();
            $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
            $prepare = null;
            $dbh = null;

            return $result;
        } catch (PDOException $e) {
            exit('【ビュー解析エラー（記事数指定）】' . PHP_EOL . $e->getMessage() . PHP_EOL);
        }
    }
}
