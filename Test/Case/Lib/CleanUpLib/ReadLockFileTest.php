<?php
/**
 * CleanUpLib::readLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpLib::readLockFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpLib
 */
class CleanUpUtilityCleanUpUtilityReadLockFileTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * readLockFile() ファイルあり
 *
 * @return void
 */
	public function testReadLockFile() {
		//データ生成
		CleanUpLib::makeLockFile();

		//テスト実施
		$reseult = CleanUpLib::readLockFile();

		//チェック
		$this->assertNotEmpty($reseult, 'ロックファイルに書かれた値が取得できる想定');
	}

/**
 * readLockFile() ファイルなし
 *
 * @return void
 */
	public function testReadLockFileCannotFile() {
		//テスト実施
		$reseult = CleanUpLib::readLockFile();

		//チェック
		$this->assertEmpty($reseult, 'ロックファイルなしで空文字の想定');
	}
}
