<?php

namespace DataRetrieval;

require_once(__DIR__ . '/IPageAnalyticsView.php');
require_once(__DIR__ . '/../database/DbConnect.php');

use database\DbConnect;

/**
 * ビュー解析識別クラス
 *
 * @author naito
 * @version ver1.0.0 2024/03/17
 */
class PageAnalyticsEvaluator
{
    /**
     * コンストラクタ
     *
     * @param IPageAnalyticsView $pageAnalyticsView ビュー解析
     */
    public function __construct(private IPageAnalyticsView $pageAnalyticsView)
    {
    }

    /**
     * データ取得
     *
     * @param string $inputValue 入力値
     * @return array<mixed> 取得結果
     */
    public function findPageAnalyticsView(string $inputValue): array
    {
        $dbConnect = new DbConnect();
        $dbh = $dbConnect->dbConnect();
        $result = $this->pageAnalyticsView->findPageAnalyticsView($dbh, $inputValue);
        $dbh = null;

        return $result;
    }
}
