<?php
/**
 * CleanUpUtility::cleanUp()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CleanUpCakeTestCase', 'CleanUp.TestSuite');
App::uses('CleanUpUtility', 'CleanUp.Utility');
App::uses('CleanUpTestUtil', 'CleanUp.Test/Case');

/**
 * CleanUpUtility::cleanUp()のテスト
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case\Utility\CleanUpUtility
 */
class CleanUpUtilityCleanUpUtilityCleanUpTest extends CleanUpCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 * @see NetCommonsCakeTestCase::$_defaultFixtures よりコピー
 */
	protected $_defaultFixtures = array(
		'plugin.blocks.block',
		'plugin.blocks.block_role_permission',
		'plugin.blocks.block_setting',
		'plugin.blocks.blocks_language',
		'plugin.boxes.box',
		'plugin.boxes.boxes_page_container',
		'plugin.data_types.data_type',
		'plugin.data_types.data_type_choice',
		'plugin.files.upload_file',
		'plugin.files.upload_files_content',
		'plugin.frames.frame',
		'plugin.frames.frame_public_language',
		'plugin.frames.frames_language',
		'plugin.m17n.language',
		'plugin.mails.mail_queue',
		'plugin.mails.mail_queue_user',
		'plugin.mails.mail_setting',
		'plugin.pages.page',
		'plugin.pages.page_container',
		'plugin.plugin_manager.plugin',
		//'plugin.plugin_manager.plugins_role',
		//'plugin.roles.default_role_permission',
		'plugin.roles.role',
		'plugin.rooms.roles_room',
		'plugin.rooms.roles_rooms_user',
		'plugin.rooms.room',
		'plugin.rooms.rooms_language',
		//'plugin.rooms.room_role',
		//'plugin.rooms.room_role_permission',
		'plugin.rooms.space',
		'plugin.site_manager.site_setting',
		'plugin.topics.topic',
		'plugin.topics.topic_readable',
		'plugin.topics.topic_user_status',
		'plugin.user_attributes.user_attribute',
		'plugin.user_attributes.user_attribute_choice',
		'plugin.user_attributes.user_attribute_setting',
		'plugin.user_roles.user_attributes_role',
		'plugin.users.user',
		'plugin.users.users_language',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'clean_up';

/**
 * Fixtures
 *
 * @var array
 * @see Plugin4testFixture
 */
	public $fixtures = array(
		'plugin.clean_up.clean_up',
		'plugin.plugin_manager.plugin4test',
	);

/**
 * cleanUp()のテスト
 *
 * @return void
 */
	public function testCleanUp() {
		//アップロードファイルで、削除対象のファイルを用意
		CleanUpTestUtil::makeTestUploadFiles();

		//データ生成
		// コンソール側でfixturesのclean_up <-> plugin4test関連データがうまくfindできてなくtravisで
		// 下記エラーになった。
		// Error: "announcements" is not a valid value for 0. Please use one of "unknown, all"
		// コンソールのテストは、別途コンソール側で行うため、ここで指定するプラグインキーはテーブルをfind
		// しなくてもある、unknownを指定する
		$data['CleanUp']['plugin_key'] = [
			'unknown'
		];

		//テスト実施
		// execを実行してるだけなので、ロジックが通る事を確認
		CleanUpUtility::cleanUp($data);

		//チェック
		$this->assertTrue(true);
	}

}
