<?php
/**
 * ファイルクリーンアップ ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Cache', 'Cache');
App::uses('NetCommonsTime', 'NetCommons.Utility');
App::uses('CleanUpLog', 'CleanUp.Lib');

/**
 * Configure the cache used for general framework caching. Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
$cacheSetting = Cache::settings('_cake_core_');
//$cacheSetting['prefix'] =
//	preg_replace('/cake_core_/', 'netcommons_clean_up_lock_', $cacheSetting['prefix']);
Cache::config(CleanUpLockFile::$cacheConfigName, array(
	'engine' => $cacheSetting['engine'],
	//'prefix' => $cacheSetting['prefix'],
	'prefix' => 'netcommons_clean_up_lock_',
	'path' => CACHE,
	'serialize' => $cacheSetting['serialize'],
	'duration' => '+7 days'
));

/**
 * ファイルクリーンアップ ライブラリ
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Lib
 */
class CleanUpLockFile {

/**
 * ロックファイルの設定名
 *
 * @var string
 */
	public static $cacheConfigName = 'netcommons_clean_up_lock';

/**
 * ロックファイルのキャッシュキー
 *
 * @var string
 */
	public static $cacheKey = 'CleanUp.lock';

/**
 * ロックファイルの作成と時刻の書き込み。バッチ実行開始時
 *
 * @return void
 */
	public static function makeLockFile() {
		//touch(self::$lockFilePath);

		//時刻をロックファイルに書き込む
		$now = NetCommonsTime::getNowDatetime();
		//file_put_contents(self::$lockFilePath, $now);
		//Cache::write('CleanUp.lock', $now, 'netcommons_clean_up_lock');
		Cache::write(self::$cacheKey, $now, self::$cacheConfigName);

		CakeLog::info(__d('clean_up', 'Created a lock file.'), ['CleanUp']);
	}

/**
 * ロックファイルの削除。バッチ終了時
 *
 * @return bool true:削除|false:ファイルなし
 */
	public static function deleteLockFile() {
		//if (file_exists(self::$lockFilePath)) {
		if (Cache::read(self::$cacheKey, self::$cacheConfigName)) {
			//unlink(self::$lockFilePath);
			Cache::delete(self::$cacheKey, self::$cacheConfigName);
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
		CleanUpLog::setupLog();
		// ログ開始時のタイムゾーン変更
		$timezone = CleanUpLog::startLogTimezone();

		CakeLog::info(__d('clean_up',
			'Start forcibly delete lock file processing.'), ['CleanUp']);
		$isDelete = self::deleteLockFile();

		// ログ終了時にタイムゾーン戻す
		CleanUpLog::endLogTimezone($timezone);
		return $isDelete;
	}

/**
 * ロックファイルの存在確認
 *
 * @return bool true:ロックあり|false:ロックなし
 */
	public static function isLockFile() {
		//if (file_exists(self::$lockFilePath)) {
		if (Cache::read(self::$cacheKey, self::$cacheConfigName)) {
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
		//if (file_exists(self::$lockFilePath)) {
		$cleanUpStart = Cache::read(self::$cacheKey, self::$cacheConfigName);
		if ($cleanUpStart) {
			//$cleanUpStart = file_get_contents(self::$lockFilePath);
			$cleanUpStart = date('m/d G:i', strtotime($cleanUpStart));
			return $cleanUpStart;
		}
		return '';
	}

}
