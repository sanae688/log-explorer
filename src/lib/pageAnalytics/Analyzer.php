<?php

namespace PageAnalytics;

require_once(__DIR__ . '/../dataRetrieval/IPageAnalyticsView.php');
require_once(__DIR__ . '/../dataRetrieval/PageAnalyticsEvaluator.php');
require_once(__DIR__ . '/../dataRetrieval/PageAnalyticsViewArticle.php');
require_once(__DIR__ . '/../dataRetrieval/PageAnalyticsViewDomainCode.php');

use dataRetrieval\IPageAnalyticsView;
use dataRetrieval\PageAnalyticsEvaluator;
use dataRetrieval\PageAnalyticsViewArticle;
use dataRetrieval\PageAnalyticsViewDomainCode;

/**
 * 解析クラス
 *
 * @author naito
 * @version ver1.0.0 2024/03/17
 */
class Analyzer
{
    /* @var array ログ解析方法（1：記事数指定、2：ドメインコード指定） */
    private const ANALYSIS_MODE = [
        '1',
        '2',
    ];

    public function __construct()
    {
        echo '************ログ解析開始************' . PHP_EOL;
    }

    /**
     * ログ解析開始
     */
    public function executeAnalysis(): void
    {
        $inputAnalysisMode = '';
        while (!in_array($inputAnalysisMode, self::ANALYSIS_MODE)) {
            echo 'ログ解析方法を入力して下さい。※入力条件→記事数指定の場合は1、ドメインコード指定の場合は2（1/2）' . PHP_EOL;
            $inputAnalysisMode = trim(fgets(STDIN));
        }

        $inputArticleCount = '';
        switch ($inputAnalysisMode) {
            case self::ANALYSIS_MODE[0]:
                while (!preg_match('/^[0-9]{1,2}$/', $inputArticleCount)) {
                    echo '記事数を入力して下さい。※入力条件→1~2桁の半角数字（例：30）' . PHP_EOL;
                    $inputArticleCount = trim(fgets(STDIN));
                }
                break;
            case self::ANALYSIS_MODE[1]:
                while (!preg_match('/^[a-z. ]+$/', $inputArticleCount)) {
                    echo 'ドメインコードを入力して下さい。※入力条件→半角英字、2つ以上入力する場合は半角スペースを間に挿入（例：en ja en.m）' . PHP_EOL;
                    $inputArticleCount = trim(fgets(STDIN));
                }
                break;
        }

        $pageAnalyticsView = $this->getPageAnalyticsView($inputAnalysisMode);
        $pageAnalyticsEvaluator = new PageAnalyticsEvaluator($pageAnalyticsView);
        $results = $pageAnalyticsEvaluator->findPageAnalyticsView($inputArticleCount);

        if ($results) {
            foreach ($results as $result) {
                echo implode(',', $result) . PHP_EOL;
            }
        } else {
            echo 'データが存在しませんでした。' . PHP_EOL;
        }

        echo '************ログ解析終了************' . PHP_EOL;
    }

    /**
     * ビュー解析取得
     *
     * @param string $inputAnalysisMode ログ解析方法
     * @return IPageAnalyticsView ビュー解析インスタンス
     */
    private function getPageAnalyticsView(string $inputAnalysisMode): IPageAnalyticsView
    {
        $pageAnalyticsView = '';
        switch ($inputAnalysisMode) {
            case self::ANALYSIS_MODE[0]:
                $pageAnalyticsView = new PageAnalyticsViewArticle();
                break;
            case self::ANALYSIS_MODE[1]:
                $pageAnalyticsView = new PageAnalyticsViewDomainCode();
                break;
        }

        return $pageAnalyticsView;
    }
}

/**
 * 実行
 */
$analyzer = new Analyzer();
$analyzer->executeAnalysis();
