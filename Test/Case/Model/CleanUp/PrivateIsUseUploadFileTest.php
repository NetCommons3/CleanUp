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
					'UploadFile' => $UploadFileFixture->records[4]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル、お知らせ(is_active=1 and is_latest=1)で使われてるため、trueが戻る想定'
			],
			'2.お知らせで英日あり。英でファイル使ってる' => [
				'uploadFile' => [
					/* @see UploadFileForCleanUpFixture::$records アップロードファイルのテストデータ. id=15のwysiwygのお知らせアップロードデータを利用
					 * @see AnnouncementForCleanUpFixture::$records id=6,7 がアップロード元お知らせ　英日で英でファイル利用, is_active=1 and is_latest=1 */
					'UploadFile' => $UploadFileFixture->records[5]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル、お知らせ英語(is_active=1 and is_latest=1)で使われてるため、trueが戻る想定'
			],
			'3.お知らせで英日あり。日でファイル使ってる' => [
				'uploadFile' => [
					/* @see UploadFileForCleanUpFixture::$records アップロードファイルのテストデータ. id=16のwysiwygのお知らせアップロードデータを利用
					 * @see AnnouncementForCleanUpFixture::$records id=8,9 がアップロード元お知らせ　英日で日でファイル利用, is_active=1 and is_latest=1 */
					'UploadFile' => $UploadFileFixture->records[6]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル、お知らせ日本語(is_active=1 and is_latest=1)で使われてるため、trueが戻る想定'
			],
			'4.お知らせ(is_active=1)でファイル使ってる' => [
				'uploadFile' => [
					/* @see UploadFileForCleanUpFixture::$records アップロードファイルのテストデータ. id=20のwysiwygのお知らせアップロードデータを利用
					 * @see AnnouncementForCleanUpFixture::$records id=12 がアップロード元お知らせ　is_active=1 */
					'UploadFile' => $UploadFileFixture->records[10]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル、お知らせ(is_active=1)で使われてるため、trueが戻る想定'
			],
			'5.お知らせ(is_latest=1 & 一時保存)でファイル使ってる' => [
				'uploadFile' => [
					/* @see UploadFileForCleanUpFixture::$records アップロードファイルのテストデータ. id=21のwysiwygのお知らせアップロードデータを利用
					 * @see AnnouncementForCleanUpFixture::$records id=15 がアップロード元お知らせ　is_latest=1 */
					'UploadFile' => $UploadFileFixture->records[11]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル、お知らせ(is_latest=1)で使われてるため、trueが戻る想定'
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
		$cleanUp['CleanUp'] = (new CleanUpFixture())->records[0];

		$UploadFileFixture = new UploadFileForCleanUpFixture();

		return [
			'1.お知らせで使われてない(block.id=null, block.plugin_key="announcements"なし)' => [
				'uploadFile' => [
					/* @sse UploadFileForCleanUpFixture アップロードファイルのテストデータ. id=12のwysiwygアップロードデータを利用 */
					'UploadFile' => $UploadFileFixture->records[2]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル(block.id=null, block.plugin_key="announcements"なし)で、お知らせで使われてないため、falseが戻る想定'
			],
			'2.お知らせで使われてない(content_key="")' => [
				'uploadFile' => [
					/* @sse UploadFileForCleanUpFixture アップロードファイルのテストデータ. id=17のwysiwygアップロードデータを利用 */
					'UploadFile' => $UploadFileFixture->records[7]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル(content_key="")で、お知らせで使われてないため、falseが戻る想定'
			],
			'3.お知らせで使われてない(content_key=null)' => [
				'uploadFile' => [
					/* @sse UploadFileForCleanUpFixture アップロードファイルのテストデータ. id=18のwysiwygアップロードデータを利用 */
					'UploadFile' => $UploadFileFixture->records[8]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル(content_key=null)で、お知らせで使われてないため、falseが戻る想定'
			],
			'4.プラグイン不明ファイルで使われてない(content_key=null)' => [
				'uploadFile' => [
					'UploadFile' => $UploadFileFixture->records[2]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'$cleanUp[CleanUp][plugin_key] == unknownはブロックキーなしやコンテンツキーなしで、使われていないため、falseが戻る想定'
			],
			'5.お知らせで英日あり。どちらもファイル使ってない' => [
				'uploadFile' => [
					/* @sse UploadFileForCleanUpFixture アップロードファイルのテストデータ. id=19のwysiwygアップロードデータを利用 */
					'UploadFile' => $UploadFileFixture->records[9]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイルは、英日お知らせで使われてないため、falseが戻る想定'
			],
			'6.お知らせ(is_latest=1 or is_latest=1)でファイル使ってない' => [
				'uploadFile' => [
					/* @see UploadFileForCleanUpFixture::$records アップロードファイルのテストデータ. id=22のwysiwygのお知らせアップロードデータを利用
					 * @see AnnouncementForCleanUpFixture::$records id=16,17,18 が該当お知らせ */
					'UploadFile' => $UploadFileFixture->records[12]
				],
				'cleanUp' => $cleanUp,
				'assertMessage' =>
					'ファイル、お知らせ(is_latest=1 or is_latest=1)でファイル使ってないため、falseが戻る想定'
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
