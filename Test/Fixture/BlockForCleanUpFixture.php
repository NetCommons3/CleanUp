<?php
/**
 * BlockForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlockFixture', 'Blocks.Test/Fixture');

/**
 * BlockForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Fixture
 */
class BlockForCleanUpFixture extends BlockFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Block';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'blocks';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//公開データ
		array(
			'id' => '2',
			'room_id' => '2',
			'plugin_key' => 'test_plugin',
			'key' => 'block_1',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
		),
		//公開データ announcements
		array(
			'id' => '1001',
			'room_id' => '2',
			'plugin_key' => 'announcements',
			'key' => 'block_1001',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
		),
		array(
			'id' => '1002',
			'room_id' => '2',
			'plugin_key' => 'announcements',
			'key' => 'block_1002',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
		),
		array(
			'id' => '1003',
			'room_id' => '2',
			'plugin_key' => 'announcements',
			'key' => 'block_1003',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
		),
		array(
			'id' => '1004',
			'room_id' => '2',
			'plugin_key' => 'announcements',
			'key' => 'block_1004',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
		),
		array(
			'id' => '1005',
			'room_id' => '2',
			'plugin_key' => 'announcements',
			'key' => 'block_1005',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
		),
		array(
			'id' => '1006',
			'room_id' => '2',
			'plugin_key' => 'announcements',
			'key' => 'block_1006',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
		),
		array(
			'id' => '1007',
			'room_id' => '2',
			'plugin_key' => 'announcements',
			'key' => 'block_1006',
			'public_type' => '1',
			'publish_start' => null,
			'publish_end' => null,
		),
		//非公開データ
		array(
			'id' => '4',
			'room_id' => '2',
			'plugin_key' => 'test_plugin',
			'key' => 'block_2',
			'public_type' => '0',
			'publish_start' => null,
			'publish_end' => null,
		),
		//期間限定公開(範囲内)
		array(
			'id' => '6',
			'room_id' => '2',
			'plugin_key' => 'test_plugin',
			'key' => 'block_3',
			'public_type' => '2',
			'publish_start' => '2014-01-01 00:00:00',
			'publish_end' => '2035-12-31 00:00:00',
		),

		//期間限定公開(過去)
		array(
			'id' => '8',
			'room_id' => '2',
			'plugin_key' => 'test_plugin',
			'key' => 'block_4',
			'public_type' => '2',
			'publish_start' => null,
			'publish_end' => null,
		),

		//期間限定公開(未来)
		array(
			'id' => '10',
			'room_id' => '2',
			'plugin_key' => 'test_plugin',
			'key' => 'block_5',
			'public_type' => '2',
			'publish_start' => null,
			'publish_end' => null,
		),
	);

}
