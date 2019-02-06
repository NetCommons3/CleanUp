<?php
/**
 * CleanUpUtility::deleteLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpUtility::deleteLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpUtility
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
		CleanUpUtility::makeLockFile();

		//テスト実施
		//チェック
		$this->assertTrue(CleanUpUtility::deleteLockFile(), 'ロックファイルが削除できtrueの想定');
	}

/**
 * deleteLockFile() ロックファイルなし
 *
 * @return void
 */
	public function testDeleteLockFileCannotFile() {
		//テスト実施
		//チェック
		$this->assertFalse(CleanUpUtility::deleteLockFile(), 'ロックファイルがなくfalseの想定');
	}

}
