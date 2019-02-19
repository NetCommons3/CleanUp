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
 * getUploadFileParams()のテスト プラグイン対象:unknow
 *
 * @return void
 */
	public function testGetUploadFileParamsUnknow() {
		$model = $this->_modelName;
		//$methodName = $this->_methodName;

		//テストデータ

		// チェック対象プラグイン:unknow
		/* @see Cleanup::getUnknowCleanUp() */
		$cleanUp = $this->$model->getUnknowCleanUp();

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//
		//テスト実施
		//
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUp);

		//チェック
		//var_export($params);
		//var_export($params['conditions']['OR'][0]);
		$this->assertEquals($params['conditions']['OR'][0], ['Block.id' => null],
			'プラグイン対象:unknowはblock_id = nullの条件を含む想定');
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
		/* @see Cleanup::getCleanUpsAndPlugin() */
		$cleanUps = $this->$model->getCleanUpsAndPlugin($data);
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
		//var_export($params['conditions']['OR'][0]);
		$this->assertNotEquals($params['conditions']['OR'][0], ['Block.id' => null],
			'プラグイン対象:announcementsはblock_id = nullの条件を含まない想定');
	}

}
