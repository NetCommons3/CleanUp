# CleanUp
CleanUp for NetCommons3

[![Build Status](https://api.travis-ci.org/NetCommons3/CleanUp.svg?branch=master)](https://travis-ci.org/NetCommons3/CleanUp)
[![Coverage Status](https://coveralls.io/repos/NetCommons3/CleanUp/badge.svg?branch=master)](https://coveralls.io/r/NetCommons3/CleanUp?branch=master)

### [phpdoc](https://netcommons3.github.io/NetCommons3Docs/phpdoc/CleanUp/)

* [画面](#画面)
* [コンソール](#コンソール)

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
