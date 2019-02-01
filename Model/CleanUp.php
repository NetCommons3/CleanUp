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
App::uses('NetCommonsUrl', 'NetCommons.Utility');

/**
 * CleanUp Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Model
 * @property UploadFile $UploadFile
 */
class CleanUp extends CleanUpAppModel {

/**
 * WYSIWYG対象フィールドの区切り文字
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
 * 削除遅延日 x日前<br />
 * 例えば1日前を指定すると、今日アップしたファイルは消さなくなります
 *
 * @var string
 */
	const DELETE_DELAY_DAY = 0;

/**
 * プラグイン不明ファイル のプラグインキー
 *
 * @var string
 */
	const PLUGIN_KEY_UNKNOWN = 'unknown';

/**
 * バックアップ方法URL
 *
 * @var string
 */
	const HOW_TO_BACKUP_URL = 'https://www.netcommons.org/NetCommons3/download#!#frame-362';

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
 * construct
 *
 * @return void
 */
	function __construct() {
		parent::__construct();

		// ログ設定
		$this->setupLog();
	}

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
		$this->validate = Hash::merge(array(
			'plugin_key' => array(
				'multiple' => array(
					'rule' => array('multiple', array('min' => 1)),
					// plugin
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('clean_up', 'プラグイン')),
					'required' => false,
				),
			),
		), $this->validate);

		return parent::beforeValidate($options);
	}

/**
 * getCleanUpsAndPlugin
 *
 * @param array $data received post data. ex) ['CleanUp']['plugin_key'][] = 'announcements'
 * @return array
 */
	public function getCleanUpsAndPlugin($data = null) {
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
			'fields' => 'CleanUp.plugin_key, CleanUp.model, CleanUp.class, CleanUp.fields, ' .
				'Plugin.key, Plugin.name',
			'order' => 'CleanUp.id'
		);
		// dataあれば条件追加
		if ($data) {
			$params['conditions'] = [
				'plugin_key' => $data['CleanUp']['plugin_key']
			];
		}

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
				'plugin_key' => self::PLUGIN_KEY_UNKNOWN,
				'model' => self::PLUGIN_KEY_UNKNOWN, //ログ出力のみで利用
			],
			'Plugin' => [
				'key' => self::PLUGIN_KEY_UNKNOWN,
				// Plugin unknown file
				'name' => __d('clean_up', 'プラグイン不明ファイル'),
			],
		];
		return $unknowCleanUp;
	}

/**
 * プラグイン不明ファイルを含むCleanUp一覧 ゲット
 *
 * @param array $data received post data. ex) ['CleanUp']['plugin_key'][] = 'announcements'
 * @return array
 */
	public function getCleanUpsAndUnknow($data = null) {
		$cleanUps = $this->getCleanUpsAndPlugin($data);
		$cleanUps[] = $this->getUnknowCleanUp();
		return $cleanUps;
	}

