<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'text_library.php');

class categoryViewcategory extends JViewLegacy
{
    public function display($tpl = null)
    {

        //echo SHOW_PRICE;die();
        global $context;
        $mainframe     = JFactory::getApplication();
        $objhelper     = new redhelper();
        $prodhelperobj = new producthelper();

        // Request variables
        $option = JRequest::getVar('option', 'com_redshop');
        $catid  = JRequest::getInt('cid', 0, '', 'int');
        $layout = JRequest::getVar('layout');

        $print  = JRequest::getVar('print');
        $params = &$mainframe->getParams($option);
        $model  = $this->getModel('category');

        $category_template     = ( int )$params->get('category_template');
        $menu_meta_keywords    = $params->get('menu-meta_keywords');
        $menu_robots           = $params->get('robots');
        $menu_meta_description = $params->get('menu-meta_description');

        if (!$catid && $layout == 'detail')
        {
            $catid = $params->get('cid');
            $this->setLayout('detail');
        }

        if (!isset($layout) && $catid > 0)
        {
            $this->setLayout('detail');
        }

        $document = JFactory::getDocument();

        JHTML::Script('jquery.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);

        JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
        JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);

        //JHTML::Script('fetchscript.js', 'components/com_redshop/assets/js/',false);
        JHTML::Stylesheet('priceslider.css', 'components/com_redshop/assets/css/');
        // Start Code for fixes IE9 issue
        //JHTML::Stylesheet('jquery-ui-1.css', 'components/com_redshop/assets/css/');
        $document->addStyleSheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css');
        // End Code for fixes IE9 issue
        //JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');

        $lists   = array();
        $minmax  = array(0, 0);
        $product = array();

        $maincat = $model->_loadCategory();

        $allCategoryTemplate  = $model->getCategoryTemplate();
        $order_data           = $objhelper->getOrderByList();
        $manufacturers        = $model->getManufacturer();
        $loadCategorytemplate = $model->loadCategoryTemplate();
        $detail               = $model->getdata();

        if ($catid)
        {
            # restrict category if category not published
            if ($maincat->published == 0)
            {
                JError::raiseError(404, sprintf(JText::_('COM_REDSHOP_CATEGORY_IS_NOT_PUBLISHED'), $maincat->category_name, $maincat->category_id));
            }
            $isSlider = false;
            if (count($loadCategorytemplate) > 0 && strstr($loadCategorytemplate[0]->template_desc, "{product_price_slider}"))
            {
                $limit_product =& $model->getCategoryProduct(1);
                $minmax[0]     = $limit_product[0]->minprice;
                $minmax[1]     = $limit_product[0]->maxprice;

                $isSlider    = true;
                $texpricemin = JRequest::getInt('texpricemin', $minmax[0], '', 'int');
                $texpricemax = JRequest::getInt('texpricemax', $minmax[1], '', 'int');
                $model->setMaxMinProductPrice(array($texpricemin, $texpricemax));
            }
            $product =& $model->getCategoryProduct(0, $isSlider);

            $document->setMetaData('keywords', $maincat->metakey);
            $document->setMetaData('description', $maincat->metadesc);
            $document->setMetaData('robots', $maincat->metarobot_info);

            // for page title
            $pagetitletag = SEO_PAGE_TITLE_CATEGORY;
            $parentcat    = "";
            $parentid     = $prodhelperobj->getParentCategory($maincat->category_id);
            while ($parentid != 0)
            {
                $parentdetail = $prodhelperobj->getSection("category", $parentid);
                $parentcat    = $parentdetail->category_name . "  " . $parentcat;
                $parentid     = $prodhelperobj->getParentCategory($parentdetail->category_id);
            }

            $pagetitletag = str_replace("{parentcategoryloop}", $parentcat, $pagetitletag);
            $pagetitletag = str_replace("{categoryname}", $maincat->category_name, $pagetitletag);
            $pagetitletag = str_replace("{shopname}", SHOP_NAME, $pagetitletag);
            $pagetitletag = str_replace("{categoryshortdesc}", strip_tags($maincat->category_short_description), $pagetitletag); //die();

            if ($maincat->pagetitle != "" && AUTOGENERATED_SEO && SEO_PAGE_TITLE_CATEGORY != '')
            {

                if ($maincat->append_to_global_seo == 'append')
                {
                    $pagetitletag = $pagetitletag . $maincat->pagetitle;
                    $document->setTitle($pagetitletag);
                }
                else if ($maincat->append_to_global_seo == 'prepend')
                {
                    $pagetitletag = $maincat->pagetitle . $pagetitletag;
                    $document->setTitle($pagetitletag);
                }
                else if ($maincat->append_to_global_seo == 'replace')
                {
                    $document->setTitle($maincat->pagetitle);
                }
            }
            else if ($maincat->pagetitle != "")
            {
                $document->setTitle($maincat->pagetitle);
            }
            else if (AUTOGENERATED_SEO && SEO_PAGE_TITLE_CATEGORY != '')
            {

                $document->setTitle($pagetitletag);
            }
            else
            {
                $document->setTitle($mainframe->getCfg('sitename'));
            }

            if (AUTOGENERATED_SEO && SEO_PAGE_KEYWORDS_CATEGORY != '')
            {
                $pagekeywordstag = SEO_PAGE_KEYWORDS_CATEGORY;
                $pagekeywordstag = str_replace("{categoryname}", $maincat->category_name, $pagekeywordstag);
                $pagekeywordstag = str_replace("{categoryshortdesc}", strip_tags($maincat->category_short_description), $pagekeywordstag);
                $pagekeywordstag = str_replace("{shopname}", SHOP_NAME, $pagekeywordstag);
                $document->setMetaData('keywords', $pagekeywordstag);
            }
            if (trim($maincat->metakey) != '' && AUTOGENERATED_SEO && SEO_PAGE_KEYWORDS_CATEGORY != '')
            {
                if ($maincat->append_to_global_seo == 'append')
                {
                    $pagekeywordstag = $pagekeywordstag . "," . trim($maincat->metakey);
                    $document->setMetaData('keywords', $pagekeywordstag);
                }
                else if ($maincat->append_to_global_seo == 'prepend')
                {
                    $pagekeywordstag = trim($maincat->metakey) . $pagekeywordstag;
                    $document->setMetaData('keywords', $pagekeywordstag);
                }
                else if ($maincat->append_to_global_seo == 'replace')
                {
                    $document->setMetaData('keywords', $maincat->metakey);
                }
            }
            else
            {

                if ($maincat->metakey != '')
                {
                    $document->setMetaData('keywords', $maincat->metakey);
                }
                else
                {
                    if (AUTOGENERATED_SEO && SEO_PAGE_KEYWORDS_CATEGORY != '')
                    {
                        $document->setMetaData('keywords', $pagekeywordstag);
                    }
                    else
                    {
                        $document->setMetaData('keywords', $maincat->category_name);
                    }
                }
            }

            // for custom + auto generated description
            if (AUTOGENERATED_SEO && SEO_PAGE_DESCRIPTION_CATEGORY != '')
            {
                $pagedesctag = SEO_PAGE_DESCRIPTION_CATEGORY;
                $pagedesctag = str_replace("{categoryname}", $maincat->category_name, $pagedesctag);
                $pagedesctag = str_replace("{shopname}", SHOP_NAME, $pagedesctag);
                $pagedesctag = str_replace("{categoryshortdesc}", strip_tags($maincat->category_short_description), $pagedesctag);
                $pagedesctag = str_replace("{categorydesc}", strip_tags($maincat->category_description), $pagedesctag); //die();

            }
            if ($maincat->metadesc != '' && AUTOGENERATED_SEO && SEO_PAGE_DESCRIPTION_CATEGORY != '')
            {
                if ($maincat->append_to_global_seo == 'append')
                {
                    $pagedesctag = $pagedesctag . $maincat->metadesc;
                    $document->setMetaData('description', $pagedesctag);
                }
                else if ($maincat->append_to_global_seo == 'prepend')
                {
                    $pagedesctag = trim($maincat->metadesc) . $pagedesctag;
                    $document->setMetaData('description', $pagedesctag);
                }
                else if ($maincat->append_to_global_seo == 'replace')
                {
                    $document->setMetaData('description', $maincat->metadesc);
                }
            }
            else if ($maincat->metadesc != '')
            {
                $document->setMetaData('description', $maincat->metadesc);
            }
            else
            {
                if (AUTOGENERATED_SEO && SEO_PAGE_DESCRIPTION_CATEGORY != '')
                {

                    $document->setMetaData('description', $pagedesctag);
                }
                else
                {
                    $document->setMetaData('description', $maincat->category_name);
                }
            }

            // for metarobot
            if ($maincat->metarobot_info != '')
            {
                $document->setMetaData('robots', $maincat->metarobot_info);
            }
            else
            {
                if (AUTOGENERATED_SEO && SEO_PAGE_ROBOTS != '')
                {
                    $pagerobotstag = SEO_PAGE_ROBOTS;
                    $document->setMetaData('robots', $pagerobotstag);
                }
                else
                {
                    $document->setMetaData('robots', "INDEX,FOLLOW");
                }
            }

            $pageheadingtag = str_replace("{categoryname}", $maincat->category_name, SEO_PAGE_HEADING_CATEGORY);
            if ($maincat->pageheading != "" && AUTOGENERATED_SEO && SEO_PAGE_HEADING_CATEGORY != '')
            {
                $pageheadingtag = $pageheadingtag . $maincat->pageheading;
            }
            else if ($maincat->pageheading != "")
            {
                $pageheadingtag = $maincat->pageheading;
            }
            else if (AUTOGENERATED_SEO && SEO_PAGE_HEADING_CATEGORY != '')
            {
                $pageheadingtag = $pageheadingtag;
            }
            else
            {
                $pageheadingtag = $mainframe->getCfg('sitename');
            }
        }
        else
        {
            if ($menu_meta_keywords != "")
            {
                $document->setMetaData('keywords', $menu_meta_keywords);
            }
            else
            {
                $document->setMetaData('keywords', $mainframe->getCfg('sitename'));
            }

            if ($menu_meta_description != "")
            {
                $document->setMetaData('description', $menu_meta_description);
            }
            else
            {
                $document->setMetaData('description', $mainframe->getCfg('sitename'));
            }

            if ($menu_robots != "")
            {
                $document->setMetaData('robots', $menu_robots);
            }
            else
            {
                $document->setMetaData('robots', $mainframe->getCfg('sitename'));
            }
        }

        //-------------- Breadcrumbs -------------------
        $prodhelperobj->generateBreadcrumb($catid);
        $disabled = "";
        if ($print)
        {
            $disabled = "disabled";
        }

        $selected_template = 0;
        if ($catid)
        {
            if (isset($category_template) && $category_template)
            {
                $selected_template = $category_template;
            }
            else if (isset($maincat->category_template))
            {
                $selected_template = $maincat->category_template;
            }
        }
        else
        {
            $selected_template = DEFAULT_CATEGORYLIST_TEMPLATE;
        }
        $category_template_id = $mainframe->getUserStateFromRequest($this->_context . 'category_template', 'category_template', $selected_template); # Gunjan Template Switcher
        $order_by_select      = JRequest::getVar('order_by', '');
        $manufacturer_id      = JRequest::getInt('manufacturer_id', 0, '', 'int');

        $lists['category_template'] = "";
        $lists['manufacturer']      = "";

        if (count($manufacturers) > 0)
        {
            $temps                       = array();
            $temps[0]->manufacturer_id   = "0";
            $temps[0]->manufacturer_name = JText::_('COM_REDSHOP_SELECT_MANUFACTURE');
            $manufacturers               = array_merge($temps, $manufacturers);
            $lists['manufacturer']       = JHTML::_('select.genericlist', $manufacturers, 'manufacturer_id', 'class="inputbox" onchange="javascript:setSliderMinMaxForManufactur();" ' . $disabled . ' ', 'manufacturer_id', 'manufacturer_name', $manufacturer_id);
        }
        if (count($allCategoryTemplate) > 1)
        {
            $lists['category_template'] = JHTML::_('select.genericlist', $allCategoryTemplate, 'category_template', 'class="inputbox" size="1" onchange="javascript:setSliderMinMaxForTemplate();" ' . $disabled . ' ', 'template_id', 'template_name', $category_template_id);
        }
        if ($order_by_select == '')
        {
            $order_by_select = $params->get('order_by', DEFAULT_PRODUCT_ORDERING_METHOD);
        }
        $lists['order_by'] = JHTML::_('select.genericlist', $order_data, 'order_by', 'class="inputbox" size="1" onChange="javascript:setSliderMinMax();" ' . $disabled . ' ', 'value', 'text', $order_by_select);

        /*************THIS FILE MUST LOAD AFTER MODEL CONSTUCTOR LOAD***************/
        $GLOBALS['product_price_slider'] = 0;
        if ($catid && count($loadCategorytemplate) > 0)
        {
            if (strstr($loadCategorytemplate[0]->template_desc, "{product_price_slider}"))
            {
                if (!JRequest::getVar('ajaxslide'))
                {
                    $loadCategorytemplate[0]->template_desc = str_replace("{show_all_products_in_category}", "<div id='oldredcatpagination'>{show_all_products_in_category}</div>", $loadCategorytemplate[0]->template_desc);
                    $loadCategorytemplate[0]->template_desc = str_replace("{pagination}", "<div id='oldredcatpagination'>{pagination}</div>", $loadCategorytemplate[0]->template_desc);
                }
                if (count($product) > 0)
                {
                    $GLOBALS['product_price_slider'] = 1;
                    // Start Code for fixes IE9 issue
                    //JHTML::Script('jquery-1.js', 'components/com_redshop/assets/js/',false);
                    //JHTML::Script('jquery-ui-1.js', 'components/com_redshop/assets/js/',false);
                    $document->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');
                    $document->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js');
                    // End Code for fixes IE9 issue
                    require_once (JPATH_COMPONENT_SITE . '/assets/js/catprice_filter.php');
                }
                else
                {
                    $loadCategorytemplate[0]->template_desc = str_replace("{product_price_slider}", "", $loadCategorytemplate[0]->template_desc);
                    $loadCategorytemplate[0]->template_desc = str_replace("{pagination}", "", $loadCategorytemplate[0]->template_desc);
                }
            }
            if (!count($product))
            {
                $loadCategorytemplate[0]->template_desc = str_replace("{order_by_lbl}", "", $loadCategorytemplate[0]->template_desc);
                $loadCategorytemplate[0]->template_desc = str_replace("{order_by}", "", $loadCategorytemplate[0]->template_desc);
                if (!$manufacturer_id)
                {
                    $loadCategorytemplate[0]->template_desc = str_replace("{filter_by_lbl}", "", $loadCategorytemplate[0]->template_desc);
                    $loadCategorytemplate[0]->template_desc = str_replace("{filter_by}", "", $loadCategorytemplate[0]->template_desc);
                }
            }
        }

        $this->assignRef('detail', $detail);
        $this->assignRef('lists', $lists);
        $this->assignRef('product', $product);
        $this->assignRef('pageheadingtag', $pageheadingtag);
        $this->assignRef('params', $params);
        $this->assignRef('catid', $catid);
        $this->assignRef('maincat', $maincat);
        $this->assignRef('category_template_id', $category_template_id);
        $this->assignRef('order_by_select', $order_by_select);
        $this->assignRef('manufacturer_id', $manufacturer_id);
        $this->assignRef('loadCategorytemplate', $loadCategorytemplate);

        parent::display($tpl);
    }
}

