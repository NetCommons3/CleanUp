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
class ModifyCleanUpRecordsColumn extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'modify_clean_up_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
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
			//登録フォーム
			array (
				'plugin_key' => 'registrations',
				'model' => 'RegistrationQuestion',
				'class' => 'Registrations.RegistrationQuestion',
				'fields' => 'description',
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