/**
 * ファイルクリーンアップ
 *
 * @param array $data received post data. ['CleanUp']['plugin_key'][] = 'announcements'
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws Exception
 */
	public function fileCleanUp($data) {
		$this->loadModels(array(
			'UploadFile' => 'Files.UploadFile',
		));
		//トランザクションBegin
		$this->begin();
		//var_dump($data);

		//バリデーション
		$this->set($data);
		/* @see beforeValidate() */
		if (! $this->validates()) {
			return false;
		}
		CakeLog::info(__d('clean_up', 'クリーンアップ処理を開始します'), ['CleanUp']);

		// ファイルクリーンアップ対象のプラグイン設定を取得
		$cleanUps = $this->getCleanUpsAndPlugin($data);
		foreach ($data['CleanUp']['plugin_key'] as $plugin_key) {
			// プラグイン不明ファイルがチェックされてたら、プラグイン不明ファイル データ追加
			if ($plugin_key == self::PLUGIN_KEY_UNKNOWN) {
				$cleanUps[] = $this->getUnknowCleanUp();
				break;
			}
		}
		//var_dump($cleanUps);

		try {
			foreach ($cleanUps as $cleanUp) {
				// 削除対象件数
				$targetCount = 0;
				//var_dump($cleanUp);
				$pluginName = $cleanUp['Plugin']['name'];
				$model = $cleanUp['CleanUp']['model'];

				CakeLog::info(__d('clean_up', '[%s:%s] クリーンアップ処理を開始します',
					[$pluginName, $model]), ['CleanUp']);

				//アップロードファイルのfind条件 ゲット
				$params = $this->__getUploadFileParams($cleanUp);
				// $uploadFiles findでデータとれすぎてメモリ圧迫問題対応。 1000件づつ取得
				$params = array_merge($params, array('limit' => self::FIND_LIMIT_UPLOAD_FILE, 'offset' => 0));

				while ($uploadFiles = $this->UploadFile->find('all', $params)){
					// ファイル削除
					$targetCount = $this->__deleteUploadFiles($uploadFiles, $cleanUp, $targetCount);
					// 次のn件取得
					$params['offset'] = self::FIND_LIMIT_UPLOAD_FILE;
				}

				if ($targetCount === 0) {
					CakeLog::info(__d('clean_up', '[%s:%s] 対象ファイルが一件もありませんでした',
						[$pluginName, $model]), ['CleanUp']);
				} else {
					CakeLog::info(__d('clean_up', '[%s:%s] クリーンアップ処理が完了しました',
						[$pluginName, $model]), ['CleanUp']);
				}
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		CakeLog::info(__d('clean_up', 'クリーンアップ処理が完了しました'), ['CleanUp']);
		return true;
	}

/**
 * アップロードファイルのfind条件 ゲット
 *
 * @param array $cleanUp request->data 1件
 * @return array
 * @see UploadFile::deleteUploadFile() よりコピー
 */
	private function __getUploadFileParams($cleanUp) {
		if ($cleanUp['CleanUp']['plugin_key'] == self::PLUGIN_KEY_UNKNOWN) {
			// プラグイン不明ファイル
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
				'fields' => 'UploadFile.id, UploadFile.room_id, UploadFile.content_key, ' .
					'UploadFile.path, UploadFile.original_name, UploadFile.modified',
				'order' => 'UploadFile.id'
			);
		} else {
			// block_keyあり、content_keyあり、コンテンツあり
			$params = array(
				'recursive' => 0,
				'conditions' => array(
					$this->UploadFile->alias . '.plugin_key' => 'wysiwyg',
					'OR' => array(
						array($this->UploadFile->alias . '.content_key !=' => null),
						array($this->UploadFile->alias . '.content_key !=' => ''),
					),
					'Block.plugin_key' => $cleanUp['CleanUp']['plugin_key']
				),
				//'callbacks' => false,
				'joins' => array(
					array('table' => 'blocks',
						'alias' => 'Block',
						'type' => 'inner',
						'conditions' => array(
							$this->UploadFile->alias . '.block_key = Block.key',
						)
					)
				),
				'fields' => 'UploadFile.id, UploadFile.room_id, UploadFile.content_key, ' .
					'UploadFile.path, UploadFile.original_name, UploadFile.modified',
				'order' => 'UploadFile.id'
			);
		}
		return $params;
	}

/**
 * Delete uploadFiles
 *
 * @param array $uploadFiles UploadFiles
 * @param array $cleanUp request->data 1件
 * @param int $targetCount 削除対象件数
 * @return int 削除対象件数
 */
	private function __deleteUploadFiles($uploadFiles, $cleanUp, $targetCount) {
		foreach ($uploadFiles as $uploadFile) {
			// 削除遅延日
			$delayTime = self::DELETE_DELAY_DAY * 24 * 60 * 60;
			$now = NetCommonsTime::getNowDatetime();
			$delayDate = date('Y-m-d H:i:s',  strtotime($now) - $delayTime);
			//var_dump($delayDate);

			//var_dump($uploadFile['UploadFile']['modified']);
			if($uploadFile['UploadFile']['modified'] >= $delayDate) {
				// 削除遅延日  x日前を例えば1日前を指定すると、今日アップしたファイルは消さなくなります
				continue;
			}

			// このコンテンツでアップロードファイルを使っているかどうか。
			// 該当あり => 該当ファイルは使ってるため削除しない
			if ($this->__isUseUploadFile($uploadFile, $cleanUp)) {
				continue;
			}
			//var_dump($uploadFile);

			// お知らせウィジウィグでファイルアップした場合、upload_files_contentsにデータはできなかったため、
			// とりあえずupload_files_contents削除処理は、なしで進める。

			// ファイル削除
			$pluginName = $cleanUp['Plugin']['name'];
			$model = $cleanUp['CleanUp']['model'];
			$fileName = $uploadFile['UploadFile']['original_name'];
			if ($this->__deleteUploadFile($uploadFile) === false) {
				CakeLog::info(__d('clean_up', '[%s:%s] 「%s」の削除に失敗しました',
					[$pluginName, $model, $fileName]), ['CleanUp']);
			} else {
				CakeLog::info(__d('clean_up', '[%s:%s] 「%s」を削除しました',
					[$pluginName, $model, $fileName]), ['CleanUp']);
			}
			$targetCount++;
		}
		return $targetCount;
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
		// Wysiwyg独自でサムネイルにbiggestを設定している。biggestを設定すると、biggestも削除される。
		$thumbnailSizes = $this->UploadFile->actsAs['Upload.Upload']['real_file_name']['thumbnailSizes'];
		$thumbnailSizes['biggest'] = '1200ml';
		$this->UploadFile->uploadSettings('real_file_name', 'thumbnailSizes', $thumbnailSizes);

		return $this->UploadFile->delete($fileId, false);
	}

/**
 * このコンテンツでアップロードファイルを使っているかどうか。
 *
 * @param array $uploadFile UploadFile
 * @param array $cleanUp [CleanUp][...]
 * @return bool true:使ってる|false:使ってない
 */
	private function __isUseUploadFile($uploadFile, $cleanUp) {
		//private function __isUseUploadFile($uploadFile, $cleanUp, $class, $fields) {
		//* @param string $class クラス名
		//* @param string $fields フィールド名
		if ($cleanUp['CleanUp']['plugin_key'] == self::PLUGIN_KEY_UNKNOWN) {
			// プラグイン不明ファイルは、ブロックキーなしやコンテンツキーなしで、使われていないため、false
			return false;
		}

		$model = $cleanUp['CleanUp']['model'];
		$class = $cleanUp['CleanUp']['class'];
		$fields = $cleanUp['CleanUp']['fields'];
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
		// CakeLog::infoをよびだし、debug.logとCleanUp.logの両方出力するようにした。
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
