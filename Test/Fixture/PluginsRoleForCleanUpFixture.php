<?php
/**
 * PluginsRoleForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('PluginsRoleFixture', 'PluginManager.Test/Fixture');

/**
 * PluginsRoleForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\PluginManager\Test\Fixture
 */
class PluginsRoleForCleanUpFixture extends PluginsRoleFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'PluginsRole';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'plugins_roles';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'role_key' => 'system_administrator',
			'plugin_key' => 'clean_up',
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		parent::init();
	}

}
