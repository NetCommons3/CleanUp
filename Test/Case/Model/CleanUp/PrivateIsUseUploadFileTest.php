<?php
/**
 * CleanUp::__isUseUploadFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpModelTestCase', 'CleanUp.TestSuite');
App::uses('UploadFileFixture', 'Files.Test/Fixture');
App::uses('CleanUpFixture', 'CleanUp.Test/Fixture');

/**
 * CleanUp::__isUseUploadFile()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpPrivateIsUseUploadFileTest extends CleanUpModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
		'plugin.clean_up.announcement_for_clean_up',
		'plugin.clean_up.block_for_clean_up',
		'plugin.clean_up.plugin_for_clean_up',
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
	protected $_methodName = '__isUseUploadFile';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * __isUseUploadFile()のファイル使ってるテスト
 *
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__isUseUploadFile()
 */
	public function testIsUseUploadFile() {
		// TODO 作成中
		//		* @param array $uploadFile UploadFile
		//		* @param array $cleanUp [CleanUp][...]
		// * @dataProvider dataProvider
		//public function testIsUseUploadFile($uploadFile, $cleanUp) {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ
		/* @see NetCommonsCakeTestCase::$_defaultFixtures 'plugin.files.upload_file','plugin.files.upload_files_content', 読み込んでる
		 * @sse UploadFileFixture アップロードファイルのテストデータ. id=12, 13のwysiwygアップロードデータを利用 */
		$uploadFile['UploadFile'] = (new UploadFileFixture())->records[11];
		//$data['UploadFile'] = (new UploadFileFixture())->records[12];

		// 'plugin_key' => 'announcements',
		$cleanUp['CleanUp'] = (new CleanUpFixture())->records[0];

		//テスト実施
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFile, $cleanUp)
		);

		//チェック
		//var_export($result);
	}

/**
 * __isUseUploadFile()のファイル使ってないテスト
 *
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__isUseUploadFile()
 */
	public function testIsUseUploadFileNotUse() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ
		/* @see NetCommonsCakeTestCase::$_defaultFixtures 'plugin.files.upload_file','plugin.files.upload_files_content', 読み込んでる
		 * @sse UploadFileFixture アップロードファイルのテストデータ. id=12, 13のwysiwygアップロードデータを利用 */
		$uploadFile['UploadFile'] = (new UploadFileFixture())->records[11];
		//$data['UploadFile'] = (new UploadFileFixture())->records[12];

		// 'plugin_key' => 'announcements',
		$cleanUp['CleanUp'] = (new CleanUpFixture())->records[0];

		//テスト実施
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFile, $cleanUp)
		);

		//チェック
		//var_export($result);
		$this->assertFalse($result, 'ファイル、お知らせで使われてないため、falseが戻る想定');
	}

/**
 * __isUseUploadFile()のUnknowテスト
 *
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__isUseUploadFile()
 */
	public function testIsUseUploadFileUnknow() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ
		/* @see NetCommonsCakeTestCase::$_defaultFixtures 'plugin.files.upload_file','plugin.files.upload_files_content', 読み込んでる
		 * @sse UploadFileFixture アップロードファイルのテストデータ. id=12, 13のwysiwygアップロードデータを利用 */
		$uploadFile['UploadFile'] = (new UploadFileFixture())->records[11];
		//$data['UploadFile'] = (new UploadFileFixture())->records[12];

		// プラグイン不明ファイル データゲット
		/* @see CleanUp::getUnknowCleanUp() */
		$cleanUp = $this->$model->getUnknowCleanUp();
		//var_export($cleanUp);

		//テスト実施
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFile, $cleanUp)
		);

		//チェック
		//var_export($result);
		$this->assertFalse($result, '$cleanUp[CleanUp][plugin_key] == unknownはブロックキーなしやコンテンツキーなしで、使われていないため、falseが戻る想定');
	}
}
