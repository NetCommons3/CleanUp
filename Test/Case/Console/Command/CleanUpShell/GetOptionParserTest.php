<?php
/**
 * CleanUpShell::getOptionParser()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpConsoleTestCase', 'CleanUp.TestSuite');

/**
 * CleanUpShell::getOptionParser()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Console\Command\CleanUpShell
 */
class CleanUpConsoleCommandCleanUpShellGetOptionParserTest extends CleanUpConsoleTestCase {

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
 * getOptionParser()のテスト<br />
 * Help確認とコードカバレッジ対応のためのテスト
 *
 * @return void
 */
	public function testGetOptionParser() {
		$shell = $this->_shellName;
		$this->$shell = $this->loadShell($shell);

		//テスト実施
		$result = $this->$shell->getOptionParser();

		//チェック
		// Help確認とコードカバレッジ対応のためのテスト
		//var_export($result->help());
		$this->assertNotEmpty($result->help(), 'Helpは何も設定しなくても必ずあるので、エラーにならない想定');
	}
}
