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
		<?php echo __d('clean_up', 'File cleanup view description', CleanUp::HOW_TO_BACKUP_URL); ?>
	</div>

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
						//$options = Hash::combine(
						//	$cleanUps, '{n}.Plugin.key', '{n}.Plugin.name'
						//);
						$options = [];
						foreach ($cleanUps as $cleanUp) {
							$options[$cleanUp['Plugin']['key']] = $cleanUp['Plugin']['name'];
						}
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

				<?php /* ロック中 */
				$deleteButtonOption = [];
				?>
				<?php if ($isLockFile) : ?>
					<div class="has-error">
						<div class="help-block">
							<?php
							echo __d('clean_up', 'There is a lock file. Please wait for a while because file cleanup is in progress.') . '<br />';
							echo __d('clean_up', 'File cleanup start date: %s', [$cleanUpStart]);
							// 削除ボタン非活性
							$deleteButtonOption = ['disabled' => 'disabled'];
							?>
						</div>
					</div>
				<?php endif; ?>

			</div>

			<div class="panel-footer text-center">
				<?php echo $this->Button->delete(
					null,
					__d('clean_up', 'Deletes unused upload files. Is it OK?'),
					$deleteButtonOption
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
							<?php echo __d('clean_up', 'Forcibly delete lock file'); ?>
						</span>
						<span class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': dangerZone, 'glyphicon-chevron-right': ! dangerZone}"></span>
					</uib-accordion-heading>

					<div class="inline-block">
						<?php echo __d('clean_up', 'Forcibly delete lock file description'); ?>
					</div>
					<?php echo $this->Button->delete(
						__d('net_commons', 'Delete'),
						sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('clean_up', 'Lock file')),
						array('addClass' => 'pull-right')
					); ?>
				</div>
			</uib-accordion>
		<?php echo $this->NetCommonsForm->end(); ?>
	</div>

	<?php /* 実行結果 */ ?>
	<h2><?php echo __d('clean_up', 'Execution result') ?></h2>
	<?php echo $this->NetCommonsForm->create(false, []); ?>
		<div class="form-group">
			<div class="form-inline">
				<?php echo $this->NetCommonsForm->input('_log_file', [
					'options' => $logFileNames,
					//'ng-change' => 'more()',
					//'ng-model'
				]); ?>
				<button type="button" class="btn btn-default" ng-click="more()" >
					<?php echo __d('clean_up', 'Look'); ?>
				</button>

			</div>
			<?php echo $this->NetCommonsForm->textarea('_log_result', [
				'default' => $cleanUpLog,
				'class' => 'form-control',
				'rows' => '15',
			]); ?>
		</div>
	<?php echo $this->NetCommonsForm->end(); ?>

</article>
