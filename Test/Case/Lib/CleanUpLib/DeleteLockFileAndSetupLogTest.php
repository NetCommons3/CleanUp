<?php
/**
 * CleanUpLib::deleteLockFileAndSetupLog()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpLib::deleteLockFileAndSetupLog()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpLib
 */
class CleanUpUtilityCleanUpUtilityDeleteLockFileAndSetupLogTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * deleteLockFileAndSetupLog() ロックファイルあり
 *
 * @return void
 */
	public function testDeleteLockFileAndSetupLog() {
		//データ生成
		CleanUpLib::makeLockFile();

		//テスト実施
		//チェック
		$this->assertTrue(CleanUpLib::deleteLockFileAndSetupLog(), 'ロックファイルが削除できtrueの想定');
	}

}