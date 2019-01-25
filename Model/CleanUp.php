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
 * @property UploadFile $UploadFile
 */
class CleanUp extends CleanUpAppModel {

/**
 * @var string フィールドの区切り文字
 */
	const FIELD_DELIMITER = ',';

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
		$this->setupLog();
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
		$targetPluginKey = 'announcements';	// TODO 仮
		$uploadFiles = $this->UploadFile->find('all', array(
			'recursive' => 0,
			'conditions' => array(
				$this->UploadFile->alias . '.plugin_key' => 'wysiwyg',
				'OR' => array(
					$this->UploadFile->alias . '.content_key !=' => null,
					$this->UploadFile->alias . '.content_key !=' => '',
				),
				//$this->UploadFile->alias . '.block_key = Block.key',
				'Block.plugin_key' => $targetPluginKey
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
			'fields' => 'id, room_id, content_key, path, original_name',
			'order' => ''
		));

		// TODO $uploadFiles 取得件数とれすぎる.見直し 1000件づつとか。
		// TODO クリーンアップ設定TB作って、ぱらめとる必要ありそう。
		// アップロードファイル、1件1件チェック。使われていないidをピックアップ
		foreach ($uploadFiles as $i => $uploadFile) {
			// このコンテンツでアップロードファイルを使っているかどうか。
			/*
			SELECT * FROM nc3.announcements
			where (announcements.is_active = 1
			OR announcements.is_latest = 1)
			AND `key` = 'ee5cda11750fe0e839a4b539590b35dd'
			*/
			// アップロードしたファイルのパスを作成
			/* @see WysiwygFileController::upload() よりコピー */
			$checkFileUrl = NetCommonsUrl::actionUrl(
				array(
					'plugin' => 'wysiwyg',
					'controller' => 'file',
					'action' => 'download',
					$uploadFile['UploadFile']['room_id'],
					$uploadFile['UploadFile']['id']
				),
				false
			);
			$checkImageUrl = NetCommonsUrl::actionUrl(
				array(
					'plugin' => 'wysiwyg',
					'controller' => 'image',
					'action' => 'download',
					$uploadFile['UploadFile']['room_id'],
					$uploadFile['UploadFile']['id']
				),
				false
			);

			$model = 'Announcement';
			$class = 'Announcements.Announcement';
			$fields = 'content'; // 複数の場合カンマ区切りかな
			$this->loadModels(array(
				$model => $class,
			));

			// TODO 作成途中
			// fileUrl, imageUrl使ってると件数取得できる
			$checkConditions = [];
			$fieldsArray = explode(self::FIELD_DELIMITER, $fields);
			foreach ($fieldsArray as $field) {
				$checkConditions[] = array(
					'OR' => array(
						$this->$model->alias . '.' . $field . ' LIKE' => '%' . $checkFileUrl . '%',
						$this->$model->alias . '.' . $field . ' LIKE' => '%' . $checkImageUrl . '%',
					),
				);
			}

			// TODO 作成途中 array_merge_recasivあたりでマージする想定
			$conditions = array(
				array(
					'OR' => array(
						$this->$model->alias . '.is_active' => '1',
						$this->$model->alias . '.is_latest' => '1',
					),
				),
				$this->$model->alias . '.key' => $uploadFile['UploadFile']['content_key'],
				$checkConditions,
			);

			// 多言語, アクティブ, 最終のコンテンツで複数件あるため、ループする
			$count = $this->$model->find('count', array(
				'recursive' => -1,
				'conditions' => $conditions,
				//'fields' => $fields,
				//'order' => ''
			));

			// 該当あり = 該当ファイルは削除しないため、unset
			if ($count) {
				unset($uploadFiles[$i]);
				break;
			}
//			foreach ($announcements as $announcement) {
//				$content = $announcement[$this->Announcement->alias]['content'];
//				//var_dump($content);
//				// チェック
//				if (strpos($content, $checkFileUrl) !== false) {
//					// 含まれるため、該当ファイルは削除しないため、unset
//					unset($uploadFiles[$i]);
//					break;
//				}
//				if (strpos($content, $checkImageUrl) !== false) {
//					// 含まれるため、該当ファイルは削除しないため、unset
//					unset($uploadFiles[$i]);
//					break;
//				}
//			}
			var_dump($count);

//			var_dump($url, $url2);
		}
		if (! $uploadFiles) {
			CakeLog::info(__d('clean_up', '%s 対象ファイルが一件もありませんでした', [$targetPluginKey]), ['CleanUp']);
			return true;
		}

		// ファイル削除処理
//		var_dump($uploadFiles);

		try {
			// お知らせウィジウィグでファイルアップした場合、upload_files_contentsにデータはできなかったため、
			// とりあえずupload_files_contents削除処理は、なしで進める。

			// ファイル削除
			foreach ($uploadFiles as $i => $uploadFile) {
				//$this->UploadFile->deleteUploadFile($uploadFile['UploadFile']['id']);
				$this->__deleteUploadFile($uploadFile);

				$fileName = $uploadFile['UploadFile']['original_name'];
				CakeLog::info(__d('clean_up', '「%s」を削除しました', [$fileName]), ['CleanUp']);
				unset($uploadFiles[$i]);
			}

		//			// 動画削除($callbacks = true)
		//			$this->contentKey = $data['Video']['key'];
		//			if (! $this->deleteAll(array($this->alias . '.key' => $data['Video']['key']), false, true)) {
		//				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
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
			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

/**
 * Delete uploadFile
 *
 * @param arrya $uploadFile UploadFile
 * @return bool
 * @see UploadFile::deleteUploadFile() よりコピー
 */
	private function __deleteUploadFile($uploadFile) {
		// Uploadビヘイビアにpathを渡す
		//$uploadFile = $this->findById($fileId);
		$fileId = $uploadFile['UploadFile']['id'];
		$path = $this->UploadFile->uploadBasePath . $uploadFile['UploadFile']['path'];
		$this->UploadFile->uploadSettings('real_file_name', 'path', $path);
		$this->UploadFile->uploadSettings('real_file_name', 'thumbnailPath', $path);

		/* @see WysiwygFileController::_setUploadFileModel() よりコピー */
		$thumbnailSizes = $this->UploadFile->actsAs['Upload.Upload']['real_file_name']['thumbnailSizes'];
		$thumbnailSizes['biggest'] = '1200ml';
		$this->UploadFile->uploadSettings('real_file_name', 'thumbnailSizes', $thumbnailSizes);

		return $this->UploadFile->delete($fileId, false);
	}

/**
 * Setup log
 *
 * @return void
 * @see Nc2ToNc3BaseBehavior::setup() よりコピー
 */
	public function setupLog() {
		// CakeLog::writeでファイルとコンソールに出力していた。
		// Consoleに出力すると<tag></tag>で囲われ見辛い。
		// @see
		// https://github.com/cakephp/cakephp/blob/2.9.4/lib/Cake/Console/ConsoleOutput.php#L230-L241
		// CakeLog::infoをよびだし、debug.logとNc2ToNc3.logの両方出力するようにした。
		CakeLog::config(
			'CleanUpFile',
			[
				'engine' => 'FileLog',
				'types' => ['info'],
				'scopes' => ['CleanUp'],
				'file' => 'CleanUp.log',
			]
		);
	}

}
