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
 * use helpers
 *
 * @var array
 * @see NetCommonsAppController::$helpers
 */
	public $helpers = array(
		'CleanUp.CleanUpForm'
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
			$data = $this->request->data;
			//var_dump($data);
			if ($this->CleanUp->fileCleanUp($data)) {
				// リダイレクトすると、チェック内容が消えるため、そのままreturn
				//$this->redirect($this->referer());
				// 削除しましたFlashメッセージを設定
				$this->NetCommons->setFlashNotification(
					__d('net_commons', 'Successfully deleted.'), array('class' => 'success')
				);
				return;
			}
			// エラー
			$this->NetCommons->handleValidationError($this->CleanUp->validationErrors);
		} else {
			// チェックボックス初期値
			$default = Hash::extract($cleanUps, '{n}.CleanUp.plugin_key');
			$this->request->data['CleanUp']['plugin_key'] = $default;
		}
	}
}
