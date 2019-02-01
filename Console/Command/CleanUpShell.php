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
	function __construct() {
		parent::__construct();
		// とりあえず2:日本語をセット
		Current::write('Language.id', '2');

		//Configure::write('Config.language', 'fra');
	}

/**
 * Main
 *
 * @return void
 * @throws Exception
 * @see http://www.php.net/manual/ja/info.configuration.php#ini.max-execution-time max_execution_time  PHP を コマンドライン から実行する場合のデフォルト設定は 0 です。
 */
	public function main() {
		//var_dump($this->args[0]);
		//$pluginKey = $this->args[0];
		$pluginKeys = $this->args;

		$data = [];
		if (array_search(self::PLUGIN_KEY_ALL, $pluginKeys)) {
			// プラグインキーの一覧
			$data['CleanUp']['plugin_key'] = $this->__getPluginKeys();
		} else {
			$data['CleanUp']['plugin_key'] = $pluginKeys;
		}

		//$data['CleanUp']['plugin_key'][] = 'announcements';
		//$data['CleanUp']['plugin_key'][] = 'unknown';
		//$data['CleanUp']['plugin_key'] = '';
		//var_dump($data);
		if ($this->CleanUp->fileCleanUp($data)) {
			// 成功
			$this->out('Success!!');
			return;
		}
		// エラー. 第1引数plugin_keyを選択肢からの必須にしたため, 基本到達しない
		$this->out('[ValidationErrors]');
		var_export($this->CleanUp->validationErrors);
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
			__d('clean_up', 'ファイルクリーンアップ'),
			__d('clean_up', '使用されていないアップロードファイルを削除します。対象のplugin_keyを指定してください。
全ての引数はplugin_keyとして処理します。
ファイルクリーンアップを実行する前に、こちらを参考に必ずバックアップして、いつでもリストアできるように
してから実行してください。'),
			CleanUp::HOW_TO_BACKUP_URL,
			'',
			__d('clean_up', '実行結果は下記にログ出力されます。'),
			ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'logs' . DS . 'CleanUp.log',
		]);

		// プラグインキーの一覧
		$pluginKeys = $this->__getPluginKeys();
		$pluginKeys[] = self::PLUGIN_KEY_ALL;

		// 引数
		$arguments[] = [
			'help' => __d('clean_up', 'クリーンアップする対象のプラグインキー。
[通常以外で指定できるプラグインキー] unknown:プラグイン不明ファイル, all:全てのプラグイン'),
			'required' => true,
			'choices' => $pluginKeys,
		];

		// プラグイン数分ループして引数を追加する
		foreach($pluginKeys as $pluginKey) {
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
