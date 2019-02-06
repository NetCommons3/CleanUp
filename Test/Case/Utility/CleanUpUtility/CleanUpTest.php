<?php
/**
 * CleanUpUtility::cleanUp()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpUtility', 'CleanUp.Utility');

/**
 * CleanUpUtility::cleanUp()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpUtility
 */
class CleanUpUtilityCleanUpUtilityCleanUpTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
	);

/**
 * cleanUp()のテスト
 *
 * @return void
 */
	public function testCleanUp() {
		//データ生成
		$data['CleanUp']['plugin_key'] = [
			'announcements',
			'blogs'
		];

		//テスト実施
		// execを実行してるだけなので、ロジックが通る事を確認
		CleanUpUtility::cleanUp($data);

		//チェック
		$this->assertTrue(true);
	}

}
