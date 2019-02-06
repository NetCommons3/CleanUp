<?php
/**
 * CleanUpShell::clean_up()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpConsoleTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpShell::clean_up()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Console\Command\CleanUpShell
 */
class CleanUpConsoleCommandCleanUpShellCleanUpTest extends CleanUpConsoleTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
		'plugin.plugin_manager.plugin4test',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * Shell name
 *
 * @var string
 */
	protected $_shellName = 'CleanUpShell';

/**
 * clean_up()のテスト
 *
 * @return void
 */
	public function testCleanUp() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell);

		//データ生成
		//$this->$shell->args[] = 'all';
		//$this->$shell->args[] = 'announcements';
		$this->$shell->args[] = 'unknown';

		//チェック
		$this->$shell->expects($this->at(0))->method('out')
			->with('Success!!');

		//テスト実施
		$this->$shell->clean_up();
	}

/**
 * clean_up() 引数なしエラー
 *
 * @return void
 */
	public function testCleanUpValidationErrors() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell);

		//チェック
		$this->$shell->expects($this->at(0))->method('out')
			->with('[ValidationErrors]');

		//テスト実施
		$this->$shell->clean_up();
	}

}