<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

abstract class Malam_Controller_Cli_Migration extends Controller
{
    protected $_message = '';

    public function action_index()
    {
        $this->_message = Malam_Doctrine::info();
    }

    public function action_run()
    {
        $version = $this->request->param('version');
        $this->_message = Malam_Doctrine::run($version);
    }

    public function action_create()
    {
        $name = Arr::get(CLI::options('name'), 'name', NULL);
        $this->_message = Malam_Doctrine::create($name);
    }

    public function action_reset()
    {
        $this->_message = Malam_Doctrine::reset();
    }

    public function after()
    {
        $this->response->body($this->_message);
        parent::after();
    }
}