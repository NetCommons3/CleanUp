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
		// 'multiple' => 'checkbox'表示用
		$this->set('cleanUps', $cleanUps);

		if ($this->request->is('post')) {
			// TODO 仮で画面からのみ30分設定
			set_time_limit(1800);

			$data = $this->request->data;
			//var_dump($data);
			if ($this->CleanUp->fileCleanUp($data)) {
				// リダイレクトすると、チェック内容が消えるため、そのままreturn
				//$this->redirect($this->referer());
				// 削除しましたFlashメッセージを設定
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully deleted.'), array('class' => 'success')
				);
			} else {
				// エラー
				$this->NetCommons->handleValidationError($this->CleanUp->validationErrors);
			}
		} elseif ($this->request->is('ajax')) {
			// ajaxの場合は何もしない
		} else {
			// チェックボックス初期値
			$default = Hash::extract($cleanUps, '{n}.CleanUp.plugin_key');
			$this->request->data['CleanUp']['plugin_key'] = $default;
		}

		// ログファイル名
		$logFileNames = $this->__getLogFileNames();
		$this->set('logFileNames', $logFileNames);

		// ログの内容
		$cleanUpLog = $this->__getleanUpLog();
		$this->set('cleanUpLog', $cleanUpLog);
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
			if (strpos($file,'CleanUp.log') !== false) {
				$logFileNames[] = $file;
			}
		}

		// 空の場合セット
		if (empty($logFileNames)) {
			$logFileNames[] = 'CleanUp.log';
		}
		return $logFileNames;
	}

/**
 * ログの内容
 *
 * @return string ログの内容
 */
	private function __getleanUpLog() {
		$logFileNo = isset($this->params['named']['logFileNo'])
			? $this->params['named']['logFileNo']
			: 0;

		if ($logFileNo == 0) {
			$logFile = 'CleanUp.log';
		} else {
			$logFile = 'CleanUp.log.' . $logFileNo;
		}
		$logPath = ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'logs' . DS . $logFile;

		$cleanUpLog = '';
		if (file_exists($logPath)) {
			$cleanUpLog = file_get_contents($logPath);
		} else {
			$cleanUpLog = __d('clean_up', 'ありません');
		}
		return $cleanUpLog;
	}

}
