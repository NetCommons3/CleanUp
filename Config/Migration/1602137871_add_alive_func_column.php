<?php
/**
 * clean up records migration
 *
 * @author AllCreator <info@allcreator.net>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * clean up records migration
 *
 * @package NetCommons\CleanUp\Config\Migration
 */
class AddAliveFuncColumn extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_alive_func_column';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'clean_ups' => array(
					'alive_func' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'fields'),
				),
			),
			'alter_field' => array(
				'clean_ups' => array(
					'fields' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'WYSIWYG対象フィールド名(複数はカンマ区切り)', 'charset' => 'utf8'),
				),
			),
	),
		'down' => array(
			'drop_field' => array(
				'clean_ups' => array('alive_func'),
			),
			'alter_field' => array(
				'clean_ups' => array(
					'fields' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'WYSIWYG対象フィールド名(複数はカンマ区切り)', 'charset' => 'utf8'),
				),
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
			//汎用データベース
			array (
				'plugin_key' => 'multidatabases',
				'model' => 'MultidatabaseContent',
				'class' => 'Multidatabases.MultidatabaseContent',
				'fields' => 'value2,value3,value4,value5,value6,value7,value8,value9,value10,value11,value12,value13,value14,value15,value16,value17,value18,value19,value20,value21,value22,value23,value24,value25,value26,value27,value28,value29,value30,value31,value32,value33,value34,value35,value36,value37,value38,value39,value40,value41,value42,value43,value44,value45,value46,value47,value48,value49,value50,value51,value52,value53,value54,value55,value56,value57,value58,value59,value60,value61,value62,value63,value64,value65,value66,value67,value68,value69,value70,value71,value72,value73,value74,value75,value76,value77,value78,value79,value80,value81,value82,value83,value84,value85,value86,value87,value88,value89,value90,value91,value92,value93,value94,value95,value96,value97,value98,value99,value100',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//アンケート
			array (
				'plugin_key' => 'questionnaires',
				'model' => 'QuestionnaireQuestion',
				'class' => 'Questionnaires.QuestionnaireQuestion',
				'fields' => 'description',
				'alive_func' => 'getAliveCondition',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//小テスト
			array (
				'plugin_key' => 'quizzes',
				'model' => 'QuizQuestion',
				'class' => 'Quizzes.QuizQuestion',
				'fields' => 'commentary, question_value',
				'alive_func' => 'getAliveCondition',
				'created_user' => '1',
				'modified_user' => '1',
			),
			array (
				'plugin_key' => 'quizzes',
				'model' => 'QuizPage',
				'class' => 'Quizzes.QuizPage',
				'fields' => 'page_description',
				'alive_func' => 'getAliveCondition',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//登録フォーム
			array (
				'plugin_key' => 'registrations',
				'model' => 'Registration',
				'class' => 'Registrations.RegistrationQuestion',
				'fields' => 'description',
				'alive_func' => 'getAliveCondition',
				'created_user' => '1',
				'modified_user' => '1',
			),
			//施設予約
			array (
				'plugin_key' => 'reservations',
				'model' => 'ReservationLocation',
				'class' => 'Reservations.ReservationLocation',
				'fields' => 'detail',
				'alive_func' => 'getAliveCondition',
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
			$CleanUp = $this->generateModel('CleanUp');
			foreach ($records as &$record) {
				$targetRecord = $CleanUp->find('first', array(
					'recursive' => -1,
					'conditions' => array(
						'class' => $record['class']
					)
				));
				if (! $targetRecord) {
					CakeLog::info('Not Found ' . $record['class'] . ' in clean_ups');
					continue;
				}
				$record['id'] = $targetRecord['CleanUp']['id'];
			}
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}