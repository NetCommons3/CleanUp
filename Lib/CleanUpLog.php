<?php
/**
 * ファイルクリーンアップ ログ出力ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * ファイルクリーンアップ ログ出力ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Lib
 */
class CleanUpLog {

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

/**
 * ログの内容
 *
 * @param int $logFileNo ログ番号
 * @return string ログの内容
 */
	public static function getLog($logFileNo = 0) {
		if ($logFileNo == 0) {
			$logFile = self::LOG_FILE_NAME;
		} else {
			$logFile = self::LOG_FILE_NAME . '.' . $logFileNo;
		}
		$logPath = LOGS . 'cleanup' . DS . $logFile;

		$cleanUpLog = '';
		if (file_exists($logPath)) {
			$cleanUpLog = file_get_contents($logPath);
		} else {
			$cleanUpLog = __d('clean_up', 'None.');
		}
		return $cleanUpLog;
	}

/**
 * ログファイル名 ゲット
 *
 * @return array ログファイル名
 */
	public static function getLogFileNames() {
		//インスタンスを作成
		$dir = new Folder(LOGS);
		$files = $dir->read();
		$logFileNames = [];
		foreach ($files[1] as $file) {
			if (strpos($file, self::LOG_FILE_NAME) !== false) {
				$logFileNames[] = $file;
			}
		}

		// 空の場合セット
		if (empty($logFileNames)) {
			$logFileNames[] = self::LOG_FILE_NAME;
		}
		return $logFileNames;
	}

}
