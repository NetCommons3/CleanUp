<?php
/**
 * AnnouncementForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AnnouncementFixture', 'Announcements.Test/Fixture');

/**
 * AnnouncementForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Fixture
 * @codeCoverageIgnore
 */
class AnnouncementForCleanUpFixture extends AnnouncementFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Announcement';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'announcements';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'language_id' => '2',
			'block_id' => '2',
			'key' => 'announcement_1',
			'status' => '1',
			'is_active' => '1',
			'is_latest' => '0',
			'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '1',
			'modified_user' => '1',
		),
		array(
			'id' => '2',
			'language_id' => '2',
			'block_id' => '2',
			'key' => 'announcement_1',
			'status' => '4',
			'is_active' => '0',
			'is_latest' => '1',
			'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created_user' => '1',
			'created' => '2014-10-09 16:07:57',
			'modified_user' => '1',
			'modified' => '2014-10-09 16:07:57'
		),
		array(
			'id' => '3',
			'language_id' => '2',
			'block_id' => '4',
			'key' => 'announcement_2',
			'status' => '1',
			'is_active' => '1',
			'is_latest' => '1',
			'content' => 'Content 11',
			'created_user' => '1',
			'created' => '2014-10-09 16:07:57',
			'modified_user' => '1',
			'modified' => '2014-10-09 16:07:57'
		),
		array(
			'id' => '4',
			'language_id' => '2',
			'block_id' => '6',
			'key' => 'announcement_3',
			'status' => '2',
			'is_active' => '0',
			'is_latest' => '1',
			'content' => 'Content 12',
			'created_user' => '1',
			'created' => '2014-10-09 16:07:57',
			'modified_user' => '1',
			'modified' => '2014-10-09 16:07:57'
		),
		// wysiwyg で announcements アップファイル. 最新で有効
		array(
			'id' => '5',
			'language_id' => '2',
			'block_id' => '1001',
			'key' => 'announcement_wysiwyg_5',
			'status' => '2',
			'is_active' => '1',
			'is_latest' => '1',
			'content' => '
<p><img class="img-responsive nc3-img nc3-img-block" title="" src="{{__BASE_URL__}}/wysiwyg/image/download/2/14/" alt="" />ｱｱｱ</p>',
			'created_user' => '1',
			'created' => '2016-10-09 16:07:57',
			'modified_user' => '1',
			'modified' => '2016-10-09 16:07:57'
		),
	);

}
