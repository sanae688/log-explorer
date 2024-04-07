<?php

namespace PageAnalytics;

require_once(__DIR__ . '/../dataRetrieval/IPageAnalyticsView.php');
require_once(__DIR__ . '/../dataRetrieval/PageAnalyticsEvaluator.php');
require_once(__DIR__ . '/../dataRetrieval/PageAnalyticsViewArticle.php');
require_once(__DIR__ . '/../dataRetrieval/PageAnalyticsViewDomainCode.php');
require_once(__DIR__ . '/../enum/AnalysisMode.php');

use dataRetrieval\IPageAnalyticsView;
use dataRetrieval\PageAnalyticsEvaluator;
use dataRetrieval\PageAnalyticsViewArticle;
use dataRetrieval\PageAnalyticsViewDomainCode;
use enum\AnalysisMode;
use PDOException;

/**
 * 解析クラス
 *
 * @author naito
 * @version ver1.0.0 2024/03/17
 */
class Analyzer
{
    public function __construct()
    {
        echo '************ログ解析開始************' . PHP_EOL;
    }

    /**
     * ログ解析開始
     */
    public function executeAnalysis(): void
    {
        try {
            $inputAnalysisMode = '';
            foreach (AnalysisMode::cases() as $mode) {
                $analysisMode[] = $mode->name;
            }

            while (!in_array($inputAnalysisMode, $analysisMode)) {
                echo 'ログ解析方法を入力して下さい。※入力条件→記事数指定の場合はArticle、ドメインコード指定の場合はDomainCode（Article/DomainCode）' . PHP_EOL;
                $inputAnalysisMode = trim(fgets(STDIN));
            }

            $inputCondition = '';
            switch ($inputAnalysisMode) {
                case AnalysisMode::Article->name:
                    while (!preg_match('/^[0-9]{1,2}$/', $inputCondition)) {
                        echo '記事数を入力して下さい。※入力条件→1~2桁の半角数字（例：30）' . PHP_EOL;
                        $inputCondition = trim(fgets(STDIN));
                    }
                    break;
                case AnalysisMode::DomainCode->name:
                    while (!preg_match('/^[a-z. ]+$/', $inputCondition)) {
                        echo 'ドメインコードを入力して下さい。※入力条件→半角英字、ドメインコードを2つ以上入力する場合は半角スペースを間に挿入（例：en ja en.m）' . PHP_EOL;
                        $inputCondition = trim(fgets(STDIN));
                    }
                    break;
            }

            $pageAnalyticsView = $this->getPageAnalyticsView($inputAnalysisMode);
            $pageAnalyticsEvaluator = new PageAnalyticsEvaluator($pageAnalyticsView);
            $results = $pageAnalyticsEvaluator->findPageAnalyticsView($inputCondition);

            if ($results) {
                foreach ($results as $result) {
                    echo implode(',', $result) . PHP_EOL;
                }
            } else {
                echo 'データが存在しませんでした。' . PHP_EOL;
            }
        } catch (PDOException $e) {
            exit('【ログ解析エラー】' . PHP_EOL . $e->getMessage() . PHP_EOL);
        } finally {
            echo '************ログ解析終了************' . PHP_EOL;
        }
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
            case AnalysisMode::Article->name:
                $pageAnalyticsView = new PageAnalyticsViewArticle();
                break;
            case AnalysisMode::DomainCode->name:
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
