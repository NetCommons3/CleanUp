<?php
/**
 * CleanUpLog::startLogTimezone()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpLog', 'CleanUp.Lib');

/**
 * CleanUpLog::startLogTimezone()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpExec
 */
class CleanUpLibCleanUpLogStartLogTimezoneTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * startLogTimezone()のテスト
 *
 * @return void
 */
	public function testStartLogTimezone() {
		//テスト実施
		$result = CleanUpLog::startLogTimezone();

		//チェック
		$this->assertEquals('UTC', $result,
			'戻り値は、date_default_timezoneの初期値でUTCの想定');
		$this->assertEquals(CleanUpLog::TIMEZONE, date_default_timezone_get(),
			'date_default_timezoneは、CleanUpLog::TIMEZONEに変更されてる想定');
	}

}
