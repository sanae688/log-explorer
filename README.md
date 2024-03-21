# ログ解析システム

## 環境構築

*ターミナル上で下記コマンドを実行*

1. 基本

```bash
# Docker イメージのビルド
docker-compose build

# Docker コンテナの起動
docker-compose up -d

# Docker コンテナの停止・削除
docker-compose down
```

2. DB初期設定

```bash
# DB 初期設定
docker compose exec app php lib/database/InitialData.php
```

3. データのインポート

*※必ず「2.DB初期設定」が完了してから下記実施すること*

* Wikipediaのアクセスログのデータを下記URLからダウンロード
[Index of /other/pageviews/2024/2024-03/](https://dumps.wikimedia.org/other/pageviews/2024/2024-03/)

* ダウンロードしたファイルはsrc/lib/database配下に解凍した状態で追加

* ファイル名は`page_analytics`に変更

```bash
# Dockerのdbコンテナにrootユーザーでログイン(コマンド実行後、パスワードを聞かれたら`pass`を入力)
docker compose exec db mysql -p

# local_infileをONに設定
mysql> SET GLOBAL local_infile=ON;

# ユーザー権限をSUPERに設定(コマンド実行後、`Ctrl＋D`でDockerのdbコンテナから退出)
mysql> GRANT SUPER ON *.* To test_user@'%';

# データのインポート(コマンド実行後、パスワードを聞かれたら`pass`を入力)
docker compose exec app mysqlimport -h db -u test_user -p -d --fields-terminated-by=' ' --local test_database lib/database/page_analytics
```

## ログ解析システム使用方法

*※必ず「環境構築」が完了してから下記実施すること*

```bash
# ログ解析システム実行(実行後はコマンドに従って進めるのみ)
docker compose exec app php lib/pageAnalytics/Analyzer.php
```

## 目的

* PHPからデータベースの操作を行うことに慣れる
* オブジェクト指向の復習
* ログ解析システム作成にあたり作成したファイル(src/lib配下)
```bash
.
└── src
    └── lib
        ├── database
        │   ├── DbConnect.php
        │   ├── InitialData.php
        │   └── page_analytics
        ├── dataRetrieval
        │   ├── IPageAnalyticsView.php
        │   ├── PageAnalyticsEvaluator.php
        │   ├── PageAnalyticsViewArticle.php
        │   └── PageAnalyticsViewDomainCode.php
        └── pageAnalytics
            └── Analyzer.php
```

## 教材

[独学エンジニア](https://dokugaku-engineer.com/)
