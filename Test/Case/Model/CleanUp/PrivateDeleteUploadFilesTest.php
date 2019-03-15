<?php
/**
 * CleanUp::__deleteUploadFiles()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpModelTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpTestUtil', 'CleanUp.Test/Case');

/**
 * CleanUp::__deleteUploadFiles()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpPrivateDeleteUploadFilesTest extends CleanUpModelTestCase {

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
	protected $_methodName = '__deleteUploadFiles';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * __deleteUploadFiles()のテスト. チェック対象unknow
 *
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__deleteUploadFiles()
 */
	public function testDeleteUploadFilesUnknow() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		// チェック対象プラグイン
		/* @see Cleanup::getUnknowCleanUp() */
		$cleanUp = $this->$model->getUnknowCleanUp();

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//アップロードデータ
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUp);
		$params['conditions'] = array_merge($params['conditions'], ['UploadFile.id' => [12, 13]]);
		$uploadFiles = $this->$model->UploadFile->find('all', $params);

		// 削除対象件数 初期値
		$targetCount = 0;

		//
		//テスト実施
		//
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFiles, $cleanUp, $targetCount)
		);

		//チェック
		//var_export($result);
		$this->assertEquals(2, $result, '2件ファイル削除する想定');
	}

/**
 * __deleteUploadFiles()の削除遅延日以上テスト. チェック対象unknow
 *
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__deleteUploadFiles()
 */
	public function testDeleteUploadFilesDelayDay() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		// チェック対象プラグイン
		/* @see Cleanup::getUnknowCleanUp() */
		$cleanUp = $this->$model->getUnknowCleanUp();

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//アップロードデータ
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUp);
		//var_dump($params);
		$params['conditions'] = array_merge($params['conditions'], ['UploadFile.id' => [12, 13]]);
		$uploadFiles = $this->$model->UploadFile->find('all', $params);
		//var_dump($uploadFiles);

		// 削除対象件数 初期値
		$targetCount = 0;

		// 削除遅延日 x日前
		// ありえないほど前の日(20年～30年前)を指定して削除対象外にする
		/* @see Cleanup::$deleteDelayDay */
		$this->$model->deleteDelayDay = 9999;

		//
		//テスト実施
		//
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFiles, $cleanUp, $targetCount)
		);

		//チェック
		//var_export($result);
		$this->assertEquals(0, $result, 'ファイル削除なしで0件の想定');
	}

/**
 * __deleteUploadFiles()のテスト. チェック対象:announcement<br />
 * __isUseUploadFile()=trueでcontinue通しテスト
 *
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__deleteUploadFiles()
 */
	public function testDeleteUploadFilesAnnouncement() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		$data['CleanUp']['plugin_key'] = [
			'announcements'
		];

		// チェック対象プラグイン
		/* @see Cleanup::getCleanUpsAndPlugin() */
		$cleanUps = $this->$model->getCleanUpsAndPlugin($data);
		//var_dump($cleanUps);

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//アップロードデータ
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUps[0]);
		//var_dump($params);
		$params['conditions'] = array_merge($params['conditions'], ['UploadFile.id' => 14]);

		$uploadFiles = $this->$model->UploadFile->find('all', $params);
		//var_dump($uploadFiles);

		// 削除対象件数 初期値
		$targetCount = 0;

		//
		//テスト実施
		//
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFiles, $cleanUps[0], $targetCount)
		);

		//チェック
		//var_export($result);
		$this->assertEquals(0, $result,
			'アップロードファイル使ってるため、ファイル削除なしで0件の想定');
	}

/**
 * DataProvider
 *
 * @return array テストデータ
 * @see CleanUpPrivateDeleteUploadFilesTest::testDeleteUploadFilesDeleteExtension1() テスト対象
 * @see CleanUpPrivateDeleteUploadFilesTest::testDeleteUploadFilesDeleteExtension2() テスト対象
 */
	public function dataProviderUnknowAndAnnouncement() {
		return [
			'1.削除する拡張子を指定jpg' => [
				'deleteExtension' => 'jpg',
				'resultDeleteCount' => 1,
				'assertMessage' => '1件ファイル削除する想定'
			],
			'2.削除する拡張子を指定しない=全拡張子対象' => [
				'deleteExtension' => '',
				'resultDeleteCount' => 2,
				'assertMessage' => '2件ファイル削除する想定'
			],
		];
	}

/**
 * __deleteUploadFiles()のテスト. チェック対象unknow 削除対象. 削除する拡張子を指定
 *
 * @param string $deleteExtension 削除する拡張子
 * @param int $resultDeleteCount 削除した件数
 * @param string $assertMessage テスト想定メッセージ
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__deleteUploadFiles()
 *
 * @dataProvider dataProviderUnknowAndAnnouncement
 */
	public function testDeleteUploadFilesDeleteExtension1($deleteExtension, $resultDeleteCount,
														$assertMessage) {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		// 削除する拡張子を指定
		//$this->$model->deleteExtension = 'jpg';
		$this->$model->deleteExtension = $deleteExtension;

		// チェック対象プラグイン
		/* @see Cleanup::getUnknowCleanUp() */
		$cleanUp = $this->$model->getUnknowCleanUp();

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//アップロードデータ
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUp);
		$params['conditions'] = array_merge($params['conditions'], ['UploadFile.id' => [12, 23]]);
		$uploadFiles = $this->$model->UploadFile->find('all', $params);

		// 削除対象件数 初期値
		$targetCount = 0;

		//
		//テスト実施
		//
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFiles, $cleanUp, $targetCount)
		);

		//チェック
		//var_export($result);
		//$this->assertEquals(1, $result, '1件ファイル削除する想定');
		$this->assertEquals($resultDeleteCount, $result, $assertMessage);
	}

/**
 * __deleteUploadFiles()のテスト. チェック対象announcements 削除対象. 削除する拡張子を指定
 *
 * @param string $deleteExtension 削除する拡張子
 * @param int $resultDeleteCount 削除した件数
 * @param string $assertMessage テスト想定メッセージ
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__deleteUploadFiles()
 *
 * @dataProvider dataProviderUnknowAndAnnouncement
 */
	public function testDeleteUploadFilesDeleteExtension2($deleteExtension, $resultDeleteCount,
														$assertMessage) {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		// 削除する拡張子を指定
		//$this->$model->deleteExtension = 'jpg';
		$this->$model->deleteExtension = $deleteExtension;

		$data['CleanUp']['plugin_key'] = [
			'announcements'
		];

		// チェック対象プラグイン
		/* @see Cleanup::getCleanUpsAndPlugin() */
		$cleanUps = $this->$model->getCleanUpsAndPlugin($data);
		//var_dump($cleanUps);

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//アップロードデータ
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUps[0]);
		$params['conditions'] = array_merge($params['conditions'], ['UploadFile.id' => [22, 24]]);
		$uploadFiles = $this->$model->UploadFile->find('all', $params);
		//var_export($params);
		//var_dump($uploadFiles);

		// 削除対象件数 初期値
		$targetCount = 0;

		//
		//テスト実施
		//
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFiles, $cleanUps[0], $targetCount)
		);

		//チェック
		//var_export($result);
		//$this->assertEquals(1, $result, '1件ファイル削除する想定');
		$this->assertEquals($resultDeleteCount, $result, $assertMessage);
	}

}
