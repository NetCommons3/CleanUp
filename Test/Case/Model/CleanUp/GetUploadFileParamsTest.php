<?php
/**
 * CleanUp::getUploadFileParams()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpModelTestCase', 'CleanUp.TestSuite');

/**
 * CleanUp::getUploadFileParams()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpGetUploadFileParamsTest extends CleanUpModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
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
	protected $_methodName = 'getUploadFileParams';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * getUploadFileParams()のテスト プラグイン対象:announcements
 *
 * @return void
 */
	public function testGetUploadFileParamsAnnouncements() {
		$model = $this->_modelName;
		//$methodName = $this->_methodName;

		//テストデータ

		$data['CleanUp']['plugin_key'] = [
			'announcements'
		];

		// チェック対象プラグイン
		/* @see Cleanup::findCleanUpsAndPlugin() */
		$cleanUps = $this->$model->findCleanUpsAndPlugin($data);
		//var_dump($cleanUps);

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//
		//テスト実施
		//
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUps[0]);

		//チェック
		//var_export($params);
		//var_export($params['joins'][0]);
		$this->assertEquals([$params['joins'][0]['table'], $params['joins'][0]['type']],
			['blocks', 'left'],
			'プラグイン対象:announcementsはblockテーブルと結合条件を含む想定');
	}

}
