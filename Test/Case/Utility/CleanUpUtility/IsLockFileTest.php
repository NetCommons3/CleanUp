<?php
/**
 * CleanUpUtility::isLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpUtility::isLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpUtility
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
		//テスト実施
		CleanUpUtility::makeLockFile();

		//チェック
		$this->assertTrue(CleanUpUtility::isLockFile(), 'ロックファイルが存在ありtrueの想定');
	}

/**
 * isLockFile() ロックファイルなし
 *
 * @return void
 * @see CleanUpCakeTestCase::setUp()  ロックファイルの出力先をtestに変更
 * @see CleanUpCakeTestCase::tearDown()  テスト後に必ずロックファイルあってもなくても削除する
 */
	public function testIsLockFileCannotFile() {
		//チェック
		$this->assertFalse(CleanUpUtility::isLockFile(), 'ロックファイルがなくfalseの想定');
	}

}