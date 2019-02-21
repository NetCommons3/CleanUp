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
App::uses('UploadFileForCleanUpFixture', 'CleanUp.Test/Fixture');
App::uses('CleanUpFixture', 'CleanUp.Test/Fixture');
App::uses('CleanUpTestUtil', 'CleanUp.Test/Case');

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
		'plugin.clean_up.upload_file_for_clean_up',
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

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();
	}

/**
 * testIsUseUploadFileUseのDataProvider
 *
 * @return array テストデータ
 * @see CleanUpPrivateIsUseUploadFileTest::testIsUseUploadFileUse() テスト対象
 */
	public function dataProviderUse() {
		// 'plugin_key' => 'announcements',
		$cleanUp['CleanUp'] = (new CleanUpFixture())->records[0];

		$UploadFileFixture = new UploadFileForCleanUpFixture();

		return [
			'1.お知らせ(is_active=1 and is_latest=1)でファイル使ってる' => [
				'uploadFile' => [
					/* @see UploadFileForCleanUpFixture::$records アップロードファイルのテストデータ. id=14のwysiwygのお知らせアップロードデータを利用
					 * @see AnnouncementForCleanUpFixture::$records id=5 がアップロード元お知らせ　is_active=1 and is_latest=1 */
					'UploadFile' => $UploadFileFixture->records[13]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル、お知らせ(is_active=1 and is_latest=1)で使われてるため、trueが戻る想定'
			],
			'2.お知らせで英日あり。英でファイル使ってる' => [
				'uploadFile' => [
					/* @see UploadFileForCleanUpFixture::$records アップロードファイルのテストデータ. id=15のwysiwygのお知らせアップロードデータを利用
					 * @see AnnouncementForCleanUpFixture::$records id=6,7 がアップロード元お知らせ　英日で英でファイル利用, is_active=1 and is_latest=1 */
					'UploadFile' => $UploadFileFixture->records[14]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル、お知らせ英語(is_active=1 and is_latest=1)で使われてるため、trueが戻る想定'
			],
		];
	}

/**
 * __isUseUploadFile()のファイル使ってるテスト
 *
 * @param array $uploadFile uploadFile
 * @param string $cleanUp cleanUp
 * @param string $assertMessage assertメッセージ
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__isUseUploadFile()
 *
 * @dataProvider dataProviderUse
 */
	public function testIsUseUploadFileUse($uploadFile, $cleanUp, $assertMessage = '') {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ
		//$uploadFile['UploadFile'] = (new UploadFileForCleanUpFixture())->records[13];

		// 'plugin_key' => 'announcements',
		//$cleanUp['CleanUp'] = (new CleanUpFixture())->records[0];

		//テスト実施
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFile, $cleanUp)
		);

		//チェック
		//var_export($result);
		//$this->assertTrue($result, 'ファイル、お知らせ(is_active=1 and is_latest=1)で使われてるため、trueが戻る想定');
		$this->assertTrue($result, $assertMessage);
	}

/**
 * testIsUseUploadFileUseのDataProvider
 *
 * @return array テストデータ
 * @see CleanUpPrivateIsUseUploadFileTest::testIsUseUploadFileUse() テスト対象
 */
	public function dataProviderNotUse() {
		// 'plugin_key' => 'announcements',
		$cleanUp1['CleanUp'] = (new CleanUpFixture())->records[0];

		// プラグイン不明ファイル
		// 'plugin_key' => 'unknow',
		/* @see CleanUp::getUnknowCleanUp() */
		$CleanUp = ClassRegistry::init('CleanUp.CleanUp', true);
		$cleanUp2 = $CleanUp->getUnknowCleanUp();
		//var_export($cleanUp2);

		$UploadFileFixture = new UploadFileForCleanUpFixture();

		return [
			'1.お知らせで使われてない' => [
				'uploadFile' => [
					/* @sse UploadFileForCleanUpFixture アップロードファイルのテストデータ. id=12のwysiwygアップロードデータを利用 */
					'UploadFile' => $UploadFileFixture->records[11]
				],
				'cleanUp' => $cleanUp1,
				'assertMessage' =>
					'ファイル、お知らせで使われてないため、falseが戻る想定'
			],
			'2.プラグイン不明ファイルで使われてない' => [
				'uploadFile' => [
					'UploadFile' => $UploadFileFixture->records[11]
				],
				'cleanUp' => $cleanUp2,
				'assertMessage' =>
					'$cleanUp[CleanUp][plugin_key] == unknownはブロックキーなしやコンテンツキーなしで、使われていないため、falseが戻る想定'
			],
		];
	}

/**
 * __isUseUploadFile()のファイル使ってないテスト
 *
 * @param array $uploadFile uploadFile
 * @param string $cleanUp cleanUp
 * @param string $assertMessage assertメッセージ
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__isUseUploadFile()
 *
 * @dataProvider dataProviderNotUse
 */
	public function testIsUseUploadFileNotUse($uploadFile, $cleanUp, $assertMessage = '') {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ
		//$uploadFile['UploadFile'] = (new UploadFileForCleanUpFixture())->records[11];

		// 'plugin_key' => 'announcements',
		//$cleanUp['CleanUp'] = (new CleanUpFixture())->records[0];

		//テスト実施
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFile, $cleanUp)
		);

		//チェック
		//var_export($result);
		//$this->assertFalse($result, 'ファイル、お知らせで使われてないため、falseが戻る想定');
		$this->assertFalse($result, $assertMessage);
	}

}
