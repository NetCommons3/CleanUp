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

<!--<a href="https://github.com/NetCommons3/CleanUp#CleanUp" target="_blank">-->
<!--	--><?php //echo __d('nc2_to_nc3', 'Go here for the migration from NetCommons2 documentation.'); ?>
<!--</a>-->

<article>
	<div class="well well-sm">
		使用されていないアップロードファイルを削除します。
		対象のプラグインを選択して、[削除]を押してください。<br>
		ファイルクリーンアップを実行する前に、<a href="https://www.netcommons.org/NetCommons3/download#!#frame-362" target="_blank">こちら</a>を参考に<span class="text-danger"><u>必ずバックアップして、いつでもリストアできるようにしてから実行してください。</u></span>
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
<!--		<div class="panel-body">-->
<!--			<div class="form-inline">-->
<!--				<div class="clearfix">-->
<!--					--><?php
//					$default = Hash::extract($pluginsRoom, '{n}.PluginsRoom[room_id=' . Current::read('Room.id') . ']');
//					echo $this->PluginsForm->checkboxPluginsRoom(
//						'PluginsRoom.plugin_key',
//						array(
//							'div' => array('class' => 'plugin-checkbox-outer'),
//							'default' => array_values(Hash::combine($default, '{n}.plugin_key', '{n}.plugin_key'))
//						)
//					);
//					?>
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->

			<div class="panel-body">
				<div class="form-inline">
					<div class="clearfix">
						<div class="plugin-checkbox-outer">
							<div class="checkbox nc-multiple-checkbox">
								<?php echo $this->NetCommonsForm->checkbox('announcements', array(
									'type' => 'checkbox',
									'label' => __d('clean_up', 'お知らせ'),
//									'div' => array('class' => 'plugin-checkbox-outer'),
//									'div' => array('class' => ''),
//									'class' => 'checkbox nc-multiple-checkbox'
								)); ?>
							</div>
							<div class="checkbox nc-multiple-checkbox">
								<?php echo $this->NetCommonsForm->checkbox('unknown', array(
									'type' => 'checkbox',
									'label' => __d('clean_up', 'プラグイン不明のファイル'),
//									'div' => array('class' => 'plugin-checkbox-outer'),
//									'div' => array('class' => ''),
//									'class' => 'checkbox nc-multiple-checkbox'
								)); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer text-center">
				<?php echo $this->Button->delete(
					null,
					__d('clean_up', 'チェックされたプラグインの使用されていないアップロードファイルを削除します。よろしいですか？')
				); ?>
			</div>
		<?php echo $this->NetCommonsForm->end(); ?>
	</div>
</article>


