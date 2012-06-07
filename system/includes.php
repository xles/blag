<?php
/**
 * Magic Potion.
 *
 * MOAR INFOZ!!
 *
 * @package	org.mirakulix.magic-potion
 * @subpackage
 * @author	xles <xles@mirakulix.org>
 * @copyright	Copyright (c) xles 2010, http://web.mirakulix.org/
 */
namespace MagicPotion;
use MagicPotion\Helper;

/**
 * Includes
 */

#Helper::import('inc.factory');
import('inc.error');
import('inc.logger');
import('inc.config');
import('inc.database');
import('inc.session');
import('inc.locale');
import('inc.router');
import('inc.rewrite');
import('inc.template');
import('inc.auth');

#Helper::import('inc.builder');
#Helper::import('inc.upload');
#Helper::import('inc.syndication');
#Helper::import('inc.bbcode');

/**
 * Components
 */
#Helper::import('com.news.controller');

/**
 * Modules
 */
#Helper::import('mod.auth.class');
#Helper::import('mod.user.class');
#Helper::import('mod.info.class');
#Helper::import('mod.comic.class');
#Helper::import('mod.forum.class');
#Helper::import('mod.chan.class');
#Helper::import('mod.chat.class');
#Helper::import('mod.gallery.class');


/**
 * EOF /application/includes.php
 */
