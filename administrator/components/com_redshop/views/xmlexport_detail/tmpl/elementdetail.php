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
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'xmlhelper.php');

$option = JRequest::getVar('option');
$section_type = JRequest::getVar('section_type');
$parentsection = JRequest::getVar('parentsection');
$model = $this->getModel('xmlexport_detail');
$uri = JURI::getInstance ();
$url = $uri->root ();	?>

<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	submitbutton(pressbutton);
	}

submitbutton = function(pressbutton)
{
	var form = document.adminForm;
	if (form.element_name.value == ""){
		alert( "<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_XMLEXPORT_CHILD_ELEMENT_NAME', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>
<form action="<?php echo JRoute::_($this->request_url); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col50">
	<table class="admintable">
	<tr><td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_XMLEXPORT_ELEMENT_NAME' ); ?>:</td>
		<td><input type="text" name="element_name" id="element_name" value="<?php echo $this->childname;?>" /></td></tr>
	<tr><th><?php echo JText::_('COM_REDSHOP_FIELD_NAME' ); ?></th><th><?php echo JText::_('COM_REDSHOP_XMLEXPORT_FILE_TAG_NAME' ); ?></th></tr>
<?php	for($i=0;$i<count($this->columns);$i++)
		{	?>
	<tr><td width="100" align="right" class="key"><?php echo $this->columns[$i]->Field; ?>:</td>
		<td><input type="text" name="<?php echo $this->columns[$i]->Field; ?>" id="<?php echo $this->columns[$i]->Field; ?>" value="<?php echo $this->colvalue[$i];?>" /></td></tr>
<?php 	}	?>
	<tr><td width="100" align="right" class="key">&nbsp;</td>
		<td><input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo JText::_('COM_REDSHOP_SAVE');?>" /></td></tr>
	</table>
</div>

<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->xmlexport_id; ?>" />
<input type="hidden" name="xmlexport_id" id="xmlexport_id" value="<?php echo $this->detail->xmlexport_id; ?>" />
<input type="hidden" name="task" id="task" value="setChildElement" />
<input type="hidden" name="section_type" id="section_type" value="<?php echo $section_type;?>" />
<input type="hidden" name="parentsection" id="parentsection" value="<?php echo $parentsection;?>" />
<input type="hidden" name="view" value="xmlexport_detail" />
</form>
