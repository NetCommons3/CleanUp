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

	///**
	// * use components
	// *
	// * @var array
	// * @see NetCommonsAppController::$components
	// * @see ContentCommentsComponent::beforeRender()
	// *
	// */
	//	public $components = array(
	//	);

/**
 * beforeFilter
 *
 * @return void
 * @see NetCommonsAppController::beforeFilter()
 */
	public function beforeFilter() {
		// ログ出力設定
		$this->CleanUp->setupLog();
		parent::beforeFilter();
	}

/**
 * 削除
 *
 * @return CakeResponse
 * @throws Exception
 */
	public function delete() {
		$cleanUps = $this->CleanUp->getCleanUpsAndPlugin();
		$cleanUps[] = $this->CleanUp->getUnknowCleanUp();
		// 'multiple' => 'checkbox'表示用
		$this->set('cleanUps', $cleanUps);

		if ($this->request->is('post')) {
			$data = $this->request->data;
			//var_dump($data);
			if ($this->CleanUp->fileCleanUp($data)) {
				// success画面へredirect
				//$this->redirect($this->referer());
				return;
			}
			// エラー
			$this->NetCommons->handleValidationError($this->CleanUp->validationErrors);
			//CakeLog::info('[ValidationErrors] ' . $this->request->here(), ['CleanUp']);
			//CakeLog::info(print_r($this->CleanUp->validationErrors, true), ['CleanUp']);
			CakeLog::info('[ValidationErrors] ' . $this->request->here());
			CakeLog::info(print_r($this->CleanUp->validationErrors, true));
		} else {
			// チェックボックス初期値
			$default = Hash::extract($cleanUps, '{n}.CleanUp.plugin_key');
			$this->request->data['CleanUp']['plugin_key'] = $default;
		}

		//		if (! $this->request->is('delete')) {
		//			return $this->throwBadRequest();
		//		}
		//
		//		$video = $this->Video->getWorkflowContents('first', array(
		//			'recursive' => 1,
		//			'conditions' => array(
		//				$this->Video->alias . '.key' => $this->data['Video']['key']
		//			)
		//		));
		//
		//		//削除権限チェック
		//		if (! $this->Video->canDeleteWorkflowContent($video)) {
		//			return $this->throwBadRequest();
		//		}
		//
		//		// 削除
		//		if (!$this->Video->deleteVideo($this->data)) {
		//			return $this->throwBadRequest();
		//		}
		//
		//		// 一覧へ
		//		$url = NetCommonsUrl::actionUrl(array(
		//			'controller' => 'videos',
		//			'action' => 'index',
		//			'block_id' => $this->data['Block']['id'],
		//			'frame_id' => $this->data['Frame']['id'],
		//		));
		//		$this->redirect($url);
	}
}
