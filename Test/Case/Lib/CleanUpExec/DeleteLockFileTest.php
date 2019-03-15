<?php
/**
 * CleanUpExec::deleteLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpExec::deleteLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpExec
 */
class CleanUpUtilityCleanUpUtilityDeleteLockFileTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * deleteLockFile() ロックファイルあり
 *
 * @return void
 */
	public function testDeleteLockFile() {
		//データ生成
		CleanUpExec::makeLockFile();

		//テスト実施
		//チェック
		$this->assertTrue(CleanUpExec::deleteLockFile(), 'ロックファイルが削除できtrueの想定');
	}

/**
 * deleteLockFile() ロックファイルなし
 *
 * @return void
 */
	public function testDeleteLockFileCannotFile() {
		//テスト実施
		//チェック
		$this->assertFalse(CleanUpExec::deleteLockFile(), 'ロックファイルがなくfalseの想定');
	}

}
