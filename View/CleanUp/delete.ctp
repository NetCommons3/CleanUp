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

	<?php /* 再表示ボタン */ ?>
	<div class="form-group">
		<div class="clearfix">
			<div class="pull-right">
				<?php echo $this->NetCommonsForm->create(false, [
					'url' => array('controller' => 'clean_up', 'action' => 'delete'),
					'id' => 'CleanUpDeleteGet',
					'type' => 'get',
				]); ?>
					<?php echo $this->NetCommonsForm->button('<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>', [
						'class' => 'btn btn-default nc-btn-style',
					]); ?>
				<?php echo $this->NetCommonsForm->end(); ?>
			</div>
		</div>
	</div>

	<?php /* 上部メッセージ */ ?>
	<div class="well well-sm">
		<?php echo __d('clean_up', '使用されていないアップロードファイルを削除します。
対象のプラグインを選択して、[削除]を押してください。
ファイルクリーンアップを実行する前に、<a href="%s" target="_blank">こちら</a>を参考に<span class="text-danger"><u>必ずバックアップして、いつでもリストアできるようにしてから実行してください。</u></span><br />
ファイルクリーンアップはバックグラウンドで実行します。
		', CleanUp::HOW_TO_BACKUP_URL);
		?>
	</div>

	<?php /* ロック中ラベル */ ?>
	<?php if ($isLockFile) : ?>
		<div class="form-group">
			<span class="label label-info">
				<?php echo __d('clean_up', '実行ロック中 (%sよりファイルクリーンアップ開始)', [$this->Date->dateFormat($cleanUpStart)]); ?>
			</span>
		</div>
	<?php endif; ?>

	<?php /* チェックボックス */ ?>
	<div class="panel panel-default">
		<?php echo $this->NetCommonsForm->create('CleanUp', [
			'url' => array('controller' => 'clean_up', 'action' => 'delete'),
			'id' => 'CleanUpDeletePost',
		]); ?>

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
					__d('clean_up', '使用されていないアップロードファイルを削除します。よろしいですか？')
				); ?>
			</div>
		<?php echo $this->NetCommonsForm->end(); ?>
	</div>

	<?php /* 実行中ロックファイル強制削除処理 */ ?>
	<div class="nc-danger-zone" ng-init="dangerZone=false;">
		<?php echo $this->NetCommonsForm->create(false, [
			'url' => array('controller' => 'clean_up', 'action' => 'unlock'),
			'id' => 'CleanUpLock',
			'type' => 'get',
		]); ?>
			<uib-accordion close-others="false">
				<div uib-accordion-group is-open="dangerZone" class="panel-danger">
					<uib-accordion-heading class="clearfix">
						<span style="cursor: pointer">
							<?php echo __d('clean_up', 'ロックファイル強制削除処理'); ?>
						</span>
						<span class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': dangerZone, 'glyphicon-chevron-right': ! dangerZone}"></span>
					</uib-accordion-heading>

					<div class="inline-block">
						<?php echo __d('clean_up', 'ロックファイルを強制削除します。<br />
ファイルクリーンアップの途中停止等でロックファイルが残り、実行できなくなった場合にご利用ください。<br />
また、実行結果からファイルクリーンアップが停止した事を確認した上で、ご利用ください。'); ?>
					</div>
					<?php echo $this->Button->delete(
						__d('net_commons', 'Delete'),
						sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('clean_up', 'ロックファイル')),
						array('addClass' => 'pull-right')
					); ?>
				</div>
			</uib-accordion>
		<?php echo $this->NetCommonsForm->end(); ?>
	</div>

	<?php /* 実行結果 */ ?>
	<h2><?php echo __d('clean_up', '実行結果') ?></h2>
	<?php echo $this->NetCommonsForm->create(false, []); ?>
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
