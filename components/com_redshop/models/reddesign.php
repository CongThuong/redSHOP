<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class reddesignModelreddesign extends JModelLegacy
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    function __construct()
    {
        // redshop product detail
        $this->_pid = (int)JRequest::getVar('pid', 0);
        $this->_cid = (int)JRequest::getVar('cid', 0);

        $this->_table_prefix = '#__reddesign_';
        parent::__construct();
    }

    function getDesignTypeImages($designtype_id)
    {
        $db = & JFactory :: getDBO();

        $table = $this->_table_prefix . "image";
        $query = "SELECT * FROM " . $table . " WHERE designtype_id = " . $designtype_id . " order by ordering";
        $db->setQuery($query);

        return $db->loadObjectlist();
    }

    function getProductDetail($product_id, $field_name = "")
    {

        $db = & JFactory :: getDBO();
        if (!$field_name)
        {
            $query = 'SELECT * FROM `#__redshop_product` WHERE product_id = ' . $product_id;
        }
        else
        {
            $query = 'SELECT $field_name FROM `#__redshop_product` WHERE product_id = ' . $product_id;
        }
        $db->setQuery($query);
        return $db->loadObject();
    }

    function getProductDesign($product_id)
    {
        $query = "SELECT * FROM `#__reddesign_redshop` WHERE `product_id` = '" . $product_id . "'";
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
}
