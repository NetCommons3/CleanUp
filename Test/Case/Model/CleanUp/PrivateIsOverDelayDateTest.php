<?php
/**
 * CleanUp::__isOverDelayDate()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpModelTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpTestUtil', 'CleanUp.Test/Case');

/**
 * CleanUp::__isOverDelayDate()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Model\CleanUp
 */
class CleanUpPrivateIsOverDelayDateTest extends CleanUpModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
		//'plugin.clean_up.announcement_for_clean_up',
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
	protected $_methodName = '__isOverDelayDate';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$model = $this->_modelName;
		$this->$model->nc314InstallDatetime = '2015-03-31 00:00:00';
	}

/**
 * __isOverDelayDate()のテスト. チェック対象:announcement
 *
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__isOverDelayDate()
 */
	public function testIsOverDelayDate() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		// チェック対象プラグイン
		$data['CleanUp']['plugin_key'] = [
			'announcements'
		];
		/* @see Cleanup::findCleanUpsAndPlugin() */
		$cleanUps = $this->$model->findCleanUpsAndPlugin($data);
		//var_dump($cleanUps);
		$cleanUp = $cleanUps[0];

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//アップロードデータ
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUp);
		$uploadFiles = $this->$model->UploadFile->find('all', $params);

		//
		//テスト実施
		//
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFiles[0])
		);

		//チェック
		//var_export($result);
		$this->assertFalse($result,
			'アップロードファイルの更新日が削除遅延日より小さいのでfalseの想定');
	}

/**
 * __isOverDelayDate()の削除遅延日以上テスト. チェック対象:announcement
 *
 * @return void
 * @throws ReflectionException
 * @see CleanUp::__isOverDelayDate()
 */
	public function testIsOverDelayDateOver() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ

		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		// チェック対象プラグイン
		$data['CleanUp']['plugin_key'] = [
			'announcements'
		];
		/* @see Cleanup::findCleanUpsAndPlugin() */
		$cleanUps = $this->$model->findCleanUpsAndPlugin($data);
		//var_dump($cleanUps);
		$cleanUp = $cleanUps[0];

		// UploadFileインスタンスの準備
		$this->$model->UploadFile = ClassRegistry::init('Files.UploadFile', true);

		//アップロードデータ
		/* @see Cleanup::getUploadFileParams() */
		$params = $this->$model->getUploadFileParams($cleanUp);
		$uploadFiles = $this->$model->UploadFile->find('all', $params);
		//var_dump($uploadFiles);

		// 削除遅延日 x日前
		// ありえないほど前の日(20年～30年前)を指定して削除対象外にする
		/* @see Cleanup::$deleteDelayDay */
		$this->$model->deleteDelayDay = 9999;

		//
		//テスト実施
		//
		$result = $this->_testReflectionMethod(
			$this->$model, $methodName, array($uploadFiles[0])
		);

		//チェック
		//var_export($result);
		$this->assertTrue($result,
			'アップロードファイルの更新日が削除遅延日以上なのでtrueの想定');
	}

}
