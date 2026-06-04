# flea-market（新模擬案件\_フリマアプリ）

## 環境構築

**Dockerビルド**

1. `git clone git@github.com:yuyu580905-dev/flea-market.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel環境構築**

1. `docker-compose exec php bash`
2. `composer install`
3. .env.example をコピーして .env を作成

```bash
cp .env.example .env
```

4. .envに以下の環境変数を追加

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

5. アプリケーションキーの作成

```bash
php artisan key:generate
```

6. マイグレーションの実行

```bash
php artisan migrate
```

7. storageディレクトリ公開

```bash
php artisan storage:link
```

8. シーディングの実行

```bash
php artisan db:seed
```

## メール認証について

本アプリではMailtrapを使用しています

1. Mailtrapに登録
2. Sandboxを作成
3. SMTP情報を.envに設定（Laravel環境構築 4.で設定済み）
4. `php artisan config:clear`

## テスト

PHPUnitを使用して以下の機能テストを実装

- 会員登録
- メール認証機能
- ログイン
- ログアウト
- 商品一覧取得
- マイリスト一覧取得
- 商品検索
- 商品詳細取得
- いいね機能
- コメント機能
- 商品購入
- 支払方法選択
- 配送先変更
- ユーザー情報取得
- ユーザー情報変更
- 商品出品情報登録

## 使用技術(実行環境)

- PHP8.1
- Laravel8.75
- MySQL8.0
- Nginx
- Docker
- Docker Compose

## ER図

![ER図](src/er-diagram.png)

## URL

- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/
