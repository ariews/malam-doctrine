<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

abstract class Malam_Controller_Cli_Migration extends Controller
{
    public function action_index()
    {
        echo Malam_Doctrine::info();
    }

    public function action_run()
    {
        $version = $this->request->param('version');
        echo Malam_Doctrine::run($version);
    }

    public function action_create()
    {
        $name = Arr::get(CLI::options('name'), 'name', NULL);
        echo Malam_Doctrine::create($name);
    }

    public function action_reset()
    {
        echo Malam_Doctrine::reset();
    }
}