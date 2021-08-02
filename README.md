CleanUp
=============

[![Tests Status](https://github.com/NetCommons3/CleanUp/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/NetCommons3/CleanUp/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/NetCommons3/CleanUp/badge.svg?branch=master)](https://coveralls.io/r/NetCommons3/CleanUp?branch=master)
[![Stable Version](https://img.shields.io/packagist/v/netcommons/clean-up.svg?label=stable)](https://packagist.org/packages/netcommons/clean-up)

### [phpdoc](https://netcommons3.github.io/NetCommons3Docs/phpdoc/CleanUp/)

* [画面](#画面)
* [コンソール](#コンソール)
* [Tips](#Tips)
  * [削除する拡張子を指定する](#削除する拡張子を指定する)

### 画面

![ファイルクリーンアップ画面](https://github.com/NetCommons3/CleanUp/wiki/images/cleanup.png)

### コンソール

```
--- コマンド

$ cd␣（インストールディレクトリ）/app
$ Console/cake clean_up.clean_up clean_up --help

--- 実行結果

Welcome to CakePHP v2.10.16 Console
---------------------------------------------------------------
App : app
Path: /var/www/app/app/
---------------------------------------------------------------
ファイルクリーンアップ

[コマンド]
cake clean_up.clean_up clean_up [arguments]: ファイルクリーンアップ
cake clean_up.clean_up unlock: 実行中ロックファイルの強制削除

使用されていないアップロードファイルを削除します。対象のplugin_keyを指定してください。
全ての引数はplugin_keyとして処理します。ファイルクリーンアップを
実行する前に、こちらを参考に必ずバックアップして、
いつでもリストアできるようにしてから実行してください。
https://www.netcommons.org/NetCommons3/download#!#frame-362

実行結果は下記にログ出力されます。
/var/www/app/app/tmp/logs/cleanup/CleanUp.log


Usage:
cake clean_up.clean_up [-h] [-v] [-q] [arguments]

Options:

--help, -h     Display this help.
--verbose, -v  Enable verbose output.
--quiet, -q    Enable quiet output.

Arguments:

0   クリーンアップする対象のプラグインキー。
    [通常以外で指定できるプラグインキー]
    all: 全てのプラグイン (optional) (choices:
    announcements|bbses|blogs|calendars|circular_notices|faqs|multidatabases|questionnaires|questionnaires|quizzes|quizzes|registrations|registrations|reservations|reservations|tasks|videos|all)
1    (optional) (choices:
    announcements|bbses|blogs|calendars|circular_notices|faqs|multidatabases|questionnaires|questionnaires|quizzes|quizzes|registrations|registrations|reservations|reservations|tasks|videos|all)
(省略)
18   (optional) (choices:
    announcements|bbses|blogs|calendars|circular_notices|faqs|multidatabases|questionnaires|questionnaires|quizzes|quizzes|registrations|registrations|reservations|reservations|tasks|videos|all)
```

### Tips
#### 削除する拡張子を指定する

CleanUp/Model/CleanUp.phpの[下記箇所](https://github.com/NetCommons3/CleanUp/blob/243562e8d5da31b3c828c153bdcfa519657b5d89/Model/CleanUp.php#L68)を変更する事で、削除する拡張子を指定できます。

```php
/**
 * 削除する拡張子<br />
 * (例 jpg、複数はカンマ区切り、空なら全ての拡張子が対象)
 *
 * @var string
 */
	public $deleteExtension = '';
```
