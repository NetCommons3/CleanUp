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
 * __deleteUploadFiles()の削除遅延日テスト. チェック対象unknow
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
 * __deleteUploadFiles()のテスト. チェック対象announcement<br />
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
		$uploadFiles = $this->$model->UploadFile->find('all', $params);

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

}
