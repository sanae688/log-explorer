<?php

namespace DataRetrieval;

require_once(__DIR__ . '/IPageAnalyticsView.php');

use PDO;

/**
 * ビュー解析クラス（ドメインコード指定）
 *
 * @author naito
 * @version ver1.0.0 2024/03/17
 */
class PageAnalyticsViewDomainCode implements IPageAnalyticsView
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
        $param = explode(" ", $inputValue);
        $placeHolders = implode(',', array_fill(0, count($param), '?'));

        $sql = <<<EOI
            SELECT
                domain_code
                , SUM(count_views) AS sum_count_views
            FROM
                page_analytics
            WHERE
                domain_code IN ($placeHolders)
            GROUP BY
                domain_code
            ORDER BY
                sum_count_views DESC;
            EOI;

        $prepare = $dbh->prepare($sql);
        $prepare->execute($param);
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
