<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

//get module path
$mod_dir = dirname( __FILE__ );

define('LETTERSEARCH_MODULE_PATH',$mod_dir);

require_once(LETTERSEARCH_MODULE_PATH.DS.'helper.php');
$lettersearchHelper = new modlettersearchHelper();

$selected_field = trim( $params->get( 'list_of_fields','') );
$db	=	JFactory::getDBO();
$getcharacters = $lettersearchHelper->getDefaultModulecharacters($selected_field);



$number_of_items = trim( $params->get( 'count_products',5) );	// get show number of products
$number_of_columns = trim( $params->get( 'number_of_columns',6) );	// get show number of Columns

require(JModuleHelper::getLayoutPath('mod_redshop_lettersearch'));
