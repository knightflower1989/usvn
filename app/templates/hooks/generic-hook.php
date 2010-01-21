#!/usr/bin/env php
<?php

/****************/
/* Include USVN */
/****************/

define('APPLICATION_PATH', '${USVN_app_path}');
define('USVN_BASE_DIR', '${USVN_base_dir}');

define('USVN_APP_DIR',          USVN_BASE_DIR   . '/app');
define('USVN_LIB_DIR',          USVN_BASE_DIR   . '/library');
define('USVN_PUB_DIR',          USVN_BASE_DIR   . '/public');
define('USVN_CONFIG_DIR',       USVN_BASE_DIR   . '/config');
define('USVN_FILES_DIR',        USVN_BASE_DIR   . '/files');

define('USVN_CONFIG_FILE',      USVN_CONFIG_DIR . '/config.ini');
define('USVN_HTACCESS_FILE',    USVN_PUB_DIR    . '/.htaccess');
define('USVN_LOCALE_DIRECTORY', USVN_APP_DIR    . '/locale');

// Define application environment
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(USVN_LIB_DIR),
	get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('USVN_');
$autoloader->setFallbackAutoloader(true);

/********************/
/* End Include USVN */
/********************/

if (file_exists(USVN_CONFIG_FILE))
{
	try
	{
		$config = new USVN_Config_Ini(USVN_CONFIG_FILE, USVN_CONFIG_SECTION);
		if (isset($config->translation->locale))
			$GLOBALS['language'] = $config->translation->locale;
		if (isset($config->timezone))
			date_default_timezone_set($config->timezone);
		if (isset($config->system->locale))
			USVN_ConsoleUtils::setLocale($config->system->locale);
		if (isset($config->database->adapterName))
		{
			Zend_Db_Table::setDefaultAdapter(Zend_Db::factory($config->database->adapterName, $config->database->options->toArray()));
			Zend_Db_Table::getDefaultAdapter()->getProfiler()->setEnabled(true);
			USVN_Db_Table::$prefix = $config->database->prefix;
		}
		Zend_Registry::set('config', $config);
	}
	catch (Exception $e)
	{
	}
}

// Begin code

// Auto-generated
$projectId = '${USVN_project_id}';
$hooksPath = '${USVN_hooks_path}';
$hookEvent = '${USVN_hook_event}';
//

$argv = $_SERVER['argv'];
array_shift($argv);
array_walk($argv, create_function('&$item', '$item = \'"\' . $item . \'"\';'));
$arguments = join(' ', $argv);

$table = new USVN_Db_Table_Projects();
$project = $table->find($projectId)->current();
$hooks = $project->findManyToManyRowset('USVN_Db_Table_Hooks', 'USVN_Db_Table_ProjectsToHooks');
foreach ($hooks as $hook)
{
	if ($hook->event == $hookEvent) {
		$hookexec = $hooksPath . DIRECTORY_SEPARATOR . $hook->path;
		if (file_exists($hookexec))
		{
			$cmd = "$hookexec $arguments";
			$returnValue = USVN_ConsoleUtils::runCmd($cmd);
			if ($returnValue != 0)
			{
				exit($returnValue);
			}
		}
	}
}

exit(0);
