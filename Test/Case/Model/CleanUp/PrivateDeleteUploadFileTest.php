<?php
/**
 * CleanUp::__deleteUploadFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpModelTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpTestUtil', 'CleanUp.Test/Case');
App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * CleanUp::__deleteUploadFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpPrivateDeleteUploadFileTest extends CleanUpModelTestCase {

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
	protected $_methodName = '__deleteUploadFile';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * __deleteUploadFile()のテスト
 *
 * @return void
 * @throws ReflectionException
 */
	public function testDeleteUploadFile() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ
		/* @see NetCommonsCakeTestCase::$_defaultFixtures 'plugin.files.upload_file','plugin.files.upload_files_content', 読み込んでる
		 * @sse UploadFileFixture アップロードファイルのテストデータ. id=12, 13のwysiwygアップロードデータを利用
		 * @see UploadFilesContentFixture アップロードファイルのテストデータ */
		$uploadFile['UploadFile'] = (new UploadFileFixture())->records[11];
		//$data['UploadFile'] = (new UploadFileFixture())->records[12];

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//テスト実施
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFile)
		);

		//チェック
		//var_export($result);
		$this->assertTrue($result, 'ファイルとデータが削除されるためtrueが戻る想定');
	}

}
