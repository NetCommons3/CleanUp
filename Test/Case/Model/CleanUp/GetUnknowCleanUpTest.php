<?php
/**
 * CleanUp::getUnknowCleanUp()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpGetTest', 'CleanUp.TestSuite');

/**
 * CleanUp::getUnknowCleanUp()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpGetUnknowCleanUpTest extends CleanUpGetTest {

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
	protected $_methodName = 'getUnknowCleanUp';

/**
 * getUnknowCleanUp()のテスト
 *
 * @return void
 * @see CleanUp::getUnknowCleanUp()
 */
	public function testGetUnknowCleanUp() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テスト実施
		$result = $this->$model->$methodName();

		//チェック
		//var_export($result);
		$this->assertArrayHasKey('CleanUp', $result,
			'CleanupのUnknowデータにはCleanUpのarray-keyを含む想定');
		$this->assertArrayHasKey('Plugin', $result,
			'CleanupのUnknowデータにはPluginのarray-keyを含む想定');
	}

}
