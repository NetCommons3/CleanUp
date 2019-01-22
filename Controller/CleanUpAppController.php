<?php
/**
 * CleanUpApp Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * CleanUpApp Controller
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\Videos\Controller
 */
class CleanUpAppController extends AppController {

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'ControlPanel.ControlPanelLayout',
		//アクセスの権限
		'NetCommons.Permission' => array(
			'type' => PermissionComponent::CHECK_TYPE_SYSTEM_PLUGIN,
			'allow' => array()
		),
		'Security',
	);
}
