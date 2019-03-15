<?php
/**
 * CleanUp Shell
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Current', 'NetCommons.Utility');
App::uses('CleanUp', 'CleanUp.Model');
App::uses('CleanUpExec', 'CleanUp.Lib');
App::uses('CleanUpLog', 'CleanUp.Lib');

/**
 * CleanUp Shell
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Console\Command
 * @property CleanUp $CleanUp
 */
class CleanUpShell extends AppShell {

/**
 * 全てのプラグインを指定
 *
 * @var string
 */
	const PLUGIN_KEY_ALL = 'all';

/**
 * use model
 *
 * @var array
 */
	public $uses = [
		'CleanUp.CleanUp',
	];

/**
 * construct
 *
 * @return void
 */
	public function __construct() {
		parent::__construct();
		// とりあえず2:日本語をセット
		Current::write('Language.id', '2');

		//Configure::write('Config.language', 'fra');
	}

/**
 * ファイルクリーンアップ
 * ### コマンド例
 * ```
 * Console/cake clean_up.clean_up clean_up all
 * ```
 *
 * @return void
 * @throws Exception
 * @see http://www.php.net/manual/ja/info.configuration.php#ini.max-execution-time max_execution_time  PHP を コマンドライン から実行する場合のデフォルト設定は 0 です。
 */
	public function clean_up() {
		$pluginKeys = $this->args;

		$data = [];
		if (array_search(self::PLUGIN_KEY_ALL, $pluginKeys)) {
			// プラグインキーの一覧
			$data['CleanUp']['plugin_key'] = $this->__getPluginKeys();
		} else {
			$data['CleanUp']['plugin_key'] = $pluginKeys;
		}

		if ($this->CleanUp->fileCleanUp($data)) {
			// 成功
			$this->out('Success!!');
			return;
		}
		// エラー. 第1引数plugin_keyを選択肢からの必須にしたため, 基本到達しない. テストはここ通る
		$this->out('[ValidationErrors]');
		$this->out(var_export($this->CleanUp->validationErrors));
	}

/**
 * ロックファイルの強制削除
 * ### コマンド例
 * ```
 * Console/cake clean_up.clean_up unlock
 * ```
 *
 * @return void
 */
	public function unlock() {
		// ロックファイルの削除
		CleanUpExec::deleteLockFileAndSetupLog();
	}

/**
 * 引数設定
 *
 * @return ConsoleOptionParser
 * @link http://book.cakephp.org/2.0/ja/console-and-shells.html#Shell::getOptionParser
 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		// 説明
		$parser->description([
			__d('clean_up', 'File cleanup command description', [
				CleanUp::HOW_TO_BACKUP_URL,
				LOGS . 'cleanup' . DS . CleanUpLog::LOG_FILE_NAME,
			]),
		]);

		// プラグインキーの一覧
		$pluginKeys = $this->__getPluginKeys();
		$pluginKeys[] = self::PLUGIN_KEY_ALL;

		// 引数
		$arguments[] = [
			'help' => __d('clean_up', 'File cleanup command arguments help'),
			'required' => false,
			'choices' => $pluginKeys,
		];

		// プラグイン数分ループして引数を追加する
		// 最大プラグイン数と+1(上記helpで説明の分)の引数を設定
		// @codingStandardsIgnoreStart
		foreach ($pluginKeys as $pluginKey) {
			// @codingStandardsIgnoreEnd
			$arguments[] = [
				'required' => false,
				'choices' => $pluginKeys,
			];
		}

		$parser->addArguments($arguments);

		return $parser;
	}

/**
 * プラグインキーの一覧 ゲット
 *
 * @return array
 */
	private function __getPluginKeys() {
		// プラグインキーの一覧
		$cleanUps = $this->CleanUp->getCleanUpsAndUnknow();
		$pluginKeys = [];
		foreach ($cleanUps as $cleanUp) {
			$pluginKeys[] = $cleanUp['CleanUp']['plugin_key'];
		}
		return $pluginKeys;
	}

}