<?php /* サンプル画面 */ ?>
<div class="row" style="box-sizing: border-box; margin-right: -15px; margin-left: -15px;">
	<div role="main" id="container-main" class="control-panel col-sm-10"
		 style="box-sizing: border-box; position: relative; min-height: 1px; padding-right: 15px; padding-left: 15px; float: left; width: 975px;">
		<article class="plugin-rooms" style="box-sizing: border-box; display: block;">
			<div class="well well-sm" style="box-sizing: border-box; min-height: 20px; padding: 9px; margin-bottom: 20px; background-color: rgb(245, 245, 245); border: 1px solid rgb(227, 227, 227); border-radius: 3px; box-shadow: rgba(0, 0, 0, 0.05) 0px 1px 1px inset;">
				使用されていないアップロードファイルを削除します。
				対象のプラグインを選択して、[削除]を押してください。<br>
				ファイルクリーンアップを実行する前に、<a href="https://www.netcommons.org/NetCommons3/download#!#frame-362" target="_blank">こちら</a>を参考に<span class="text-danger"><u>必ずバックアップして、いつでもリストアできるようにしてから実行してください。</u></span>
			</div>
			<div class="panel panel-default" style="box-sizing: border-box; margin-bottom: 20px; background-color: rgb(255, 255, 255); border: 1px solid rgb(221, 221, 221); border-radius: 4px; box-shadow: rgba(0, 0, 0, 0.05) 0px 1px 1px;">

				<form action="https://example.com/"
					novalidate="novalidate" id="RoomEditForm" method="post" accept-charset="utf-8"
					class="ng-pristine ng-valid" style="box-sizing: border-box;">

					<div class="panel-body" style="box-sizing: border-box; padding: 15px;">
						<div class="form-inline" style="box-sizing: border-box;">
							<div class="clearfix" style="box-sizing: border-box;">
								<div class="plugin-checkbox-outer" style="box-sizing: border-box;">
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="access_counters" id="PluginsRoomPluginKeyAccessCounters"

												style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyAccessCounters"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">お
											知らせ</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="announcements" id="PluginsRoomPluginKeyAnnouncements"

												style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyAnnouncements"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">掲
											示板</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="bbses" id="PluginsRoomPluginKeyBbses" style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyBbses"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">ブ
											ログ</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="blogs" id="PluginsRoomPluginKeyBlogs" style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyBlogs"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">カ
											レンダー</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="cabinets" id="PluginsRoomPluginKeyCabinets"

												style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyCabinets"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">回
											覧板</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="calendars" id="PluginsRoomPluginKeyCalendars"

												style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyCalendars"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">FAQ</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" value="circular_notices"

												id="PluginsRoomPluginKeyCircularNotices" style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyCircularNotices"

																	   style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">汎
											用データベース</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="faqs" id="PluginsRoomPluginKeyFaqs" style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyFaqs"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">ア
											ンケート</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="iframes" id="PluginsRoomPluginKeyIframes"

												style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyIframes"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">小
											テスト</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="links" id="PluginsRoomPluginKeyLinks" style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyLinks"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">登
											録フォーム</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="menus" id="PluginsRoomPluginKeyMenus" style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyMenus"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">施
											設予約</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="multidatabases" id="PluginsRoomPluginKeyMultidatabases"

												style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyMultidatabases"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">ToDo</label></div>
									<div class="checkbox nc-multiple-checkbox" style="box-sizing: border-box; position: relative; display: inline-block; margin-top: 0px; margin-bottom: 1em; vertical-align: middle; width: 12em; float: left; white-space: nowrap;"><input

												name="data[PluginsRoom][plugin_key][]" checked="checked"

												value="photo_albums" id="PluginsRoomPluginKeyPhotoAlbums"

												style="box-sizing: border-box; margin: 4px 8px 0px 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; font-size: inherit; line-height: normal; font-family: inherit; color: inherit; padding: 0px; position: relative;"

												type="checkbox"><label for="PluginsRoomPluginKeyPhotoAlbums"

																	   class="selected" style="box-sizing: border-box; display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 400; min-height: 20px; padding-left: 0px; cursor: pointer;">動
											画</label></div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-footer text-center" style="box-sizing: border-box; text-align: center; padding: 10px 15px; background-color: rgb(245, 245, 245); border-top: 1px solid rgb(221, 221, 221); border-bottom-right-radius: 3px; border-bottom-left-radius: 3px;">
						<button name="save" class="btn btn-danger" type="submit" style="box-sizing: border-box; margin: 0px 6px; font-style: inherit; font-variant: inherit; font-weight: 400; font-stretch: inherit; font-size: 14px; line-height: 1.42857; font-family: inherit; color: rgb(255, 255, 255); overflow: visible; text-transform: none; -webkit-appearance: button; cursor: pointer; display: inline-block; padding: 6px 12px; text-align: center; white-space: nowrap; vertical-align: middle; touch-action: manipulation; user-select: none; background-image: none; border: 1px solid rgb(212, 63, 58); border-radius: 4px; background-color: rgb(217, 83, 79);">
							<span class="glyphicon glyphicon-trash" style="box-sizing: border-box; position: relative; top: 1px; display: inline-block; font-family: &quot;Glyphicons Halflings&quot;; font-style: normal; font-weight: 400; line-height: 1; -webkit-font-smoothing: antialiased;"></span>
							<span>&nbsp;</span> 削除</button> </div>
				</form>
			</div>
		</article>
	</div>
</div>
