<?php
/**
 * init migration
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * init migration
 *
 * @package NetCommons\CleanUp\Config\Migration
 */
class Init extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'init';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'clean_ups' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
					'plugin_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'プラグインKey', 'charset' => 'utf8'),
					'model' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'モデル', 'charset' => 'utf8'),
					'class' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'クラス記法', 'charset' => 'utf8'),
					'fields' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'WYSIWYG対象フィールド名(複数はカンマ区切り)', 'charset' => 'utf8'),
					'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'plugin_key' => array('column' => 'plugin_key', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'clean_ups'
			),
		),
	);

/**
 * records<br />
 * ファイルクリーンアップの対象にしたい場合、当マイグレーションを参考にマイグレーション作成して、<br />
 * 対象プラグインのデータをclean_upsテーブルに書き込む
 *
 * @var array $migration
 */
	public $records = array(
		'CleanUp' => array(
			//お知らせ
			array (
				'plugin_key' => 'announcements',
				'model' => 'Announcement',
				'class' => 'Announcements.Announcement',
				'fields' => 'content',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//掲示板
			array (
				'plugin_key' => 'bbses',
				'model' => 'BbsArticle',
				'class' => 'Bbses.BbsArticle',
				'fields' => 'content',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//ブログ
			array (
				'plugin_key' => 'blogs',
				'model' => 'BlogEntry',
				'class' => 'Blogs.BlogEntry',
				'fields' => 'body1,body2', // 複数の場合カンマ区切り
				'created_user' => '1',
				'modified_user' => '1',
			),
			//カレンダー
			array (
				'plugin_key' => 'calendars',
				'model' => 'CalendarActionPlan',
				'class' => 'Calendars.CalendarActionPlan',
				'fields' => 'description',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//回覧板
			array (
				'plugin_key' => 'circular_notices',
				'model' => 'CircularNoticeContent',
				'class' => 'CircularNotices.CircularNoticeContent',
				'fields' => 'content',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//FAQ
			array (
				'plugin_key' => 'faqs',
				'model' => 'FaqQuestion',
				'class' => 'Faqs.FaqQuestion',
				'fields' => 'answer',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//汎用データベース
			array (
				'plugin_key' => 'multidatabases',
				'model' => 'MultidatabaseContent',
				'class' => 'Multidatabases.MultidatabaseContent',
				'fields' => 'value80,value81,value82,value83,value84,value85,value86,value87,value88,value89,' .
					'value90,value91,value92,value93,value94,value95,value96,value97,value98,value99,value100',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//アンケート
			array (
				'plugin_key' => 'questionnaires',
				'model' => 'Questionnaire',
				'class' => 'Questionnaires.Questionnaire',
				'fields' => 'thanks_content, total_comment',
				'created_user' => '1',
				'modified_user' => '1',
			),
			array (
				'plugin_key' => 'questionnaires',
				'model' => 'QuestionnaireQuestion',
				'class' => 'Questionnaires.QuestionnaireQuestion',
				'fields' => 'description',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//小テスト
			array (
				'plugin_key' => 'quizzes',
				'model' => 'QuizQuestion',
				'class' => 'Quizzes.QuizQuestion',
				'fields' => 'commentary, question_value',
				'created_user' => '1',
				'modified_user' => '1',
			),
			array (
				'plugin_key' => 'quizzes',
				'model' => 'QuizPage',
				'class' => 'Quizzes.QuizPage',
				'fields' => 'page_description',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//登録フォーム
			array (
				'plugin_key' => 'registrations',
				'model' => 'Registration',
				'class' => 'Registrations.Registration',
				'fields' => 'thanks_content',
				'created_user' => '1',
				'modified_user' => '1',
			),
			array (
				'plugin_key' => 'registrations',
				'model' => 'RegistrationQuestion',
				'class' => 'Registrations.RegistrationQuestion',
				'fields' => 'description',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//施設予約
			array (
				'plugin_key' => 'reservations',
				'model' => 'ReservationLocation',
				'class' => 'Reservations.ReservationLocation',
				'fields' => 'detail',
				'created_user' => '1',
				'modified_user' => '1',
			),
			array (
				'plugin_key' => 'reservations',
				'model' => 'ReservationActionPlan',
				'class' => 'Reservations.ReservationActionPlan',
				'fields' => 'description',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//ＴＯＤＯ
			array (
				'plugin_key' => 'tasks',
				'model' => 'TaskContent',
				'class' => 'Tasks.TaskContent',
				'fields' => 'content',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//動画
			array (
				'plugin_key' => 'videos',
				'model' => 'Video',
				'class' => 'Videos.Video',
				'fields' => 'description',
				'created_user' => '1',
				'modified_user' => '1',
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction === 'down') {
			return true;
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}

		return true;
	}
}
