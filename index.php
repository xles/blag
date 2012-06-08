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
 * @todo make config global.
 * @todo make an error page dispatch thingamaboober, that sets HTTP status.
 */
namespace MagicPotion;

error_reporting(-1);

define('ROOT', str_replace('\\','/',dirname(__FILE__)));

include(ROOT.'/system/core.php');

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
#$log->msg('Application dispatched, executing');

$main->run();
#$logger->log_msg('Application execution completed');

$main->__destruct();
#$logger->debugger();

exit;
/**
 * EOF /index.php
 */
