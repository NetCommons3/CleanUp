<?php
/**
 * CleanUp view template
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article>
	<div class="well well-sm">
		<?php echo __d('clean_up', '使用されていないアップロードファイルを削除します。
対象のプラグインを選択して、[削除]を押してください。
ファイルクリーンアップを実行する前に、<a href="%s" target="_blank">こちら</a>を参考に<span class="text-danger"><u>必ずバックアップして、いつでもリストアできるようにしてから実行してください。</u></span>
		', CleanUp::HOW_TO_BACKUP_URL);
		?>
	</div>
	<div class="panel panel-default">
		<?php echo $this->NetCommonsForm->create('CleanUp', array(
//			'ng-controller' => 'SystemManager',
//			'name' => 'form',
//			'url' => NetCommonsUrl::blockUrl(array(
//				'controller' => 'clean_up',
//				'action' => 'delete',
//			)),
//			'type' => 'delete',
		)); ?>

			<div class="panel-body">
				<div class="form-inline">
					<div class="clearfix">
						<?php
						//$default = Hash::extract($pluginsRoom, '{n}.PluginsRoom[room_id=' . Current::read('Room.id') . ']');
						echo $this->CleanUpForm->checkboxPlugins(
							'CleanUp.plugin_key',
							array(
								'div' => array('class' => 'plugin-checkbox-outer'),
								//'default' => array_values(Hash::combine($default, '{n}.plugin_key', '{n}.plugin_key'))
								//'default' => Hash::extract($default, '{n}.Plugin.key')
							)
						);
						?>
					</div>
				</div>
				<?php echo $this->NetCommonsForm->error('CleanUp.plugin_key'); ?>
			</div>

			<div class="panel-footer text-center">
				<?php echo $this->Button->delete(
					null,
					__d('clean_up', 'チェックされたプラグインの使用されていないアップロードファイルを削除します。よろしいですか？')
				); ?>
			</div>
		<?php echo $this->NetCommonsForm->end(); ?>
	</div>

	<h2><?php echo __d('clean_up', '実行結果') ?></h2>
	<div class="form-group">
		<?php
		$logPath = ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'logs' . DS . 'CleanUp.log';
		$cleanUpLog = '';
		if (file_exists($logPath)) {
			$cleanUpLog = file_get_contents($logPath);
		} else {
			$cleanUpLog = __d('clean_up', 'ありません');
		}
		echo $this->NetCommonsForm->textarea('result', [
			'default' => $cleanUpLog,
			'class' => 'form-control',
			'rows' => '15',
		]);
		?>
		<div class="help-block">
			<?php echo __d('clean_up', '時刻は協定世界時(UTC)表記です') ?>
		</div>
	</div>
</article>
