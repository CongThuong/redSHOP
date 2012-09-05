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

jimport ( 'joomla.application.component.controller' );

class wrapper_detailController extends JController {

	function __construct($default = array()) { 
		parent::__construct ( $default );
		$this->registerTask ( 'add', 'edit' );
	}
	function edit() 
	{
		JRequest::setVar ( 'view', 'wrapper_detail' );
		JRequest::setVar ( 'layout', 'default' );
		JRequest::setVar ( 'hidemainmenu', 1 );
		$model = $this->getModel ( 'wrapper_detail' );

		parent::display ();
	}
	function save() 
	{
		$showall = JRequest::getVar('showall','0');
		$page = "";
		if($showall)
		{
			$page = "3";
		}
		$post = JRequest::get ( 'post' );
		$post['product_id'] = (isset($post['container_product'])) ? $post['container_product'] : 0;
		$option = JRequest::getVar ('option');
		$product_id = JRequest::getInt('product_id',0);
		$category_id = JRequest::getVar ('category_id');
		
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		$post ['wrapper_id'] = $cid [0];

		$model = $this->getModel ( 'wrapper_detail' );
		if ($model->store ( $post )) {
			$msg = JText::_('COM_REDSHOP_WRAPPER_DETAIL_SAVED' );
		} else {
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_WRAPPER_DETAIL' );
		}
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=wrapper&showall='.$showall.'&product_id='.$product_id, $msg );
	}
	function remove() {
		
		$showall = JRequest::getVar('showall','0');
		$page = "";
		if($showall)
		{
			$page = "3";
		}
		$option = JRequest::getVar ('option');
		$product_id = JRequest::getVar ('product_id');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE' ) );
		}
		
		$model = $this->getModel ( 'wrapper_detail' );
		if (! $model->delete ( $cid )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_WRAPPER_DETAIL_DELETED_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=wrapper&showall='.$showall.'&product_id='.$product_id,$msg );
	}

	function cancel() {
		
		$showall = JRequest::getVar('showall','0');
		$page = "";
		if($showall)
		{
			$page = "3";
		}
		$option = JRequest::getVar ('option');
		$product_id = JRequest::getVar ('product_id');
		
		$msg = JText::_('COM_REDSHOP_WRAPPER_DETAIL_EDITING_CANCELLED' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=wrapper&showall='.$showall.'&product_id='.$product_id,$msg );
	}
	/**
	 * logic for publish
	 *
	 * @access public
	 * @return void
	 */
	function publish()
	{
		$showall = JRequest::getVar('showall','0');
		$page = "";
		if($showall)
		{
			$page = "3";
		}
		$option = JRequest::getVar ('option');
		$product_id = JRequest::getVar ('product_id');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		$model = $this->getModel ( 'wrapper_detail' );
		if (! $model->publish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_WRAPPER_PUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=wrapper&showall='.$showall.'&product_id='.$product_id,$msg );
	}
	/**
	 * logic for unpublish
	 *
	 * @access public
	 * @return void
	 */
	function unpublish()
	{
		$showall = JRequest::getVar('showall','0');
		$page = "";
		if($showall)
		{
			$page = "3";
		}
		$option = JRequest::getVar ('option');
		$product_id = JRequest::getVar ('product_id');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		$model = $this->getModel ( 'wrapper_detail' );
		if (! $model->publish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_WRAPPER_UNPUBLISHED_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=wrapper&showall='.$showall.'&product_id='.$product_id,$msg );
	}
	function enable_defaultpublish() {
		$showall = JRequest::getVar('showall','0');
		$page = "";
		if($showall)
		{
			$page = "3";
		}
		$option = JRequest::getVar('option');
		$product_id = JRequest::getVar ('product_id');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH' ) );
		}
		
		$model = $this->getModel ( 'wrapper_detail' );
		if (! $model->enable_defaultpublish ( $cid, 1 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_USE_TO_ALL_ENABLE_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=wrapper&showall='.$showall.'&product_id='.$product_id,$msg );
	}
	
	function enable_defaultunpublish() {
		
		$showall = JRequest::getVar('showall','0');
		$page = "";
		if($showall)
		{
			$page = "3";
		}
		$option = JRequest::getVar('option');
		$product_id = JRequest::getVar ('product_id');
		$cid = JRequest::getVar ( 'cid', array (0 ), 'post', 'array' );
		
		if (! is_array ( $cid ) || count ( $cid ) < 1) {
			JError::raiseError ( 500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH' ) );
		}
		
		$model = $this->getModel ( 'wrapper_detail' );
		if (! $model->enable_defaultpublish ( $cid, 0 )) {
			echo "<script> alert('" . $model->getError ( true ) . "'); window.history.go(-1); </script>\n";
		}
		$msg = JText::_('COM_REDSHOP_USE_TO_ALL_DISABLE_SUCCESSFULLY' );
		$this->setRedirect ( 'index'.$page.'.php?option='.$option.'&view=wrapper&showall='.$showall.'&product_id='.$product_id,$msg );
	}
}	?>