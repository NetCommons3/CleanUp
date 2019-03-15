<?php
/**
 * CleanUpLog::setupLog()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpLog', 'CleanUp.Lib');

/**
 * CleanUpLog::setupLog()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpExec
 */
class CleanUpUtilityCleanUpUtilitySetupLogTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * setupLog()のテスト
 *
 * @return void
 */
	public function testSetupLog() {
		//テスト実施
		// CakeLog::config()を実行してるだけなので、ロジックが通る事を確認
		CleanUpLog::setupLog();

		//チェック
		$this->assertTrue(true);
	}

}
