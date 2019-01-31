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
 * フィールドの区切り文字
 *
 * @var string
 */
	const FIELD_DELIMITER = ',';

/**
 * 1000件ずつアップロードファイルのデータを取得するようにする
 *
 * @var string
 */
	const FIND_LIMIT_UPLOAD_FILE = 1000;

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
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
 * getCleanUp
 *
 * return例
 * ```
 * array (size=1)
 *   0 =>
 *     array (size=2)
 *       'CleanUp' =>
 *         array (size=1)
 *           'plugin_key' => string 'announcements' (length=13)
 *       'Plugin' =>
 *         array (size=2)
 *           'key' => string 'announcements' (length=13)
 *           'name' => string 'お知らせ' (length=12)
 * ```
 *
 * @return array
 */
	public function getCleanUp() {
		$params = array(
			'recursive' => -1,
			//'conditions' => array(),
			//'callbacks' => false,
			'joins' => array(
				array('table' => 'plugins',
					'alias' => 'Plugin',
					'type' => 'inner',
					'conditions' => array(
						'CleanUp.plugin_key = Plugin.key',
						'Plugin.language_id' => Current::read('Language.id'),
					)
				)
			),
			'fields' => 'CleanUp.plugin_key, Plugin.key, Plugin.name',
			//'order' => ''
		);
		return $this->find('all', $params);
	}

/**
 * getUnknowCleanUp
 *
 * @return array
 */
	public function getUnknowCleanUp() {
		$unknowCleanUp = [
			'CleanUp' => [
				'plugin_key' => 'unknown',
			],
			'Plugin' => [
				'key' => 'unknown',
				// プラグイン不明のファイル
				'name' => __d('clean_up', 'Plugin unknown file'),
			],
		];
		return $unknowCleanUp;
	}

