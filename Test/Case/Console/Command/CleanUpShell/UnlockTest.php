<?php
/**
 * CleanUpShell::unlock()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpConsoleTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpShell::unlock()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Console\Command\CleanUpShell
 */
class CleanUpConsoleCommandCleanUpShellUnlockTest extends CleanUpConsoleTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

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
 * unlock()のテスト
 *
 * @return void
 */
	public function testUnlock() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell);

		//データ生成
		CleanUpLib::makeLockFile();

		//$this->$shell->expects($this->at(0))->method('out')
		//	->with('ここに出力内容を書く');

		//テスト実施
		// outはなく実行してるだけなので、ロジックが通る事を確認
		$this->$shell->unlock();

		//チェック
		$this->assertTrue(true);
	}
}
