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
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class CleanUpUtility {

/**
 * ロックファイルパス
 *
 * @var string
 */
	public static $lockFilePath = '';

/**
 * ロガーキー
 *
 * @var string
 */
	const LOGGER_KEY = 'CleanUpFile';

/**
 * ログファイル名
 *
 * @var string
 */
	const LOG_FILE_NAME = 'CleanUp.log';

/**
 * タイムゾーン。ログ出力時間 変更用
 *
 * @var string
 */
	const TIMEZONE = 'Asia/Tokyo';

/**
 * 自作のstatic initialize<br />
 * 当クラス最下部で呼び出してる
 *
 * @return void
 */
	public static function initialize() {
		// php5.4, 5.5対応 staticのメンバ変数に . 連結するとsyntax error
		// https://travis-ci.org/NetCommons3/CleanUp/jobs/492013244#L866
		self::$lockFilePath = TMP . 'CleanUp.lock';
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
 */
	public static function makeLockFile() {
		touch(self::$lockFilePath);

		//時刻をロックファイルに書き込む
		$now = NetCommonsTime::getNowDatetime();
		file_put_contents(self::$lockFilePath, $now);
		CakeLog::info(__d('clean_up', 'Created a lock file.'), ['CleanUp']);
	}

/**
 * ロックファイルの削除。バッチ終了時
 *
 * @return bool true:削除|false:ファイルなし
 */
	public static function deleteLockFile() {
		if (file_exists(self::$lockFilePath)) {
			unlink(self::$lockFilePath);
			CakeLog::info(__d('clean_up', 'Lock file was deleted.'), ['CleanUp']);
			return true;
		}
		CakeLog::info(__d('clean_up', 'No lock file.'), ['CleanUp']);
		return false;
	}

/**
 * ロックファイルの削除とログ出力設定。ロックファイル強制削除用
 *
 * @return bool true:削除|false:ファイルなし
 */
	public static function deleteLockFileAndSetupLog() {
		self::setupLog();
		// ログ開始時のタイムゾーン変更
		$timezone = self::startLogTimezone();

		CakeLog::info(__d('clean_up',
			'Start forcibly delete lock file processing.'), ['CleanUp']);
		$isDelete = self::deleteLockFile();

		// ログ終了時にタイムゾーン戻す
		self::endLogTimezone($timezone);
		return $isDelete;
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
			//return file_get_contents(self::$lockFilePath);
			$cleanUpStart = file_get_contents(self::$lockFilePath);
			$cleanUpStart = date('m/d G:i', strtotime($cleanUpStart));
			return $cleanUpStart;
		}
		return '';
	}

/**
 * ログ開始時のタイムゾーン変更<br />
 * タイムゾーンを一時的に変更。ログ出力時間を例えば日本時間に。
 *
 * @return string 日時
 */
	public static function startLogTimezone() {
		$timezone = date_default_timezone_get();
		date_default_timezone_set(self::TIMEZONE);
		return $timezone;
	}

/**
 * ログ終了時にタイムゾーン戻す
 *
 * @param string $timezone 日時
 * @return void
 */
	public static function endLogTimezone($timezone) {
		date_default_timezone_set($timezone);
	}

/**
 * Setup log
 *
 * @return void
 * @see Nc2ToNc3BaseBehavior::setup() よりコピー
 * @see https://book.cakephp.org/2.0/ja/core-libraries/logging.html#id2 ログストリームの作成と設定, size,rotateのデフォルト値
 */
	public static function setupLog() {
		// ログ出力フォルダ作成
		$logPath = LOGS . 'cleanup' . DS;
		if (! file_exists($logPath)) {
			$folder = new Folder();
			$folder->create($logPath);
		}

		// CakeLog::writeでファイルとコンソールに出力していた。
		// Consoleに出力すると<tag></tag>で囲われ見辛い。
		// @see https://github.com/cakephp/cakephp/blob/2.9.4/lib/Cake/Console/ConsoleOutput.php#L230-L241
		// CakeLog::infoをよびだし、debug.logとCleanUp.logの両方出力するようにした。
		CakeLog::config(
			self::LOGGER_KEY,
			[
				'engine' => 'FileLog',
				'types' => ['info'],
				'scopes' => ['CleanUp'],
				'file' => self::LOG_FILE_NAME,
				'size ' => '10MB',	// デフォルト値 10MB
				'rotate ' => 20,	// デフォルト値 10
				'path' => $logPath,
			]
		);
	}

}

// 自作のstatic initialize
CleanUpUtility::initialize();
