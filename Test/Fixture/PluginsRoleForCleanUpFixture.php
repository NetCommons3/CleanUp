<?php
/**
 * PluginsRoleForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * PluginsRoleForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\PluginManager\Test\Fixture
 * @see PluginsRoleFixture からコピー
 */
class PluginsRoleForCleanUpFixture extends CakeTestFixture {

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
	public $table = 'plugins_role';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'role_key' => 'system_administrator',
			//'plugin_key' => 'test_plugin',
			'plugin_key' => 'clean_up',
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('PluginManager') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new PluginManagerSchema())->tables[Inflector::tableize($this->name)];
		//		if (class_exists('NetCommonsTestSuite') && NetCommonsTestSuite::$plugin) {
		//			//var_dump($this->records, NetCommonsTestSuite::$plugin);
		//			$records = array_keys($this->records);
		//			foreach ($records as $i) {
		//				if ($this->records[$i]['plugin_key'] === 'test_plugin') {
		//					$this->records[$i]['plugin_key'] = NetCommonsTestSuite::$plugin;
		//				}
		//			}
		//		}
		//		//var_dump($this->records, NetCommonsTestSuite::$plugin);
		parent::init();
	}

}
