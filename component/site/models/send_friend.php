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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'mail.php' );

class send_friendModelsend_friend extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_product = null; // product data
	var $_table_prefix = null;
	var $_template = null;

	function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$this->setId((int)JRequest::getVar('pid',  0));

	}
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}
	function sendProductMailToFriend($your_name,$friend_name,$product_id,$email)
	{
		$producthelper = new producthelper();
		$redshopMail = new redshopMail();
		$url= JURI::base();
		$option = JRequest::getVar('option');

		$mailinfo = $redshopMail->getMailtemplate(0,"product");
		$data_add = "";
		$subject = "";
		$mailbcc=NULL;
		if(count($mailinfo)>0)
		{
			$data_add = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;
			if(trim($mailinfo[0]->mail_bcc)!="")
			{
				$mailbcc= explode(",",$mailinfo[0]->mail_bcc);
			}
		} else {
			$data_add = "<p>Hi {friend_name} ,</p>\r\n<p>New Product  : {product_name}</p>\r\n<p>{product_desc} Please check this link : {product_url}</p>\r\n<p> </p>\r\n<p> </p>";
			$subject = "Send to friend";
		}

		$data_add =str_replace("{friend_name}",$friend_name,$data_add);
		$data_add =str_replace("{your_name}",$your_name,$data_add);

		$product = $producthelper->getProductById($product_id);

		$data_add =str_replace("{product_name}",$product->product_name,$data_add);
		$data_add =str_replace("{product_desc}",$product->product_desc,$data_add);

		$rlink 	= JRoute::_( $url."index.php?option=".$option."&view=product&pid=".$product_id);
		$product_url = "<a href=".$rlink.">".$rlink."</a>";
		$data_add =str_replace("{product_url}",$product_url,$data_add);

		$config		= &JFactory::getConfig();
		$from		= $config->getValue('mailfrom');
		$fromname	= $config->getValue('fromname');

		$subject =str_replace("{product_name}",$product->product_name,$subject);
		$subject =str_replace("{shopname}",SHOP_NAME,$subject);

		if($email!="")
		{
			if(JFactory::getMailer()->sendMail($from, $fromname, $email, $subject, $data_add, 1, NULL, $mailbcc))
			{
				echo "<div class='' align='center'>".JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY')."</div>";
			}
			else
			{
				echo "<div class='' align='center'>".JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY')."</div>";
			}
		}
		exit;
	}
}?>