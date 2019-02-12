<?php
/**
 * CleanUp::getCleanUpsAndPlugin()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpGetTest', 'CleanUp.TestSuite');

/**
 * CleanUp::getCleanUpsAndPlugin()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpGetCleanUpsAndPluginTest extends CleanUpGetTest {

/**
 * Fixtures
 *
 * @var array
 * @see CleanUpGetTest::$__fixtures ここでget共通のfixtures設定済み
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'CleanUp';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getCleanUpsAndPlugin';

/**
 * getCleanUpsAndPlugin()のCleanup対象プラグインを全件取得テスト
 *
 * @return void
 * @see CleanUp::getCleanUpsAndPlugin()
 */
	public function testGetCleanUpsAndPlugin() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$data = null;
		// 言語：日本語
		Current::write('Language.id', '2');

		//テスト実施
		$result = $this->$model->$methodName($data);

		//チェック
		//var_export($result);
		$this->assertNotEmpty($result, 'Cleanup対象プラグインを全件取得できる想定');
	}

/**
 * getCleanUpsAndPlugin()のCleanup対象プラグインを1取得テスト
 *
 * @return void
 * @see CleanUp::getCleanUpsAndPlugin()
 */
	public function testGetCleanUpsAndPluginCount() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$data['CleanUp']['plugin_key'] = ['announcements'];
		// 言語：日本語
		Current::write('Language.id', '2');

		//テスト実施
		$result = $this->$model->$methodName($data);

		// debug
		//$this->Plugin = ClassRegistry::init('PluginManager.Plugin', true);
		//var_export($this->$model->find('all'));
		//var_export($this->Plugin->find('all'));
		//var_export($result);

		//チェック
		//var_export($result);
		$this->assertCount(1, $result, 'Cleanup対象プラグインを1件できる想定');
	}

/**
 * getCleanUpsAndPlugin()のCleanup対象プラグインを複数取得テスト
 *
 * @return void
 * @see CleanUp::getCleanUpsAndPlugin()
 */
	public function testGetCleanUpsAndPluginCounts() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$data['CleanUp']['plugin_key'] = ['announcements', 'bbses'];
		// 言語：日本語
		Current::write('Language.id', '2');

		//テスト実施
		$result = $this->$model->$methodName($data);

		//チェック
		//var_export($result);
		$this->assertCount(2, $result, 'Cleanup対象プラグインを複数件(2件)できる想定');
	}

}
