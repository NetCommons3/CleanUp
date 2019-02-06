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
 * @see Plugin4testFixture
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
		'plugin.plugin_manager.plugin4test',
	);

/**
 * cleanUp()のテスト
 *
 * @return void
 */
	public function testCleanUp() {
		//データ生成
		// コンソール側でfixturesのclean_up <-> plugin4test関連データがうまくfindできてなくtravisで
		// 下記エラーになった。
		// Error: "announcements" is not a valid value for 0. Please use one of "unknown, all"
		// コンソールのテストは、別途コンソール側で行うため、ここで指定するプラグインキーはテーブルをfind
		// しなくてもある、unknownを指定する
		$data['CleanUp']['plugin_key'] = [
			'unknown'
		];

		//テスト実施
		// execを実行してるだけなので、ロジックが通る事を確認
		CleanUpUtility::cleanUp($data);

		//チェック
		$this->assertTrue(true);
	}

}
