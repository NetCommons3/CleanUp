<?php
/**
 * CleanUpTest Utility
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */


/**
 * CleanUpTest Utility
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Test\Case
 */
class CleanUpTestUtil {

/**
 * テストファイル作成
 *
 * @return void
 */
	public static function makeTestUploadFiles() {
		//アップロードファイルで、削除対象のファイルを用意
		//1) CleanUpConsoleCommandCleanUpShellCleanUpTest::testCleanUp
		//finfo::file(/var/www/app/app/Uploads/files/upload_file/test/12/michel2.gif): failed to open stream: No such file or directory
		//finfo::file(/var/www/app/app/Uploads/files/upload_file/test/13/michel2.gif): failed to open stream: No such file or directory
		$fileName1 = 'michel2.gif';
		$uploadIds = [12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22];
		foreach ($uploadIds as $uploadId) {
			//$path1 = ROOT . DS . APP_DIR . DS . 'Uploads' . DS . 'files' . DS . 'upload_file' . DS . 'test' . DS . '12';
			//$path1 = ROOT . DS . APP_DIR . DS . 'Uploads' . DS . 'files' . DS . 'upload_file' . DS . 'test' . DS . '13';
			$path1 = ROOT . DS . APP_DIR . DS . 'Uploads' . DS . 'files' . DS . 'upload_file' . DS . 'test' . DS . $uploadId;
			$file1 = $path1 . DS . $fileName1;
			$folder = new Folder();
			$folder->create($path1);
			touch($file1);
			file_put_contents($file1, 1);
			//var_dump($file1);
		}
	}

}
