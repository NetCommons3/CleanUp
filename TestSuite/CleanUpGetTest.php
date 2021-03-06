<?php
/**
 * CleanUpGetTest TestCase
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

//@codeCoverageIgnoreStart;
App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');
//@codeCoverageIgnoreEnd;
App::uses('CleanUpExec', 'CleanUp.Lib');
App::uses('CleanUpLog', 'CleanUp.Lib');

/**
 * CleanUpGetTest TestCase
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\TestSuite
 * @codeCoverageIgnore
 */
abstract class CleanUpGetTest extends NetCommonsGetTest {

	// ローカルテストでは下記で大丈夫だけど、travisでなぜかデータを取得できないエラーが出るためコメントアウト
	///**
	// * Fixtures
	// *
	// * @var array
	// * @see NetCommonsCakeTestCase::$_defaultFixtures 大量のFixturesを一部にする
	// */
	//	protected $_defaultFixtures = array(
	//		'plugin.m17n.language',
	//	);

/**
 * Fixtures
 *
 * @var array
 */
	private $__fixtures = array(
		'plugin.clean_up.clean_up',
		'plugin.clean_up.schema_migration',
		'plugin.clean_up.nc2_to_nc3_map',
		'plugin.plugin_manager.plugin4test',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * Fixtures load
 *
 * @param string $name The name parameter on PHPUnit_Framework_TestCase::__construct()
 * @param array  $data The data parameter on PHPUnit_Framework_TestCase::__construct()
 * @param string $dataName The dataName parameter on PHPUnit_Framework_TestCase::__construct()
 * @return void
 */
	public function __construct($name = null, array $data = array(), $dataName = '') {
		if (! isset($this->fixtures)) {
			$this->fixtures = array();
		}
		$this->fixtures = array_merge($this->__fixtures, $this->fixtures);
		parent::__construct($name, $data, $dataName);
	}

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		// テスト時はログ出力しない
		CakeLog::drop(CleanUpLog::LOGGER_KEY);
	}
}