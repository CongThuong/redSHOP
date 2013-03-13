<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class Tableshipping_detail extends JTable
{
	var $shipping_id = null;
	var $shipping_name = null;
	var $shipping_class = null;
	var $shipping_method_code = null;
	var $published = null;
	var $shipping_details = null;
	var $params = null;
	var $plugin = null;
	var $ordering = null;


	function Tableshipping_detail(& $db)
	{
		$this->_table_prefix = '#__extensions';

		parent::__construct($this->_table_prefix, 'extension_id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists('params', $array) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

}
