<?php
/**
 * CleanUpLib::isLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpLib::isLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpLib
 */
class CleanUpUtilityCleanUpUtilityIsLockFileTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * isLockFile() ロックファイルあり
 *
 * @return void
 * @see CleanUpCakeTestCase::setUp()  ロックファイルの出力先をtestに変更
 * @see CleanUpCakeTestCase::tearDown()  テスト後に必ずロックファイルあってもなくても削除する
 */
	public function testIsLockFile() {
		//データ生成
		CleanUpLib::makeLockFile();

		//テスト実施
		//チェック
		$this->assertTrue(CleanUpLib::isLockFile(), 'ロックファイルが存在ありtrueの想定');
	}

/**
 * isLockFile() ロックファイルなし
 *
 * @return void
 * @see CleanUpCakeTestCase::setUp()  ロックファイルの出力先をtestに変更
 * @see CleanUpCakeTestCase::tearDown()  テスト後に必ずロックファイルあってもなくても削除する
 */
	public function testIsLockFileCannotFile() {
		//テスト実施
		//チェック
		$this->assertFalse(CleanUpLib::isLockFile(), 'ロックファイルがなくfalseの想定');
	}

}
