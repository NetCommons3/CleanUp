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

/* @see https://github.com/NetCommons3/NetCommons3/blob/6451c4b5ee2a17c74ea65eb7e4d757d148cd1835/app/Config/core.php#L368 */
//
// 基本的にcore.phpで定義されたキャッシュ方式を踏襲する
// キャッシュファイルのprefixの一部(cake_core_)をCleanUpで使用している特別Prefixに置き換えて使用する
//
// 全てを置き換えないようにしているのは、
// 複数サーバーが同一箇所のキャッシュ場所を使用した場合も
// コンタミしないように工夫されたprefixを消さないようにしているため
// デフォルトでは頭に"myapp_"がついています
// ※NC3ではキャッシュファイル名を「固定」にしてはいけないということ
//   必ずcore.phpで用いられるprefixを意識しないといけない
//
$cacheSetting = Cache::settings('_cake_core_');
$cleanUpLockFileSetting = array_merge($cacheSetting, [
	'prefix' => preg_replace('/cake_core_/', 'netcommons_clean_up_lock_', $cacheSetting['prefix']),
	'duration' => '+7 days'
]);
Cache::config(CleanUpLockFile::$cacheConfigName, $cleanUpLockFileSetting);

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
