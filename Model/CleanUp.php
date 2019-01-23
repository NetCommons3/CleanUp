<?php
/**
 * CleanUp Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpAppModel', 'CleanUp.Model');

/**
 * CleanUp Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Model
 */
class CleanUp extends CleanUpAppModel {

/**
 * useTable
 * TODO 仮でfalse
 *
 * @var bool
 */
	public $useTable = false;

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/ja/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		return parent::beforeValidate($options);
	}

/**
 * ファイルクリーンアップ
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
//	public function fileCleanUp($data) {
	public function CleanUp($data) {
		$this->loadModels(array(
			'UploadFile' => 'Files.UploadFile',
//			'Block' => 'Blocks.Block',
		));
		//トランザクションBegin
		$this->begin();

		//$this->UploadFile->deleteUploadFile($UploadFile_id);
		
		// 対象のプラグインを指定
//		$pluginKey = $data['Plugin']['key'][0];

		// ---------------------------------
		// お知らせの場合
		// ---------------------------------

		// アップロードTBをチェック

//		$this->UploadFile->bindModel([
//			'hasMany' => [
//				'Block' => [
//					'className' => 'Blocks.Block',
////					'foreignKey' => false,
//					'foreignKey' => 'key',
//					'conditions' => [
////						$this->UploadFile->alias . '.block_key = Block.key',
//						'Block.plugin_key' =>'announcements'		// TODO 仮
//					],
//					'fields' => '',
//					'order' => ''
//				]
//			],
//		]);
		$uploadFiles = $this->UploadFile->find('all', array(
			'recursive' => 0,
			'conditions' => array(
				$this->UploadFile->alias . '.plugin_key' => 'wysiwyg',
				'OR' => array(
					$this->UploadFile->alias . '.content_key !=' => null,
					$this->UploadFile->alias . '.content_key !=' => '',
				),
				//$this->UploadFile->alias . '.block_key = Block.key',
				'Block.plugin_key' =>'announcements'		// TODO 仮
			),
//			'callbacks' => false,
			'joins' => array(
				array('table' => 'blocks',
					'alias' => 'Block',
					'type' => 'inner',
					'conditions' => array(
						$this->UploadFile->alias . '.block_key = Block.key',
					)
				)
			),
			'fields' => 'content_key',
			'order' => ''
		));

		// TODO $uploadFiles 取得件数とれすぎる.見直し 1000件づつとか。
		// TODO クリーンアップ設定TB作って、ぱらめとる必要ありそう。
		foreach ($uploadFiles as $uploadFile) {
			// このコンテンツでアップロードファイルを使っているかどうか。
			/*
			SELECT * FROM nc3.announcements
			where (announcements.is_active = 1
			OR announcements.is_latest = 1)
			AND `key` = 'ee5cda11750fe0e839a4b539590b35dd'
			*/
			$model = 'Announcement';
			$class = 'Announcements.Announcement';
			$fields = 'content'; // 複数の場合カンマ区切りかな
			$this->loadModels(array(
				$model => $class,
			));
			// 多言語, アクティブ, 最終のコンテンツで複数件あるため、ループする
			$announcements = $this->$model->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'OR' => array(
						$this->$model->alias . '.is_active' => '1',
						$this->$model->alias . '.is_latest' => '1',
					),
					$this->$model->alias . '.key' => $uploadFile['UploadFile']['content_key'],
				),
				'fields' => $fields,
				'order' => ''
			));
			foreach ($announcements as $announcement) {
				$content = $announcement[$this->Announcement->alias]['content'];
			}
			var_dump($announcements);

		}

//		var_dump($uploadFiles);

		//		$this->loadModels(array(
		//			'Like' => 'Likes.Like',
		//			'TagsContent' => 'Tags.TagsContent',
		//			'UploadFile' => 'Files.UploadFile',
		//			'VideoSetting' => 'Videos.VideoSetting',
		//		));
		//
		//		//トランザクションBegin
		//		$this->begin();
		//
		//		// アップロードファイル
		//		$uploadFiles = $this->UploadFile->find('all', array(
		//			'recursive' => 1,
		//			'conditions' => array($this->UploadFile->alias . '.content_key' => $data['Video']['key']),
		//			'callbacks' => false,
		//		));
		//
		//		try {
		//			// 動画削除($callbacks = true)
		//			$this->contentKey = $data['Video']['key'];
		//			if (! $this->deleteAll(array($this->alias . '.key' => $data['Video']['key']), false, true)) {
		//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		//			}
		//
		//			// 動画とサムネイルのデータと物理ファイル削除
		//			foreach ($uploadFiles as $uploadFile) {
		//				foreach ($uploadFile['UploadFilesContent'] as $uploadFilesContent) {
		//					$this->UploadFile->removeFile($uploadFilesContent['content_id'],
		//						$uploadFilesContent['upload_file_id']);
		//				}
		//			}
		//
		//			// アップロードファイル 削除
		//			$conditions = array($this->UploadFile->alias . '.content_key' => $data['Video']['key']);
		//			if (! $this->UploadFile->deleteAll($conditions, false)) {
		//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		//			}
		//
		//			// タグコンテンツ 削除
		//			$conditions = array($this->TagsContent->alias . '.content_id' => $data['Video']['id']);
		//			if (! $this->TagsContent->deleteAll($conditions, false)) {
		//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		//			}
		//
		//			// いいね 削除
		//			$conditions = array($this->Like->alias . '.content_key' => $data['Video']['key']);
		//			if (! $this->Like->deleteAll($conditions, false)) {
		//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		//			}
		//
		//			// 総容量更新
		//			$totalSize = $this->getTotalSize();
		//			$this->VideoSetting->saveTotalSize($totalSize);
		//
		//			//トランザクションCommit
		//			$this->commit();
		//
		//		} catch (Exception $ex) {
		//			//トランザクションRollback
		//			$this->rollback($ex);
		//		}

		return true;
	}

}
