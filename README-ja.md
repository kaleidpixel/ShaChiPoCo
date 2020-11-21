ShaChiPoCo
==========

## テキストエディターの準備
開発に使用するテキストエディター・IDE の設定で、EditorConfig を有効にしてください。

EditorConfig は、複数の開発者が様々なエディタや IDE にまたがって同じプロジェクトで作業する際に、一貫性のあるコーディングスタイルを維持するのに役立ちます。詳細は[公式サイト](https://editorconfig.org)を確認してください。

## コンパイルツールの準備（Scss・JavaScript）
Scss のコンパイル、JavaScript の結合・圧縮を行うの際に Prepros という GUI ツールを使用します。[公式サイト](https://prepros.io)からダウンロード・インストールし、本プロジェクトのルートディレクトリを Prepros に追加してください。

## セットアップ
セットアップ手順は以下になります。

### サーバーの設定
ROOT_DIR/public を公開ディレクトリに指定して、XAMPP・MAMP を設定してください。  
サブディレクトリに設置することは仕様上できません。

* ○ https://example.com
* ☓ https://example.com/admin/

### Composer のインストール
以下のコマンドを実行して、各ライブラリをインストールしてください。

```
php composer install
```

### 設定ファイルの作成
プロジェクトディレクトリ直下の .env.example ファイルをコピー（必ずコピー）して .env ファイルを作成します。.env ファイルを作成したらテキストエディター・IDE で開き、必要箇所を変更してください。

## API ドキュメント
`docs/index.html` をブラウザで開くとドキュメントを閲覧することができます。

## ディレクトリ構造

```
root/
|-- apps
|   |-- Core                        // このディレクトリ以下は変更しない。代わりに User ディレクトリ以下を編集
|   |   |-- Controller
|   |   |   |-- Auth.php
|   |   |   |-- Notfound404.php
|   |   |   `-- Welcome.php
|   |   |-- Helper
|   |   |   |-- Array.php
|   |   |   |-- Cookie.php
|   |   |   |-- Form.php
|   |   |   |-- Integer.php
|   |   |   |-- Mail.php
|   |   |   |-- String.php
|   |   |   |-- Token.php
|   |   |   |-- Url.php
|   |   |   `-- Utility.php
|   |   |-- Library
|   |   |   `-- Auth.php
|   |   |-- Model
|   |   |   `-- Auth.php
|   |   |-- Controller.php
|   |   |-- Database.php
|   |   |-- Env.php
|   |   |-- Header.php
|   |   |-- Loader.php
|   |   |-- Model.php
|   |   |-- Path.php
|   |   `-- Router.php
|   |-- User                        // Core ファイルの上書きや追加
|   |   `-- Controller
|   |       `-- UserNotfound404.php // Core 書き換えのサンプル
|   `-- Framework.php
|-- cache                           // twig などのキャッシュファイル
|-- docs                            // ドキュメント
|-- logs                            // エラーログなど
|-- public                          // 公開ディレクトリ 
|   |-- assets
|   |   |-- css                     // root/src/scss の出力先 
|   |   |-- fonts
|   |   |-- img                     // root/src/img の出力先 
|   |   |-- js                      // root/src/js の出力先
|   |   `-- modules
|   |-- .htaccess
|   `-- index.php
|-- src
|   |-- img                         // 圧縮前の画像. public/assets/img 内に出力
|   |-- js                          // 結合・圧縮前のjs. public/assets/js 内に出力
|   |-- scss                        // public/assets/css 内にビルド
|   `-- twig
|-- vendor
|-- .editorconfig                   // 開発前に使用エディターに必ず適用
|-- .env.example                    // 設定ファイル
|-- .gitignore
|-- README.md
|-- composer
|-- composer.json
|-- composer.lock
`-- prepros.config                  // 設定ファイル
```

## apps/User ディレクトリについて
apps/User ディレクトリは apps/Core のファイルを変更したい場合や独自の処理、新しいファイルを作成したい場合に使用します。  
基本的なルールを、Core のコントローラーの変更を例に説明します。このルールは、コントローラー、モデル、ライブラリ、ヘルパー共に基本的に共通です（ファイル名、namespace 名、クラス名のルール）。  
  
例えば、あなたが 404 ページの処理を変更したいとします。この場合、apps/Core/Controller/Notfound404.php に変更を加えたいので、apps/User/Controller/UserNotfound404.php というファイル名で新しくファイルを作成します（今回は説明用に作成済）。  ファイル名の先頭には必ず User を追加してください。

次に新しく作成したファイルを開き、ファイルの先頭に PHP の namespace を追加します。今回作成した UserNotfound404.php はコントローラーなので、ユーザーのコントローラーという意味で ShaChiPoCo\User\Controller を記述します。

```
<?php
namespace ShaChiPoCo\User\Controller;

```

次に Core の Controller を継承して使用するため、use 宣言と extends で Core の Notfound404 を継承した UserNotfound404 クラスを作成します。クラス名はファイル名と同じ名前にしてください。

```
<?php
namespace ShaChiPoCo\User\Controller;

use ShaChiPoCo\Core\Controller;

class UserNotfound404 extends Controller {
    /**
     * Class constructor.
     *
     * @return    void
     */
    public function __construct() {
        parent::__construct();
    }
}
```

これでファイルの作成は完了です。あとは、継承元と同じメソッド名を作成して自由に変更を加えてください。
