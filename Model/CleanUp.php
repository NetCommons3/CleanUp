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
App::uses('NetCommonsTime', 'NetCommons.Utility');
App::uses('CleanUpLog', 'CleanUp.Lib');
App::uses('CleanUpLockFile', 'CleanUp.Lib');

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
 * 削除遅延日 x日前<br />
 * 例えば1日前を指定すると、今日アップしたファイルは消さなくなります
 *
 * @var string
 */
	public $deleteDelayDay = 0;

/**
 * 削除する拡張子の区切り文字
 *
 * @var string
 */
	const DELETE_EXTENSION_DELIMITER = ',';

/**
 * 削除する拡張子<br />
 * (例 jpg、複数はカンマ区切り、空なら全ての拡張子が対象)
 *
 * @var string
 */
	public $deleteExtension = '';

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
	public function __construct() {
		parent::__construct();

		// ログ設定
		CleanUpLog::setupLog();
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
		//$this->validate = Hash::merge(array(
		$this->validate = array_merge_recursive(array(
			'plugin_key' => array(
				'multiple' => array(
					'rule' => array('multiple', array('min' => 1)),
					// plugin
					'message' => __d('net_commons', 'Please input %s.',
									__d('clean_up', 'Plugin')),
					'required' => false,
				),
				'isLockFile' => array(
					'rule' => array('isLockFile'),
					// 多言語再利用のため
					// @codingStandardsIgnoreStart
					'message' => __d('clean_up', 'There is a lock file. Please wait for a while because file cleanup is in progress.'),
					// @codingStandardsIgnoreEnd
					'required' => false,
				),
			),
		), $this->validate);

		return parent::beforeValidate($options);
	}

// @codingStandardsIgnoreStart
/**
 * 独自バリデーション<br />
 * isLockFile ロックファイルの存在確認
 *
 * @param array $check チェック値
 * @return bool
 */
	public function isLockFile($check) {
		// @codingStandardsIgnoreEnd
		return !CleanUpLockFile::isLockFile();
	}

