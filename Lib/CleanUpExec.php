<?php
/**
 * ファイルクリーンアップ ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpLockFile', 'CleanUp.Lib');
App::uses('CleanUpLog', 'CleanUp.Lib');

/**
 * ファイルクリーンアップ ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Lib
 * @see MailSend よりコピー
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class CleanUpExec {

/**
 * 自作のstatic initialize<br />
 * 当クラス最下部で呼び出してる
 *
 * @return void
 * @deprecated 廃止予定
 */
	public static function initialize() {
		CleanUpLockFile::initialize();
	}

/**
 * ファイルクリーンアップ呼び出し
 *
 * @param array $data received post data. ['CleanUp']['plugin_key'][] = 'announcements'
 * @return void
 */
	public static function cleanUp($data) {
		$plugins = implode(' ', $data['CleanUp']['plugin_key']);

		// バックグラウンドでファイルクリーンアップ
		// コマンド例) Console/cake clean_up.clean_up clean_up announcements blogs
		self::execInBackground(APP . 'Console' . DS . 'cake clean_up.clean_up clean_up ' . $plugins);
	}

/**
 * バックグラウンド実行
 *
 * @param string $cmd コマンド
 * @return void
 */
	public static function execInBackground($cmd) {
		if (self::isWindows()) {
			// Windowsの場合
			pclose(popen('cd ' . APP . ' && start /B ' . $cmd, 'r'));
		} else {
			// Linuxの場合
			// logrotate問題対応 http://dqn.sakusakutto.jp/2012/08/php_exec_nohup_background.html
			exec('nohup ' . $cmd . ' > /dev/null &');
		}
	}

/**
 * 動作しているOS がWindows かどうかを返す。
 *
 * @return bool
 */
	public static function isWindows() {
		if (DIRECTORY_SEPARATOR == '\\') {
			return true;
		}
		return false;
	}

/**
 * ロックファイルの作成と時刻の書き込み。バッチ実行開始時
 *
 * @return void
 * @deprecated
 */
	public static function makeLockFile() {
		CleanUpLockFile::makeLockFile();
	}

/**
 * ロックファイルの削除。バッチ終了時
 *
 * @return bool true:削除|false:ファイルなし
 * @deprecated
 */
	public static function deleteLockFile() {
		return CleanUpLockFile::deleteLockFile();
	}

/**
 * ロックファイルの削除とログ出力設定。ロックファイル強制削除用
 *
 * @return bool true:削除|false:ファイルなし
 * @deprecated
 */
	public static function deleteLockFileAndSetupLog() {
		return CleanUpLockFile::deleteLockFileAndSetupLog();
	}

/**
 * ロックファイルの存在確認
 *
 * @return bool true:ロックあり|false:ロックなし
 * @deprecated
 */
	public static function isLockFile() {
		return CleanUpLockFile::isLockFile();
	}

/**
 * ロックファイルの読み込み
 *
 * @return string ファイルクリーンアップ開始時刻
 * @deprecated
 */
	public static function readLockFile() {
		return CleanUpLockFile::readLockFile();
	}

/**
 * ログ開始時のタイムゾーン変更<br />
 * タイムゾーンを一時的に変更。ログ出力時間を例えば日本時間に。
 *
 * @return string 日時
 * @deprecated
 */
	public static function startLogTimezone() {
		return CleanUpLog::startLogTimezone();
	}

/**
 * ログ終了時にタイムゾーン戻す
 *
 * @param string $timezone 日時
 * @return void
 * @deprecated
 */
	public static function endLogTimezone($timezone) {
		CleanUpLog::endLogTimezone($timezone);
	}

/**
 * Setup log
 *
 * @return void
 * @see Nc2ToNc3BaseBehavior::setup() よりコピー
 * @see https://book.cakephp.org/2.0/ja/core-libraries/logging.html#id2 ログストリームの作成と設定, size,rotateのデフォルト値
 * @deprecated
 */
	public static function setupLog() {
		CleanUpLog::setupLog();
	}

/**
 * ログの内容
 *
 * @param int $logFileNo ログ番号
 * @return string ログの内容
 * @deprecated
 */
	public static function getLog($logFileNo = 0) {
		return CleanUpLog::getLog($logFileNo);
	}

/**
 * ログファイル名 ゲット
 *
 * @return array ログファイル名
 * @deprecated
 */
	public static function getLogFileNames() {
		return CleanUpLog::getLogFileNames();
	}

}

// 自作のstatic initialize
CleanUpExec::initialize();
