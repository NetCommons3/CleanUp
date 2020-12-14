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
 * @SuppressWarnings(PHPMD.LongVariable)
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
 * 移行ツールによって移行されたアップロードファイルレコードの最後のID<br />
 * このIDより小さいIDのアップロードファイルデータは処理対象外とします<br />
 * 移行ツールによって作成されたアップロードファイルレコードは定型の形になっていない場合があるため
 *
 * @var int
 */
	public $nc2ToNc3UplaodFileMaxId = 0;

/**
 * NC3 3.1.4 相当のファイルがインストールされた日時<br />
 * この日時より以前のアップロードファイルデータは処理対象外とします<br />
 * 3.1.3以前に作成されたアップロードファイルレコードは定型の形になっていないため
 *
 * @var string
 */
	public $nc314InstallDatetime = '';

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
				'CleanUp.alive_func',
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

		// プラグインの中に存在する全モデルでチェックするために
		// プラグイン単位でまとめなおした配列にする
		return $this->find('all', $params);
	}
/**
 * ファイルクリーンアップ対象プラグインデータ 並べ替え
 *
 * @param array $cleanUps CleanUpテーブルから取得したレコード
 * @return array
 */
	private function __combineCleanUpsAndPlugin($cleanUps) {
		$cleanUpsByPlugin = array();
		foreach ($cleanUps as $cleanUp) {
			$pluginKey = $cleanUp['CleanUp']['plugin_key'];
			if (! isset($cleanUpsByPlugin[$pluginKey])) {
				$cleanUpsByPlugin[$pluginKey] = [];
			}
			$cleanUpsByPlugin[$pluginKey][] = $cleanUp;
		}
		return $cleanUpsByPlugin;
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

		// NC3.1.4インストール日時確保
		$this->__setNc314InstallDatetime();

		// 移行ツールによって作成されたアップロードファイルレコードの最大ID確保
		$this->__setNc2ToNc3UploadFileMaxId();

		//トランザクションBegin
		$this->begin();

		// ファイルクリーンアップ対象のプラグイン設定を取得
		// プラグイン単位にまとめなおされたクリーンアップ配列情報を作成
		// プラグイン単位で処理を行わないと間違ったファイルを消す恐れがある
		// UploadFileはどのプラグインで使用している、の情報は載っているが
		// どのモデルで使用しているの情報がないため、間違って巻き込み判断をするので。
		$cleanUps = $this->findCleanUpsAndPlugin($data);
		$cleanUpsByPlugin = $this->__combineCleanUpsAndPlugin($cleanUps);

		try {
			foreach ($cleanUpsByPlugin as $cleanUps) {
				// プラグイン中の代表Model
				// 最低１つは絶対存在するので安心して「０」を取り出している
				$cleanUp = $cleanUps[0];

				// 削除対象件数
				$targetCount = 0;

				$pluginName = $cleanUp['Plugin']['name'];
				$model = $cleanUp['CleanUp']['model'];

				CakeLog::info(__d('clean_up', '[%s:%s] Start the cleanup process.',
					[$pluginName, $model]), ['CleanUp']);

				//アップロードファイルのfind条件 ゲット
				$params = $this->getUploadFileParams($cleanUp);
				// $uploadFiles findでデータとれすぎてメモリ圧迫問題対応。 1000件づつ取得
				$params = array_merge($params, array('limit' => self::FIND_LIMIT_UPLOAD_FILE, 'offset' => 0));

				// このプラグインに関するアップロードファイルが全部見つかる
				while ($uploadFiles = $this->UploadFile->find('all', $params)) {
					// ファイル削除
					// 削除していいかどうかは、このプラグインに関する全Modelを見て判断する[$cleanUps]
					$deleteCount = $this->__deleteUploadFiles($uploadFiles, $cleanUp, $cleanUps);
					// これまでの削除ファイル数
					$targetCount += $deleteCount;
					// 次のn件取得
					$params['offset'] += (self::FIND_LIMIT_UPLOAD_FILE - $deleteCount);
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
			'Block.plugin_key',
		);

		// block_keyあり(Blockと結合するためblock_keyは必ずあり)、content_keyありorなし
		// blockが存在しない場合もあり得るのでleft joinとしている
		//
		// * content_keyなし対象データは、this->__isUseUploadFile()チェック不要。content_keyなしで
		//   使われてない事がわかっているため。
		//
		//  (旧仕様)
		// * block_keyなしのデータが存在する。 3.1.3より以前はblock_keyなしでアップロードされていたため、
		//   そのデータは削除対象にしない。
		// * ↑
		//   それをしてしまうとブロック本体を削除したような場合に一気に大量の浮きリソースが発生します。
		//  (新仕様)
		//   wysiwygのupload_filesでblock_keyがNULL（空）のもの、また、JOIN相手のblocksレコードが見つからないものは
		//   削除対象とします
		//   しかし、そのままやると3.1.3以前のバージョンで運営されていたUPファイルを消してしまう可能性があるので
		//   3.1.3以前のバージョンでUPされていたファイルは消さないように3.1.3以前にUPされていたファイルは
		//   CleanUp処理対象外とするようにします
		//   また、移行ツールで移行されたファイルは既存バグのために小テスト、アンケートプラグインでblock_keyがNULLの
		//   レコードが発生している可能性があります。
		//   移行ファイルについては処理対象外とします
		// * block_keyなしは、blockテーブルと結合できないため、どのプラグインから投稿されたかわからない。

		// 移行ファイルによって移行されたupload_filesのIDを調べ、移行ツールで作成されたupload_filesは処理対象外にする
		// 3.1.4がインストールされた日時を取得し、それ以前に作成されたupload_filesは処理対象外にする

		$params = array(
			'recursive' => -1,
			'conditions' => array(
				$this->UploadFile->alias . '.plugin_key' => 'wysiwyg',
				$this->UploadFile->alias . '.id >' => $this->nc2ToNc3UplaodFileMaxId,
				$this->UploadFile->alias . '.created >' => $this->nc314InstallDatetime,
				'OR' => array(
					'Block.plugin_key' => $cleanUp['CleanUp']['plugin_key'],
					'Block.plugin_key is null',
				)
			),
			'joins' => array(
				array('table' => 'blocks',
					'alias' => 'Block',
					'type' => 'left',
					'conditions' => array(
						$this->UploadFile->alias . '.block_key = Block.key',
					)
				)
			),
			'fields' => $fields,
			'order' => 'UploadFile.id'
		);

		// 削除する拡張子が設定されていたら条件追加（空なら条件セットしない＝全ての拡張子が対象）
		if ($this->deleteExtension) {
			$deleteExtensionArray =
				explode(self::DELETE_EXTENSION_DELIMITER, $this->deleteExtension);
			$params['conditions'][$this->UploadFile->alias . '.extension'] = $deleteExtensionArray;
		}
		return $params;
	}

/**
 * Delete uploadFiles
 *
 * @param array $uploadFiles UploadFiles
 * @param array $cleanUp request->data 1件
 * @param array $cleanUps cleanUpで指定されたモデルが属するプラグインがチェックすべき全モデル情報
 * @return int 削除した件数
 */
	private function __deleteUploadFiles($uploadFiles, $cleanUp, $cleanUps) {
		$deletedCount = 0;
		foreach ($uploadFiles as $uploadFile) {
			if ($this->__isOverDelayDate($uploadFile)) {
				// 削除遅延日  x日前を例えば1日前を指定すると、今日アップしたファイルは消さなくなります
				continue;
			}

			// Block情報があるときだけ使用中判定関数を行う
			// Blockがないのはもう持ち主もわからない不使用ファイルです
			if (! empty($uploadFile['Block']['plugin_key'])) {
				// このコンテンツでアップロードファイルを使っているかどうか。
				// 該当あり => 該当ファイルは使ってるため削除しない
				if ($this->__isUseUploadFile($uploadFile, $cleanUps)) {
					continue;
				}
				$pluginName = $cleanUp['Plugin']['name'];
			} else {
				$pluginName = '----';
			}

			// お知らせウィジウィグでファイルアップした場合、upload_files_contentsにデータはできなかったため、
			// upload_files_contents削除処理は、なし

			// ファイル削除
			// 以前はこのメッセージに「モデル名」も出力されていた
			// しかし、１プラグイン中に複数モデルが登録される場合、どのモデルでファイル削除したのかがわからない
			// (upload_filesテーブルにJOINするblocksテーブルには「プラグイン名」しか記載されていないため)
			// よってメッセージからモデル名を削除する
			$fileName = $uploadFile['UploadFile']['original_name'];
			if ($this->__deleteUploadFile($uploadFile) === false) {
				CakeLog::info(__d('clean_up', '[%s]  Failed to delete "%s".',
					[$pluginName, $fileName]), ['CleanUp']);
			} else {
				CakeLog::info(__d('clean_up', '[%s] "%s" deleted.',
					[$pluginName, $fileName]), ['CleanUp']);
			}
			$deletedCount++;
		}
		return $deletedCount;
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
 * @param array $cleanUps [n][CleanUp][...]
 * @return bool true:使ってる|false:使ってない
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	private function __isUseUploadFile($uploadFile, $cleanUps) {
		// こちらの関数でブロックNULL判断でfalse返す処理を入れていると
		// Call元の方では誤ったプラグイン名の名前を付けてログを出力してしまう
		// (Blockが存在しないのだから、持ち主不明のはず。
		//  しかしCall元はプラグイン順番で処理しているので処理中プラグインの判断としてしまう)
		// Block有無判断だけは__deleteUploadFilesで行う

		if (! $uploadFile['UploadFile']['content_key']) {
			// 既存バグでコンテンツキーなしで登録されているデータがすでに発生している可能性があります
			// 使用中ファイルを消さないようにするため、安全を考え、ノーチェックでtrueリターンする
			return true;
		}

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

		$count = 0;
		// プラグイン単位でまとめられた全Modelについてチェックする
		foreach ($cleanUps as $cleanUp) {
			$model = $cleanUp['CleanUp']['model'];
			$class = $cleanUp['CleanUp']['class'];
			$fields = $cleanUp['CleanUp']['fields'];
			$func = $cleanUp['CleanUp']['alive_func'];

			$this->loadModels(array(
				$model => $class,
			));

			// fileUrl, imageUrl使ってる条件
			$checkConditions = [];
			$fieldsArray = explode(self::FIELD_DELIMITER, $fields);
			foreach ($fieldsArray as $field) {
				$field = trim($field);
				$checkConditions['OR'][]
						= array($this->$model->alias . '.' . $field . ' LIKE' => '%' . $checkFileUrl . '%');
				$checkConditions['OR'][]
						= array($this->$model->alias . '.' . $field . ' LIKE' => '%' . $checkImageUrl . '%');
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

			// is_activeやis_latestフィールドを持たないModelもある
			// そのようなイリーガルなModelは自分自身で独自の生死判断メソッドをもつことが要求される
			// かつ、その生死判断メソッドをclean_upsテーブルのalive_funcフィールドに登録する
			if ($func) {
				// 生死判断関数をテーブルに登録しているのに実装していない！
				if (! method_exists($this->$model, $func)) {
					// エラーログを出力して、
					CakeLog::error(__d('clean_up', '[%s:%s] model does not have method.',
					$this->$model->alias, $func));
					// 実情判断できないので、使用中ということにする
					return true;
				}
				$judgeConditions = $this->$model->$func($uploadFile['UploadFile']['content_key']);
				$conditions = array_merge_recursive(
					Hash::get($judgeConditions, 'conditions', array()),
					array($checkConditions));
				$joins = Hash::get($judgeConditions, 'joins', array());
			} else {
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
				$joins = array();
			}
			try {
				// 多言語, 最新とアクティブ, のコンテンツで複数件ある
					$count = $this->$model->find('count',
						array_merge(
							array('recursive' => -1),
							array('conditions' => $conditions),
							array('joins' => $joins)
						)
					);
					if ($count > 0) {
						return true;
					}
					//var_dump($this->$model->find('all'));
			} catch (Exception $e) {
				// チェック用フォーマットの記述ミスがあった場合、SQLエラーになる可能性がある
				// SQLエラーでファイルを削除してしまうのはよくないので、とりあえず使用中とする
				// SQLエラーの内容はログに残しておく
				CakeLog::error($e->getMessage());
				return true;
			}
		}
		return false;
	}

/**
 * NC3.1.4のファイルがインストールされた日時を確保する。
 * NC3.1.4より前のバージョンではupload_filesのblock_keyがNULLになっている
 * block_keyがないからと言ってCleanUpしてしまうとまだ使用中の可能性があるので
 * 処理対象外にしたい。
 * その処理対象外にするための基準日時を確定するために前もって調べて確保しておく。
 *
 * @return void
 */
	private function __setNc314InstallDatetime() {
		$this->loadModels(array(
			'SchemaMigration' => 'CleanUp.CleanUpSchemaMigration',
		));
		$record = $this->SchemaMigration->find('first', array(
			'conditions' => array(
				'class' => 'GuestRecords',
				'type' => 'UserRoles'
			),
			'recursive' => -1
		));
		// このMigrationが実行されていないということは
		// 3.1.4がまだインストールされていないということです
		if (! $record) {
			// なにも削除してはいけない
			// これより前の時間のファイルは削除してはいけない、という
			// 基準日時を現在時刻する
			$this->nc314InstallDatetime = (new NetCommonsTime())->getNowDatetime();
		} else {
			// これより前に作成されたファイルは処理対象外にする
			$this->nc314InstallDatetime = $record['SchemaMigration']['created'];
		}
	}
/**
 * 移行ツールで作成されたupload_filesのデータのうち最大のIDを確保する。
 * 移行ツールで作成されたupload_filesはblock_keyがNULLになっているものがある
 * block_keyがないからと言ってCleanUpしてしまうとまだ使用中の可能性があるので
 * 処理対象外にしたい。
 * その処理対象外にするためのIDを確定するために前もって調べて確保しておく。
 *
 * @return void
 */
	private function __setNc2ToNc3UploadFileMaxId() {
		$this->loadModels(array(
			'Nc2ToNc3Map' => 'Nc2ToNc3.Nc2ToNc3Map',
		));
		$record = $this->Nc2ToNc3Map->find('first', array(
			'fields' => array('MAX(nc3_id) AS max_nc3_id'),
			'conditions' => array(
				'model_name' => 'UploadFile',
			),
			'recursive' => -1
		));
		if (empty($record[0]['max_nc3_id'])) {
			// 移行されたファイルはない
			// uplaod_filesテーブルに入っているデータはすべて処理対象にしてよい
			$this->nc2ToNc3UplaodFileMaxId = 0;
		} else {
			// これより小さいIDのファイルは処理対象外にする
			$this->nc2ToNc3UplaodFileMaxId = $record[0]['max_nc3_id'];
		}
	}
}
