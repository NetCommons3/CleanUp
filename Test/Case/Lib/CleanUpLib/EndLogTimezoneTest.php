<?php
/**
 * CleanUpLib::endLogTimezone()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpLib', 'CleanUp.Lib');

/**
 * CleanUpLib::endLogTimezone()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpLib
 */
class CleanUpUtilityCleanUpUtilityEndLogTimezoneTest extends CleanUpCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * endLogTimezone()のテスト
 *
 * @return void
 */
	public function testEndLogTimezone() {
		//データ生成
		$timezone = CleanUpLib::TIMEZONE;

		//テスト実施
		CleanUpLib::endLogTimezone($timezone);

		//チェック
		$this->assertEquals($timezone, date_default_timezone_get(),
			'date_default_timezoneは、指定したtimezoneに変更されてる想定');
	}

}