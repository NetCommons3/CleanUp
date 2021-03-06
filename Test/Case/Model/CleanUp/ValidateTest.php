<?php
/**
 * CleanUp::validate()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpValidateTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpLockFile', 'CleanUp.Lib');

/**
 * CleanUp::validate()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpValidateTest extends CleanUpValidateTestCase {

/**
 * Fixtures
 *
 * @var array
 * @see NetCommonsCakeTestCase::$_defaultFixtures 大量のFixturesを一部にする
 */
	protected $_defaultFixtures = array(
		'plugin.m17n.language',
	);

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
	protected $_methodName = 'validates';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// ロックファイルの出力先をtestに変更
		CleanUpLockFile::$cacheKey = 'CleanUpTest.lock';
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		// テスト後に必ずロックファイルあってもなくても削除する
		CleanUpLockFile::deleteLockFile();

		parent::tearDown();
	}

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ(省略可)
 *
 * @return array テストデータ
 * @see NetCommonsValidateTest::testValidationError() このテストから呼び出される
 * @see CleanUp::beforeValidate() テスト対象
 */
	public function dataProviderValidationError() {
		return [
			'1.plugin_key空エラー' => [
				'data' => [],
				'field' => 'plugin_key',
				'value' => [],	//data[model名][field] = value
				'message' => __d('net_commons', 'Please input %s.',
					__d('clean_up', 'Plugin'))
			],
			'2.エラーなし' => [
				'data' => [],
				'field' => 'plugin_key',
				'value' => ['announcements'],	//data[model名][field] = value
				'message' => true
			],
		];
	}

/**
 * Validatesのテスト
 *
 * @param array $data 登録データ
 * @param string $field フィールド名
 * @param string $value セットする値
 * @param string $message エラーメッセージ
 * @param array $overwrite 上書きするデータ
 * @dataProvider dataProviderValidationError
 * @return void
 */
	public function testValidationError($data, $field, $value, $message, $overwrite = array()) {
		parent::testValidationError($data, $field, $value, $message, $overwrite);
	}

/**
 * Validates ロック中エラー
 *
 * @return void
 */
	public function testValidationErrorLock() {
		// 入力値は正常
		$data['CleanUp']['plugin_key'] = ['announcements'];
		// ロックファイル作成 = ロック中
		CleanUpLockFile::makeLockFile();

		//validate処理実行
		$model = $this->_modelName;
		$this->$model->set($data);
		$result = $this->$model->validates();

		// チェック
		// ロック中は実行できないため、エラー
		$this->assertFalse($result);
		//var_export($this->$model->validationErrors);
	}

}
