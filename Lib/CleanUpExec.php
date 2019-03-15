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
