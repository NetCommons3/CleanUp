<?php
/**
 * CleanUp::fileCleanUp()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpModelTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpTestUtil', 'CleanUp.Test/Case');

/**
 * CleanUp::fileCleanUp()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpFileCleanUpTest extends CleanUpModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
		'plugin.clean_up.schema_migration',
		'plugin.clean_up.nc2_to_nc3_map',
		'plugin.plugin_manager.plugin4test',
	);

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
	protected $_methodName = 'fileCleanUp';

/**
 * fileCleanUp()のテスト
 *
 * @return void
 * @see CleanUp::fileCleanUp()
 */
	public function testFileCleanUp() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$data['CleanUp']['plugin_key'] = [
			'announcements'
		];

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		//テスト実施
		$result = $this->$model->$methodName($data);

		//チェック
		//var_export($result);
		$this->assertTrue($result, '正常処理されるためtrueが戻る想定');
	}

/**
 * fileCleanUp()のvalidateErrorテスト
 *
 * @return void
 * @see CleanUp::fileCleanUp()
 */
	public function testFileCleanUpError() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$data['CleanUp']['plugin_key'] = [];

		//テスト実施
		$result = $this->$model->$methodName($data);

		//チェック
		$this->assertFalse($result, 'validateErrorのためfalseが戻る想定');
	}
}
