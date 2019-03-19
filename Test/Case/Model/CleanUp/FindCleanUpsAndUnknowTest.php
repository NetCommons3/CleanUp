<?php
/**
 * CleanUp::findCleanUpsAndUnknow()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpGetTest', 'CleanUp.TestSuite');

/**
 * CleanUp::findCleanUpsAndUnknow()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpFindCleanUpsAndUnknowTest extends CleanUpGetTest {

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
	protected $_methodName = 'findCleanUpsAndUnknow';

/**
 * findCleanUpsAndUnknow()のテスト
 *
 * @return void
 * @see CleanUp::findCleanUpsAndUnknow()
 */
	public function testFindCleanUpsAndUnknow() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$data = null;

		//テスト実施
		$results = $this->$model->$methodName($data);

		//チェック
		$isCheke = false;
		foreach ($results as $result) {
			if ($result['CleanUp']['plugin_key'] == 'unknown') {
				$isCheke = true;
			}
		}
		//var_export($results);
		$this->assertTrue($isCheke, '取得結果の[CleanUp][plugin_key]にunknownがある想定');
	}

}
