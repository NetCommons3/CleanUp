<?php
/**
 * UploadFileForCleanUpFixture
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * Summary for UploadFileForCleanUpFixture
 * @see UploadFileFixture よりコピー
 */
class UploadFileForCleanUpFixture extends UploadFileFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'UploadFile';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'upload_files';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array( // plugin_key=ありえないデータ
			'id' => 10,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_key' => 'Lorem ipsum dolor sit amet',
			'field_name' => 'Lorem ipsum dolor sit amet',
			'original_name' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'real_file_name' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 10,
			'download_count' => 10,
			'total_download_count' => 10,
			'room_id' => '11',
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 10,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 10,
			'modified' => '2016-02-25 03:44:14'
		),
		array( // video plugin_key=wysiwyg以外のデータ
			'id' => 11,
			'plugin_key' => 'videos',
			'content_key' => 'content_key_1',
			'field_name' => 'video_file',
			'original_name' => 'video1.mp4',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'video1.mp4',
			'extension' => 'mp4',
			'mimetype' => 'video/mp4',
			'size' => 4544587,
			'download_count' => 11,
			'total_download_count' => 11,
			'room_id' => '12',
			'block_key' => 'Lorem ipsum dolor sit amet',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		array( // wysiwyg 'content_key' => null,
			'id' => 12,
			'plugin_key' => 'wysiwyg',
			'content_key' => null,
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 12,
			'total_download_count' => 12,
			'room_id' => '2',
			'block_key' => 'block_1',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		array( // wysiwyg 'content_key' => null,
			'id' => 13,
			'plugin_key' => 'wysiwyg',
			'content_key' => null,
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_100',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// wysiwyg で announcements アップファイル 日のみ
		// /wysiwyg/image/download/2/14/
		// /wysiwyg/file/download/2/14/
		array(
			'id' => 14,
			'plugin_key' => 'wysiwyg',
			'content_key' => 'announcement_wysiwyg_5',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_1001',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// wysiwyg で announcements アップファイル 日英あり。英でファイル使ってる
		// /wysiwyg/image/download/2/15/
		array(
			'id' => 15,
			'plugin_key' => 'wysiwyg',
			'content_key' => 'announcement_wysiwyg_6',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_1002',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// wysiwyg で announcements アップファイル 日英あり。日でファイル使ってる
		// /wysiwyg/image/download/2/16/
		array(
			'id' => 16,
			'plugin_key' => 'wysiwyg',
			'content_key' => 'announcement_wysiwyg_8',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_1003',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		array( // wysiwyg で announcements 'content_key' => '',
			'id' => 17,
			'plugin_key' => 'wysiwyg',
			'content_key' => '',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_1001',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		array( // wysiwyg で announcements 'content_key' => null,
			'id' => 18,
			'plugin_key' => 'wysiwyg',
			'content_key' => null,
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_1001',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// wysiwyg で announcements アップファイル 日英あり。どちらもファイル使ってない
		// /wysiwyg/image/download/2/19/
		array(
			'id' => 19,
			'plugin_key' => 'wysiwyg',
			'content_key' => 'announcement_wysiwyg_10',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_1004',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// wysiwyg で announcements アップファイル 日のみ. お知らせ(is_active=1)でファイル使ってる
		// /wysiwyg/image/download/2/20/
		array(
			'id' => 20,
			'plugin_key' => 'wysiwyg',
			'content_key' => 'announcement_wysiwyg_12',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_1005',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// wysiwyg で announcements アップファイル 日のみ. お知らせ(is_latest=1)でファイル使ってる
		// /wysiwyg/image/download/2/21/
		array(
			'id' => 21,
			'plugin_key' => 'wysiwyg',
			'content_key' => 'announcement_wysiwyg_14',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_1006',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// wysiwyg で announcements アップファイル 日のみ. (is_latest=1 or is_latest=1)でファイル使ってない
		// /wysiwyg/image/download/2/22/
		array(
			'id' => 22,
			'plugin_key' => 'wysiwyg',
			// 例えばお知らせと合わせる
			'content_key' => 'announcement_wysiwyg_16',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.gif',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.gif',
			'extension' => 'gif',
			'mimetype' => 'image/gif',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			// blockと合わせる
			'block_key' => 'block_1007',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// (jpgでunkown対象) wysiwyg 'content_key' => null,
		array(
			'id' => 23,
			'plugin_key' => 'wysiwyg',
			'content_key' => null,
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.jpg',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpeg',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			'block_key' => 'block_100',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
		// (jpgでannouncements対象)
		// wysiwyg で announcements アップファイル 日のみ. (is_latest=1 or is_latest=1)でファイル使ってない
		// /wysiwyg/image/download/2/24/
		array(
			'id' => 24,
			'plugin_key' => 'wysiwyg',
			// 例えばお知らせと合わせる
			'content_key' => 'announcement_wysiwyg_16',
			'field_name' => 'Wysiwyg.file',
			'original_name' => 'michel2.jpg',
			'path' => 'files/upload_file/test/',
			'real_file_name' => 'michel2.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpeg',
			'size' => 21229,
			'download_count' => 13,
			'total_download_count' => 13,
			'room_id' => '2',
			// blockと合わせる
			'block_key' => 'block_1007',
			'created_user' => 1,
			'created' => '2016-02-25 03:44:14',
			'modified_user' => 1,
			'modified' => '2016-02-25 03:44:14'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		parent::init();
	}

}
