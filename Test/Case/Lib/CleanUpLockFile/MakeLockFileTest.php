<?php
/**
 * CleanUpLockFile::makeLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpLockFile', 'CleanUp.Lib');

/**
 * CleanUpLockFile::makeLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpExec
 */
class CleanUpLibCleanUpLockFileMakeLockFileTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * makeLockFile()のテスト
 *
 * @return void
 * @see CleanUpCakeTestCase::setUp()  ロックファイルの出力先をtestに変更
 * @see CleanUpCakeTestCase::tearDown()  テスト後に必ずロックファイルあってもなくても削除する
 */
	public function testMakeLockFile() {
		//テスト実施
		CleanUpLockFile::makeLockFile();

		//チェック
		$this->assertTrue(CleanUpLockFile::isLockFile(), 'ロックファイルが存在ありtrueの想定');
	}

}
