<?php
/**
 * Mini blag
 * 
 * Miniature blag for php that I figured I'd put together to rant on while
 * coding on other things, i.e. learning Ruby on Rails and Django.
 * 
 * @todo impliment bcrypt on the auth.
 * @todo remove the DS constant.
 * @todo make import global.
 * @todo make logging global.
 * @todo make errors global.
 * 
 * 
 */
namespace MagicPotion;

error_reporting(-1);

define('ROOT', str_replace('\\','/',dirname(__FILE__)));

include(ROOT.'/system/helper.php');
/**
 * import files elegantly
 */
function import($str)
{
	$helper = new Import();
	return $helper->import($str);
}


function log_msg($str)
{
	
}


import('sys.magicpotion');

#$logger = Logger::get_instance();
#$logger->log_msg('Logger initiated');

//$main = MagicPotion::get_instance();
$main = new MagicPotion();
#$logger->log_msg('Application instanciated, initiating');

$main->init();
#$logger->log_msg('Application initiated, routing');

$main->route();
#$logger->log_msg('Application routed, dispatching');

$main->dispatch();
#$logger->log_msg('Application dispatched, executing');

$main->run();
#$logger->log_msg('Application execution completed');

$main->__destruct();
#$logger->debugger();

print 'teapot';
exit;
/**
 * EOF /index.php
 */
