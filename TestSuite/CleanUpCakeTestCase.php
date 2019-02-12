<?php
/**
 * CleanUpCakeTestCase TestCase
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

//@codeCoverageIgnoreStart;
App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
//@codeCoverageIgnoreEnd;
App::uses('CleanUpUtility', 'CleanUp.Utility');

/**
 * CleanUpCakeTestCase TestCase
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\TestSuite
 * @codeCoverageIgnore
 */
abstract class CleanUpCakeTestCase extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 * @see NetCommonsCakeTestCase::$_defaultFixtures 大量のFixturesを空にする
 */
	protected $_defaultFixtures = array();

/**
 * Fixtures
 *
 * @var array
 */
	private $__fixtures = array();

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
		CleanUpUtility::$isOutputLog = false;

		// ロックファイルの出力先をtestに変更
		CleanUpUtility::$lockFilePath =
			ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'tests' . DS . 'CleanUp.lock';
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		// テスト後に必ずロックファイルあってもなくても削除する
		CleanUpUtility::deleteLockFile();

		parent::tearDown();
	}
}