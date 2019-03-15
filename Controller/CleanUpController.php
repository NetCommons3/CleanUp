<?php
/**
 * ファイルクリーンアップ Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpAppController', 'CleanUp.Controller');
App::uses('CleanUp', 'CleanUp.Model');
App::uses('CleanUpUtility', 'CleanUp.Utility');
App::uses('Folder', 'Utility');

/**
 * CleanUp Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Controller
 * @property CleanUp $CleanUp
 * @property CakeRequest request
 * @property NetCommonsComponent NetCommons
 */
class CleanUpController extends CleanUpAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'CleanUp.CleanUp'
	);

/**
 * 削除
 *
 * @return CakeResponse
 * @throws Exception
 */
	public function delete() {
		$cleanUps = $this->CleanUp->getCleanUpsAndUnknow();
		// 'multiple' => 'checkbox'表示
		$this->set('cleanUps', $cleanUps);

		if ($this->request->is('post')) {
			$data = $this->request->data;
			//var_dump($data);
			//if ($this->CleanUp->fileCleanUp($data)) {
			if ($this->CleanUp->validatesOnly($data)) {
				// バックグラウンドでファイルクリーンアップ
				CleanUpUtility::cleanUp($data);

				// リダイレクトすると、チェック内容が消えるため、そのままreturn
				//$this->redirect($this->referer());
				// 削除しましたFlashメッセージを設定
				$this->NetCommons->setFlashNotification(
					__d('clean_up', 'File cleanup was executed. Please check the execution result and wait for a while until completion.'), array('class' => 'success')
				);
			} else {
				// エラー
				$this->NetCommons->handleValidationError($this->CleanUp->validationErrors);
			}
		} elseif ($this->request->is('ajax')) {
			// ログの内容(実行結果)見る場合ajax. ajaxは何もしない
		} else {
			// チェックボックス初期値
			//$default = Hash::extract($cleanUps, '{n}.CleanUp.plugin_key');
			$default = [];
			foreach ($cleanUps as $cleanUp) {
				$default[] = $cleanUp['CleanUp']['plugin_key'];
			}
			$this->request->data['CleanUp']['plugin_key'] = $default;
		}

		// ログファイル名
		$logFileNames = $this->__getLogFileNames();
		$this->set('logFileNames', $logFileNames);

		// ログの内容
		$cleanUpLog = $this->__getCleanUpLog();
		$this->set('cleanUpLog', $cleanUpLog);

		// ロックファイル関係
		$this->set('isLockFile', CleanUpUtility::isLockFile());
		$this->set('cleanUpStart', CleanUpUtility::readLockFile());
	}

/**
 * ログ表示 ajaxのみ
 *
 * @return CakeResponse
 * @throws Exception
 */
	public function clean_up_log() {
		//		$cleanUps = $this->CleanUp->getCleanUpsAndUnknow();
		//		// 'multiple' => 'checkbox'表示
		//		$this->set('cleanUps', $cleanUps);

		//		// ログファイル名
		//		$logFileNames = $this->__getLogFileNames();
		//		$this->set('logFileNames', $logFileNames);

		// ログの内容
		$cleanUpLog = $this->__getCleanUpLog();
		$this->set('cleanUpLog', $cleanUpLog);

		//		// ロックファイル関係
		//		$this->set('isLockFile', CleanUpUtility::isLockFile());
		//		$this->set('cleanUpStart', CleanUpUtility::readLockFile());
	}

/**
 * ログファイル名
 *
 * @return array ログファイル名
 */
	private function __getLogFileNames() {
		//インスタンスを作成
		$dir = new Folder(ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'logs' . DS);
		$files = $dir->read();
		$logFileNames = [];
		foreach ($files[1] as $file) {
			if (strpos($file, CleanUpUtility::LOG_FILE_NAME) !== false) {
				$logFileNames[] = $file;
			}
		}

		// 空の場合セット
		if (empty($logFileNames)) {
			$logFileNames[] = CleanUpUtility::LOG_FILE_NAME;
		}
		return $logFileNames;
	}

/**
 * ログの内容
 *
 * @return string ログの内容
 */
	private function __getCleanUpLog() {
		$logFileNo = isset($this->params['named']['logFileNo'])
			? $this->params['named']['logFileNo']
			: 0;

		if ($logFileNo == 0) {
			$logFile = CleanUpUtility::LOG_FILE_NAME;
		} else {
			$logFile = CleanUpUtility::LOG_FILE_NAME . '.' . $logFileNo;
		}
		$logPath = ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'logs' . DS . $logFile;

		$cleanUpLog = '';
		if (file_exists($logPath)) {
			$cleanUpLog = file_get_contents($logPath);
		} else {
			$cleanUpLog = __d('clean_up', 'None.');
		}
		return $cleanUpLog;
	}

/**
 * 実行中ロックファイルの強制削除
 *
 * @return CakeResponse
 * @throws Exception
 */
	public function unlock() {
		if (! $this->request->is('get')) {
			return $this->throwBadRequest();
		}

		// ロックファイルの削除
		if (CleanUpUtility::deleteLockFileAndSetupLog()) {
			// メッセージ
			$this->NetCommons->setFlashNotification(
				__d('clean_up', 'Lock file was deleted.'), array('class' => 'success')
			);
		} else {
			$this->NetCommons->setFlashNotification(
				__d('clean_up', 'No lock file.'), array('class' => 'warning')
			);
		}

		// 画面表示
		$this->delete();
		$this->view = 'delete';
	}

}
