<?php
/**
 * ファイルクリーンアップ ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsTime', 'NetCommons.Utility');

/**
 * ファイルクリーンアップ ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Lib
 */
class CleanUpLockFile {

/**
 * ロックファイルパス
 *
 * @var string
 */
	public static $lockFilePath = '';

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
		CleanUpExec::setupLog();
		// ログ開始時のタイムゾーン変更
		$timezone = CleanUpExec::startLogTimezone();

		CakeLog::info(__d('clean_up',
			'Start forcibly delete lock file processing.'), ['CleanUp']);
		$isDelete = self::deleteLockFile();

		// ログ終了時にタイムゾーン戻す
		CleanUpExec::endLogTimezone($timezone);
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

}

// 自作のstatic initialize
CleanUpLockFile::initialize();
