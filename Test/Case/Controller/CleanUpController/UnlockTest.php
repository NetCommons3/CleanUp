<?php
/**
 * CleanUpController::unlock()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpControllerTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpTestUtil', 'CleanUp.Test/Case');

/**
 * CleanUpController::unlock()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Controller\CleanUpController
 */
class CleanUpControllerUnlockTest extends CleanUpControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
		'plugin.clean_up.plugins_role_for_clean_up',
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
 * unlock()アクションのロックファイルありテスト
 *
 * @return void
 * @see CleanUpController::unlock()
 */
	public function testUnlockGetLocked() {
		// ロックファイル作成
		CleanUpExec::makeLockFile();

		//テスト実行
		$this->_testGetAction(
			array('action' => 'unlock'),
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		//var_export($this->view);
	}

/**
 * unlock()アクションのロックファイルなしテスト
 *
 * @return void
 * @see CleanUpController::unlock()
 */
	public function testUnlockGetNoLock() {
		//テスト実行
		$this->_testGetAction(
			array('action' => 'unlock'),
			array('method' => 'assertNotEmpty'), null, 'view'
		);

		//チェック
		//var_export($this->view);
		//$this->assertNotEmpty($this->view, 'viewに何かしら値が返ってくる想定');
	}

/**
 * unlock()アクションの例外テスト
 *
 * @return void
 * @see CleanUpController::unlock()
 * @see CleanUpControllerUnlockTest::_testNcAction() オーバーライトして$exception == 'BadRequestException'ならpostにしてる
 */
	public function testUnlockGetException() {
		//テスト実行
		$this->_testGetAction(
			array('action' => 'unlock'),
			array('method' => 'assertNotEmpty'), 'BadRequestException', 'view'
		);
	}

/**
 * Assert input tag
 *
 * ### $returnについて
 *  - viewFile: viewファイル名を戻す
 *  - json: JSONをでコードした配列を戻す
 *  - 上記以外: $this->testActionのreturnで指定した内容を戻す
 *
 * @param array $url URL配列
 * @param array $paramsOptions リクエストパラメータオプション
 * @param string|null $exception Exception
 * @param string $return testActionの実行後の結果
 * @return mixed
 */
	protected function _testNcAction($url = [], $paramsOptions = [],
									$exception = null, $return = 'view') {
		/* @see CleanUpControllerUnlockTest::testUnlockGetException 用にカスタマイズ */
		if ($exception == 'BadRequestException') {
			$paramsOptions = array('method' => 'post');
		}

		return parent::_testNcAction($url, $paramsOptions, $exception, $return);
	}

}
