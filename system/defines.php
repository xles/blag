<?php
// <editor-fold defaultstate="collapsed" desc="Header DocBlock">
/**
 * Magic Potion, defines.php
 *
 * 
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage	defines
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2011
 * @link	http://web.mirakulix.org/
 */
// </editor-fold>
namespace MagicPotion;
use MagicPotion\Config;

/**
 * 
 */
define('DEBUG',TRUE);
#define('TEMPLATE','beta');

#define('BASEPATH',DS.str_replace($_SERVER['DOCUMENT_ROOT'],'',ROOT));
define('BASEPATH',str_replace(rtrim($_SERVER['DOCUMENT_ROOT'],'/'),'',ROOT));
define('NL',"\n");

$copyright = NL.'Powered by Magic Potion&trade;.
Copyright <a href="http://web.mirakulix.org/">mirakulix.org</a>
&copy; 2005-2011.'.NL;
define('COPYRIGHT',$copyright);
/**
 * EOF /
 */