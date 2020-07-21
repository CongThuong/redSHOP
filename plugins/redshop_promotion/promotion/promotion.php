<?php
/**
 * @package     redSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Plugin Redshop_PromotionPromotion
 *
 * @since __DEPLOY_VERSION__
 */
class PlgRedshop_PromotionPromotion extends JPlugin
{
    protected $db;
    protected $app;
    protected $query;
    protected $form;

    /**
     * Constructor
     *
     * @param   object  $subject  The object to observe
     * @param   array   $config   An array that holds the plugin configuration
     *
     * @since    __DEPLOY_VERSION__
     */
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);

        $this->loadLanguage();
        $this->app = JFactory::getApplication();
        $this->db = JFactory::getDbo();
        $this->query = $this->db->getQuery(true);
        //echo __DIR__ . "/forms/promotion.xml"; die;
        $this->form = JForm::getInstance("promotion", __DIR__ . "/forms/promotion.xml", []);
        //$this->form->loadFile('promotion', false);
    }

    public function onSavePromotion() {

    }

    public function onLoadPromotion() {

    }

    public function onDeletePromotion() {

    }

    /**
     * @return string
     * @since __DEPLOY_VERSION__
     */
    public function onRenderBackEndLayout() {
        $layoutFile = JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name . '/layouts';
        $layout = new JLayoutFile('edit', $layoutFile);
        return $layout->render([$this]);
    }
}