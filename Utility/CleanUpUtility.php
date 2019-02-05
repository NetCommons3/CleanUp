<?php
/**
 * ファイルクリーンアップ Utility
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsTime', 'NetCommons.Utility');

/**
 * ファイルクリーンアップ Utility
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Utility
 * @see MailSend よりコピー
 */
class CleanUpUtility {

/**
 * ロックファイルパス
 *
 * @var string
 */
	public static $lockFilePath = ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'CleanUp.lock';
	//	public $lockFilePath = ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'logs' . DS . 'CleanUp.lock';

/**
 * ファイルクリーンアップ呼び出し
 *
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
 * ロックファイルの作成とプロセスIDの書き込み。バッチ実行開始時
 *
 * @return void
 */
	public static function makeLockFile() {
		touch(self::$lockFilePath);

		//時刻をロックファイルに書き込む
		$now = NetCommonsTime::getNowDatetime();
		file_put_contents(self::$lockFilePath, $now);
	}

/**
 * ロックファイルの削除。バッチ終了時
 *
 * @return bool true:削除|false:ファイルなし
 */
	public static function deleteLockFile() {
		if (file_exists(self::$lockFilePath)) {
			unlink(self::$lockFilePath);
			return true;
		}
		return false;
	}

/**
 * ロックファイルの存在確認
 *
 * @return bool true:ロックあり|false:ロックなし
 */
	public static function isLockFile() {
		if (file_exists(self::$lockFilePath)) {
			return true;
		}
		return false;
	}

/**
 * ロックファイルの読み込み
 *
 * @return string ファイルクリーンアップ開始時刻
 */
	public static function readLockFile() {
		if (file_exists(self::$lockFilePath)) {
			$cleanUpStart = file_get_contents(self::$lockFilePath);
			$NetCommonsTime = new NetCommonsTime();
			return $NetCommonsTime->toUserDatetime($cleanUpStart);
		}
		return '';
	}
}
