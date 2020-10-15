<?php
/**
 * CleanUp Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * CleanUp Model
 *
 * @author Mitsuru Mutaguchi <mutaguchi@opensource-workshop.jp>
 * @package NetCommons\CleanUp\Model
 * @property UploadFile $UploadFile
 */
class CleanUpSchemaMigration extends CleanUpAppModel {
/**
 * Use table config
 *
 * @var bool
 */
    public $useTable = 'schema_migrations';
/**
 * Use table alias
 *
 * @var bool
 */
    public $alias = 'SchemaMigration';    
}