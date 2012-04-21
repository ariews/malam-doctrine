<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

if (! defined('APP_DOCTRINE_PATH'))
    define('APP_DOCTRINE_PATH', APPPATH.'doctrine'.DIRECTORY_SEPARATOR);

return array(
    // see config/database.php
    'database_group' => 'default',

    // doctrine config path
    'config' => array(
        'data_fixtures_path'    => APP_DOCTRINE_PATH.'data/fixtures',
        'models_path'           => APP_DOCTRINE_PATH.'models',
        'migrations_path'       => APP_DOCTRINE_PATH.'migrations',
        'sql_path'              => APP_DOCTRINE_PATH.'data/sql',
        'yaml_schema_path'      => APP_DOCTRINE_PATH.'schema',
    )
);