/**
 * ファイルクリーンアップ
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws Exception
 */
	public function fileCleanUp($data) {
		$this->setupLog();
		$this->loadModels(array(
			'UploadFile' => 'Files.UploadFile',
		));
		//トランザクションBegin
		$this->begin();

		//$this->UploadFile->deleteUploadFile($UploadFile_id);
		
		// 対象のプラグインを指定
//		$pluginKey = $data['Plugin']['key'][0];

		// TODO プラグイン毎に実行

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
		$pluginKey = 'announcements';	// TODO 仮
		$model = 'Announcement';
		$class = 'Announcements.Announcement';
		$fields = 'content'; // 複数の場合カンマ区切り

		// block_keyあり、content_keyあり、コンテンツあり
//		$uploadFiles = $this->UploadFile->find('all', array(
//			'recursive' => 0,
//			'conditions' => array(
//				$this->UploadFile->alias . '.plugin_key' => 'wysiwyg',
//				'OR' => array(
//					array($this->UploadFile->alias . '.content_key !=' => null),
//					array($this->UploadFile->alias . '.content_key !=' => ''),
//				),
//				'Block.plugin_key' => $targetPluginKey
//			),
//			//'callbacks' => false,
//			'joins' => array(
//				array('table' => 'blocks',
//					'alias' => 'Block',
//					'type' => 'inner',
//					'conditions' => array(
//						$this->UploadFile->alias . '.block_key = Block.key',
//					)
//				)
//			),
//			'fields' => 'id, room_id, content_key, path, original_name',
//			//'order' => ''
//		));



		// ファイル削除処理
//		var_dump($uploadFiles);
		//
		// 処理件数
		$reseultCount = 0;

		try {
			// プラグイン不明のファイル
			//
			// 全プラグインの処理終わってから最後に実行
			// block_keyなし、content_keyなし
			// block_keyなしの場合、どのプラグインから投稿されたか不明
			// この対象データは、this->__isUseUploadFile()チェック不要。block_keyなし、content_keyなしで使われてない事がわかっているため。
			$params = array(
				'recursive' => 0,
				'conditions' => array(
					$this->UploadFile->alias . '.plugin_key' => 'wysiwyg',
					'OR' => array(
						array('Block.id' => null),
						array($this->UploadFile->alias . '.content_key' => null),
						array($this->UploadFile->alias . '.content_key' => ''),
					),
				),
				//'callbacks' => false,
				'joins' => array(
					array('table' => 'blocks',
						'alias' => 'Block',
						'type' => 'left',
						'conditions' => array(
							$this->UploadFile->alias . '.plugin_key' => 'wysiwyg',
							$this->UploadFile->alias . '.block_key = Block.key',
						)
					)
				),
				'fields' => 'id, room_id, content_key, path, original_name',
				//'order' => ''
			);

			// TODO クリーンアップ設定TB作って、ぱらめとる必要ありそう。

			// $uploadFiles findでデータとれすぎてメモリ圧迫問題対応。 1000件づつ取得
			$params = array_merge($params, array('limit' => self::FIND_LIMIT_UPLOAD_FILE, 'offset' => 0));
			while ($uploadFiles = $this->UploadFile->find('all', $params)){
				foreach ($uploadFiles as $i => $uploadFile) {
					// このコンテンツでアップロードファイルを使っているかどうか。
					// 該当あり => 該当ファイルは使ってるため削除しない
					if ($this->__isUseUploadFile($uploadFile, $model, $class, $fields)) {
						unset($uploadFiles[$i]);
						continue;
					}
					var_dump($uploadFile);

					// お知らせウィジウィグでファイルアップした場合、upload_files_contentsにデータはできなかったため、
					// とりあえずupload_files_contents削除処理は、なしで進める。

					// ファイル削除
	//				foreach ($uploadFiles as $i => $uploadFile) {
					//$this->UploadFile->deleteUploadFile($uploadFile['UploadFile']['id']);
					$fileName = $uploadFile['UploadFile']['original_name'];
	//				if ($this->__deleteUploadFile($uploadFile) === false) {
						CakeLog::info(__d('clean_up', '「%s」の削除に失敗しました', [$fileName]), ['CleanUp']);
	//				} else {
	//					CakeLog::info(__d('clean_up', '「%s」を削除しました', [$fileName]), ['CleanUp']);
	//				}
	//					unset($uploadFiles[$i]);
	//				}
					$reseultCount++;
				}
				// 次のn件取得
				$params['offset'] = self::FIND_LIMIT_UPLOAD_FILE;
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		if ($reseultCount === 0) {
			CakeLog::info(__d('clean_up', '%s 対象ファイルが一件もありませんでした', [$pluginKey]), ['CleanUp']);
			return true;
		}

		return true;
	}

/**
 * Delete uploadFile
 *
 * @param array $uploadFile UploadFile
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
 * このコンテンツでアップロードファイルを使っているかどうか。
 *
 * @param array $uploadFile UploadFile
 * @param string $model モデル名
 * @param string $class クラス名
 * @param string $fields フィールド名
 * @return bool true:使ってる|false:使ってない
 */
	private function __isUseUploadFile($uploadFile, $model, $class, $fields) {
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

		$this->loadModels(array(
			$model => $class,
		));

		// fileUrl, imageUrl使ってると件数取得する条件
		$checkConditions = [];
		$fieldsArray = explode(self::FIELD_DELIMITER, $fields);
		foreach ($fieldsArray as $field) {
			$checkConditions[] = array(
				'OR' => array(
					array($this->$model->alias . '.' . $field . ' LIKE' => '%' . $checkFileUrl . '%'),
					array($this->$model->alias . '.' . $field . ' LIKE' => '%' . $checkImageUrl . '%'),
				),
			);
		}

		// 最新とアクティブを取得する条件
		$conditions = array(
			array(
				'OR' => array(
					$this->$model->alias . '.is_active' => '1',
					$this->$model->alias . '.is_latest' => '1',
				),
			),
			$this->$model->alias . '.key' => $uploadFile['UploadFile']['content_key'],
		);
		$conditions = array_merge_recursive($conditions, $checkConditions);

		// 多言語, 最新とアクティブ, のコンテンツで複数件ある
		$count = $this->$model->find('count', array(
			'recursive' => -1,
			'conditions' => $conditions,
			//'fields' => $fields,
			//'order' => ''
		));

		if ($count) {
			return true;
		}
		return false;
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
