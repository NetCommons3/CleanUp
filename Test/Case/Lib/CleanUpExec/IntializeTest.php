<?php
/**
 * CleanUpExec::initialize()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpExec', 'CleanUp.Lib');

/**
 * CleanUpExec::initialize()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpExec
 */
class CleanUpUtilityCleanUpUtilityiIntializeTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * initialize()のテスト
 *
 * @return void
 */
	public function testInitialize() {
		//テスト実施
		// CakeLog::initialize()を実行してるだけなので、ロジックが通る事を確認
		CleanUpExec::initialize();

		//チェック
		$this->assertTrue(true);
	}

}
