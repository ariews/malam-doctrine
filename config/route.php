<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

return array(
    'migration' => array(
        'is_cli'        => TRUE,
        'uri_callback'  => 'migration(/<action>(/<version>))',
        'regex'         => array(
            'action'    => 'index|create|run|reset',
            'version'   => '\d+'
        ),
        'defaults'      => array(
            'controller'=> 'migration',
            'action'    => 'index',
            'directory' => 'cli'
        )
    )
);