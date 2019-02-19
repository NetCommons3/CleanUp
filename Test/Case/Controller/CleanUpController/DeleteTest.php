<?php
/**
 * CleanUpController::delete()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpControllerTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpTestUtil', 'CleanUp.Test/Case');

/**
 * CleanUpController::delete()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Controller\CleanUpController
 */
class CleanUpControllerDeleteTest extends CleanUpControllerTestCase {

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
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'clean_up';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * delete()アクションのGetリクエストテスト
 *
 * @return void
 * @see CleanUpController::delete()
 */
	public function testDeleteGet() {
		//テスト実行
		$this->_testGetAction(
			array('action' => 'delete'),
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		//var_export($this->view);
	}

/**
 * delete()アクションのAjaxリクエストテスト
 *
 * @return void
 * @see CleanUpController::delete()
 */
	public function testDeleteAjax() {
		//テスト実行
		$this->_testGetAction(
			array('action' => 'delete'),
			array('method' => 'assertNotEmpty'), null, 'json'
		);

		//チェック
		//var_export($this->view);
	}

/**
 * delete()アクションのPOSTリクエストテスト
 *
 * @return void
 * @see CleanUpController::delete()
 */
	public function testDeletePost() {
		//データ生成
		$data['CleanUp']['plugin_key'] = [
			'unknown'
		];

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'delete'),
			null, 'view');

		//チェック
		//var_export($this->view);
		$this->assertNotEmpty($this->view, 'viewに何かしら値が返ってくる想定');
		$this->assertEmpty($this->controller->validationErrors, 'validationErrorはない想定');
	}

/**
 * delete()アクションのValidationErrorテスト
 *
 * @return void
 * @see CleanUpController::delete()
 */
	public function testDeletePostValidationError() {
		//データ生成
		$data['CleanUp']['plugin_key'] = [];

		//テスト実行
		$this->_testPostAction('post', $data, array('action' => 'delete'),
			null, 'view');

		//チェック
		//var_export($this->view);
		//var_export($this->controller->validationErrors);
		$message = __d('net_commons', 'Please input %s.',
			__d('clean_up', 'Plugin'));

		$this->assertTextContains($message,
			$this->controller->validationErrors['plugin_key'][0]);
	}

}
