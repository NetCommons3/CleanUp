<?php
/**
 * CleanUp view template
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
echo $this->NetCommonsHtml->css('/clean_up/css/style.css');
echo $this->NetCommonsHtml->script(array(
	'/clean_up/js/clean_up.js',
));
?>

<article ng-controller="CleanUp" ng-init="initialize()" ng-cloak>

	<div class="well well-sm">
		<?php echo __d('clean_up', '使用されていないアップロードファイルを削除します。
対象のプラグインを選択して、[削除]を押してください。
ファイルクリーンアップを実行する前に、<a href="%s" target="_blank">こちら</a>を参考に<span class="text-danger"><u>必ずバックアップして、いつでもリストアできるようにしてから実行してください。</u></span>
		', CleanUp::HOW_TO_BACKUP_URL);
		?>
	</div>
	<div class="panel panel-default">
		<?php echo $this->NetCommonsForm->create('CleanUp', array()); ?>

			<div class="panel-body">
				<div class="form-inline">
					<div class="clearfix">
						<?php
						//チェックボックスの設定
						$options = Hash::combine(
							$cleanUps, '{n}.Plugin.key', '{n}.Plugin.name'
						);
						echo $this->NetCommonsForm->select(
							'CleanUp.plugin_key',
							$options,
							[
								'multiple' => 'checkbox',
								'div' => array('class' => 'plugin-checkbox-outer'),
							]
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
	<?php echo $this->NetCommonsForm->create('Log', array()); ?>

	<div class="form-group">
		<div class="form-inline">
			<?php echo $this->NetCommonsForm->input('_log_file', [
				'options' => $logFileNames,
				//'ng-change' => 'more()',
				//'ng-model'
			]); ?>
			<button type="button" class="btn btn-default" ng-click="more()" >
				<?php echo __d('clean_up', '見る'); ?>
			</button>
		</div>
		<?php echo $this->NetCommonsForm->textarea('_log_result', [
			'default' => $cleanUpLog,
			'class' => 'form-control',
			'rows' => '15',
		]); ?>
		<div class="help-block">
			<?php echo __d('clean_up', '時刻は協定世界時(UTC)表記です') ?>
		</div>
	</div>
	<?php echo $this->NetCommonsForm->end(); ?>
</article>