/**
 * ファイルクリーンアップ対象プラグインデータ ゲット
 *
 * @param array $data received post data. ex) ['CleanUp']['plugin_key'][] = 'announcements'
 * @return array
 */
	public function findCleanUpsAndPlugin($data = null) {
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
			'fields' => array(
				'CleanUp.plugin_key',
				'CleanUp.model',
				'CleanUp.class',
				'CleanUp.fields',
				'Plugin.key',
				'Plugin.name',
			),
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
 * プラグイン不明ファイル データゲット
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
				'name' => __d('clean_up', 'Plugin unknown file'),
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
	public function findCleanUpsAndUnknow($data = null) {
		$cleanUps = $this->findCleanUpsAndPlugin($data);
		$cleanUps[] = $this->getUnknowCleanUp();
		return $cleanUps;
	}

/**
 * 入力チェックのみ行う
 *
 * @param array $data received post data. ['CleanUp']['plugin_key'][] = 'announcements'
 * @return mixed On success Model::$data if its not empty or true, false on failure
 */
	public function validatesOnly($data) {
		//バリデーション
		$this->set($data);
		/* @see beforeValidate() */
		if (!$this->validates()) {
			return false;
		}

		// バックグラウンドでファイルクリーンアップ
		//CleanUpExec::cleanUp($data);
		return true;
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

		//バリデーション
		$this->set($data);
		/* @see beforeValidate() */
		if (! $this->validates()) {
			return false;
		}
		// タイムゾーンを日本に一時的に変更。ログ出力時間を日本時間に。
		$timezone = CleanUpLog::startLogTimezone();
		CakeLog::info(__d('clean_up', 'Start cleanup process.'), ['CleanUp']);

		// 複数起動防止ロック
		CleanUpLockFile::makeLockFile();

		// ファイルクリーンアップ対象のプラグイン設定を取得
		$cleanUps = $this->findCleanUpsAndPlugin($data);
		foreach ($data['CleanUp']['plugin_key'] as $pluginKey) {
			// プラグイン不明ファイルがチェックされてたら、プラグイン不明ファイル データ追加
			if ($pluginKey == self::PLUGIN_KEY_UNKNOWN) {
				$cleanUps[] = $this->getUnknowCleanUp();
				break;
			}
		}

		try {
			foreach ($cleanUps as $cleanUp) {
				// 削除対象件数
				$targetCount = 0;
				//var_dump($cleanUp);
				$pluginName = $cleanUp['Plugin']['name'];
				$model = $cleanUp['CleanUp']['model'];

				CakeLog::info(__d('clean_up', '[%s:%s] Start the cleanup process.',
					[$pluginName, $model]), ['CleanUp']);

				//アップロードファイルのfind条件 ゲット
				$params = $this->getUploadFileParams($cleanUp);
				// $uploadFiles findでデータとれすぎてメモリ圧迫問題対応。 1000件づつ取得
				$params = array_merge($params, array('limit' => self::FIND_LIMIT_UPLOAD_FILE, 'offset' => 0));

				while ($uploadFiles = $this->UploadFile->find('all', $params)) {
					// ファイル削除
					$targetCount = $this->__deleteUploadFiles($uploadFiles, $cleanUp, $targetCount);
					// 次のn件取得
					$params['offset'] = self::FIND_LIMIT_UPLOAD_FILE;
				}

				if ($targetCount === 0) {
					CakeLog::info(__d('clean_up',
						'[%s:%s] There was no target file.',
						[$pluginName, $model]), ['CleanUp']);
				} else {
					CakeLog::info(__d('clean_up',
						'[%s:%s] Cleanup processing is completed.',
						[$pluginName, $model]), ['CleanUp']);
				}
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			// ロック解除
			CleanUpLockFile::deleteLockFile();
			// 言語ファイル修正予定
			CakeLog::info(__d('clean_up',
				'Cleanup processing terminated abnormally.'), ['CleanUp']);
			// タイムゾーンを元に戻す
			CleanUpLog::endLogTimezone($timezone);
			//トランザクションRollback
			$this->rollback($ex);
		}
		// ロック解除
		CleanUpLockFile::deleteLockFile();
		CakeLog::info(__d('clean_up', 'Cleanup processing is completed.'), ['CleanUp']);
		// タイムゾーンを元に戻す
		CleanUpLog::endLogTimezone($timezone);

		return true;
	}

/**
 * アップロードファイルのfind条件 ゲット
 * テスト利用のためpublicに変更
 *
 * @param array $cleanUp request->data 1件
 * @return array
 * @see UploadFile::deleteUploadFile() よりコピー
 */
	public function getUploadFileParams($cleanUp) {
		$fields = array(
			'UploadFile.id',
			'UploadFile.room_id',
			'UploadFile.content_key',
			'UploadFile.path',
			'UploadFile.original_name',
			'UploadFile.modified',
		);

		if ($cleanUp['CleanUp']['plugin_key'] == self::PLUGIN_KEY_UNKNOWN) {
			// プラグイン不明ファイル
			$params = $this->getUploadFileUnKnownParams($fields);
		} else {
			// block_keyあり、content_keyあり、コンテンツあり
			$params = array(
				'recursive' => -1,
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
				'fields' => $fields,
				'order' => 'UploadFile.id'
			);
		}

		// 削除する拡張子が設定されていたら条件追加（空なら条件セットしない＝全ての拡張子が対象）
		if ($this->deleteExtension) {
			$deleteExtensionArray =
				explode(self::DELETE_EXTENSION_DELIMITER, $this->deleteExtension);
			$params['conditions'][$this->UploadFile->alias . '.extension'] = $deleteExtensionArray;
		}
		return $params;
	}

/**
 * プラグイン不明ファイルのアップロードファイルのfind条件 ゲット
 *
 * @param array $fields DB項目
 * @return array find条件
 */
	public function getUploadFileUnKnownParams($fields) {
		// プラグイン不明ファイル
		//
		// block_keyなし or content_keyなし
		// block_keyなしの場合、どのプラグインから投稿されたか不明
		// この対象データは、this->__isUseUploadFile()チェック不要。block_keyなし、content_keyなしで使われてない事がわかっているため。
		$params = array(
			'recursive' => -1,
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
			'fields' => $fields,
			'order' => 'UploadFile.id'
		);
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
			if ($this->__isOverDelayDate($uploadFile)) {
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
				CakeLog::info(__d('clean_up', '[%s:%s]  Failed to delete "%s".',
					[$pluginName, $model, $fileName]), ['CleanUp']);
			} else {
				CakeLog::info(__d('clean_up', '[%s:%s] "%s" deleted.',
					[$pluginName, $model, $fileName]), ['CleanUp']);
			}
			$targetCount++;
		}
		return $targetCount;
	}

/**
 * 削除遅延日以上かどうか<br />
 * 該当するファイルは削除対象外になります
 *
 * @param array $uploadFile UploadFile
 * @return bool true:削除遅延日以上|false:使ってない
 */
	private function __isOverDelayDate($uploadFile) {
		// 削除遅延日
		$delayTime = $this->deleteDelayDay * 24 * 60 * 60;

		$now = NetCommonsTime::getNowDatetime();
		$delayDate = date('Y-m-d H:i:s', strtotime($now) - $delayTime);
		//var_dump($delayDate);

		//var_dump($uploadFile['UploadFile']['modified']);
		if ($uploadFile['UploadFile']['modified'] >= $delayDate) {
			// 削除遅延日  x日前を例えば1日前を指定すると、今日アップしたファイルは消さなくなります
			return true;
		}
		return false;
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

		// fileUrl, imageUrl使ってる条件
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

		// 最新とアクティブを取得する条件（多言語も取得される）
		//
		// 多言語
		// announcementsテーブルで日英で同じkeyで2件ある場合の例。
		// id,language_id,block_id,key,status,is_active,is_latest,is_origin,is_translation,is_original_copy,content,created_user,created,modified_user,modified
		// 24,2,6,9b73e6340136d5e86e631696f3fe859e,1,1,1,1,1,0,"<img class="img-responsive nc3-img nc3-img-block" title="" src="{{__BASE_URL__}}/wysiwyg/image/download/1/" alt="" /><p> 日本語画像使ってる</p>",1,"2019-01-25 04:46:16",1,"2019-02-20 06:25:43"
		// 25,1,6,9b73e6340136d5e86e631696f3fe859e,1,1,1,0,1,0,"<p>英語　画像削除</p>",1,"2019-01-25 04:46:16",1,"2019-02-20 06:26:35"
		// ※日本語（language_id=2）のcontentで画像は使っていて、英語（language_id=1）は画像削除した。
		// ※日英ともに、is_active=1 and is_latest=1がありえる。つまり同じkeyでis_active=1 and is_latest=1が2件ある状態。
		// 多言語であっても、is_active=1 or is_latest=1で画像orファイル使っているかの対象になり、該当すればcountされる。
		// そのため、多言語（language_id=1 or 2）でもis_active=1 or is_latest=1でチェック対象になってる
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
		//var_dump($this->$model->find('all'));

		if ($count) {
			return true;
		}
		return false;
	}

}
