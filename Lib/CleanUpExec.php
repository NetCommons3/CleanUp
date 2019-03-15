<?php
/**
 * ファイルクリーンアップ ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * ファイルクリーンアップ ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Lib
 * @see MailSend よりコピー
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
}
