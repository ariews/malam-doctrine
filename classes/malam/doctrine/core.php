<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Doctrine_Core
{
    private static $_migration;
    private static $_config;

    public static function initialize()
    {
        if ( ! extension_loaded('pdo'))
        {
            throw new Kohana_Exception(__('PDO needs to be installed with MySQL support'));
        }

        require Kohana::find_file('vendor', 'doctrine/lib/Doctrine');
        spl_autoload_register(array('Doctrine', 'autoload'));

        $config   = Kohana::$config->load('migrations');
        $database = Kohana::$config->load('database')->{$config->database_group};

        // KOHANA TABLE PREFIX
        if (! defined('KTPREFIX'))
            define('KTPREFIX', $database['table_prefix']);

        $manager = Doctrine_Manager::getInstance();
        
        if (isset($database['dsn']))
        {
            $manager->openConnection($database['dsn']);
        }
        else
        {
            $manager->openConnection(
                $database['type'].'://'.
                $database['connection']['username'].':'.
                $database['connection']['password'].'@'.
                $database['connection']['hostname'].'/'.
                $database['connection']['database']
            );
        }
        
        $manager->setAttribute(Doctrine_Core::ATTR_TBLNAME_FORMAT, "{$database['table_prefix']}_%s");
        $manager->setAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT, "{$database['table_prefix']}%s");

        $config = self::$_config = Kohana::$config->load('migrations')->config;

        if (! file_exists($config['migrations_path']))
        {
            if ( ! mkdir($config['migrations_path'], 0777, TRUE))
            {
                throw new Kohana_Exception('Failed to create the migration directory : :directory',
                    array(':directory' => $config['migrations_path'] ));
            }
            chmod($config['migrations_path'], 0777);
        }

        self::$_migration = new Doctrine_Migration($config['migrations_path']);
    }

    public static function create($name = NULL)
    {
        if (NULL === $name)
            return;

        $resp = Doctrine_Core::generateMigrationClass($name, self::$_config['migrations_path']);

        if (FALSE !== $resp)
        {
            return __("Successfully generated migration class: :class_name\n", array(
                ':class_name' => $name
            ));
        }
    }

    public static function reset()
    {
        return self::do_migrate(0);
    }

    public static function run($version = NULL)
    {
        if (NULL === $version)
            $version = self::$_migration->getLatestVersion();

        return self::do_migrate($version);
    }

    public static function info()
    {
        return __("Current database migration version: #:version\n", array(
            ':version' => self::$_migration->getCurrentVersion()
        ));
    }

    private static function do_migrate($version)
    {
        $current = self::$_migration->getCurrentVersion();

        if ($current == $version)
        {
            return __("Database migration is already #:current.\n", array(
                ':current' => $current
            ));
        }

        try
        {
            self::$_migration->migrate($version);
            return __("Database migration is complete. Database version was #:current and now is #:version\n", array(
                ':current' => $current,
                ':version' => $version
            ));
        }
        catch(Doctrine_Exception $e)
        {
            Kohana::$log->add(Log::ERROR, strip_tags($e->getMessage()));
            return __("Database migration failed, check the Kohana log.\n");
        }
    }
}