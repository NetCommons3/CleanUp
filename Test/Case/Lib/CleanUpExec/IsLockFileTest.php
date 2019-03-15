<?php
/**
 * CleanUpExec::isLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpExec::isLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpExec
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
		CleanUpExec::makeLockFile();

		//テスト実施
		//チェック
		$this->assertTrue(CleanUpExec::isLockFile(), 'ロックファイルが存在ありtrueの想定');
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
		$this->assertFalse(CleanUpExec::isLockFile(), 'ロックファイルがなくfalseの想定');
	}

}
