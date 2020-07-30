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
    protected $table;
    protected $form;
    protected $layoutFolder;

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
        $this->table = '#__redshop_promotion';
        $this->layoutFolder = JPATH_PLUGINS . '/' . $this->_type . '/' . $this->_name . '/layouts';
        $this->form = JForm::getInstance("promotions", __DIR__ . "/forms/promotion.xml", []);
    }

    /**
     * @param $data
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public function onSavePromotion($data) {
        $result = $data['jform'] ?? [];

        if (!empty($result)) {
            unset($data['jform']);
            $data = base64_encode(json_encode($data));
            $result['data'] = $data;
        }

        return $result;
    }

    /**
     * @param $id
     * @return mixed|null
     * @since __DEPLOY_VERSION__
     */
    public function onLoadPromotion($id) {
        $this->query->clear()
            ->select('*')
            ->from($this->db->qn($this->table))
            ->where($this->db->qn('id') . ' = ' . $this->db->q($id));
        return $this->db->setQuery($this->query)->loadAssoc();
    }

    public function onDeletePromotion() {

    }

    /**
     * @param $cart
     * @param bool $saveSession
     * @return mixed
     * @since __DEPLOY_VERSION__
     */
    public function onProcessPromotion($cart, $saveSession = false) {
        return $cart;
    }

    /**
     * @return string
     * @since __DEPLOY_VERSION__
     */
    public function onRenderBackEndLayoutConditions() {
        $post = $this->prepareData();
        $promotionId = JFactory::getApplication()->input->getInt('id', 0);
        $layout = new JLayoutFile('conditions', $this->layoutFolder);

        return $layout->render(['form' => $this->form, 'post' => $post, 'promotionId' => $promotionId]);
    }

    /**
     * @return string
     * @since __DEPLOY_VERSION__
     */
    public function onRenderBackEndLayoutAwards() {
        $post = $this->prepareData();
        $promotionId = JFactory::getApplication()->input->getInt('id', 0);
        $layout = new JLayoutFile('awards', $this->layoutFolder);

        return $layout->render(['form' => $this->form, 'post' => $post, 'promotionId' => $promotionId]);
    }

    /**
     * @return array
     * @throws Exception
     * @since __DEPLOY_VERSION__
     */
    protected function prepareData() {
        $post = JFactory::getApplication()->input->post->getArray();
        $promotionId = JFactory::getApplication()->input->getInt('id', 0);
        $promotion = $this->onLoadPromotion($promotionId);
        $data = [];

        if (!empty($promotion['data'])) {
            try {
                $data = json_decode(base64_decode($promotion['data']), true);
            } catch (Exception $e) {
                $data = [];
            }
        }

        return array_merge($post, $data);
    }

    /**
     * @param $cart
     * @since __DEPLOY_VESION__
     */
    public function onApply(&$cart) {
        $promotions = $this->loadPromotions($cart);
        $cart['promotions'] = $cart['promotions']?? [];
        $cart['promotions'] = array_merge($cart['promotions'], $promotions);
    }

    /**
     * @param $cart
     * @return array|mixed
     * @since  __DEPLOY_VERSION__
     */
    protected function loadPromotions(&$cart){
        $this->query->clear()
            ->select('*')
            ->from($this->db->qn($this->table))
            ->where($this->db->qn('published') . ' = ' . $this->db->q('1'))
            ->where($this->db->qn('type') . ' = ' . $this->db->q('promotion'));

        $result =  $this->db->setQuery($this->query)->loadObjectList();

        for($i = 0; $i < count($result); $i++) {
            $result[$i]->data = base64_decode($result[$i]->data);
            $result[$i]->data = json_decode($result[$i]->data);
            $result[$i]->isApplied = false;

            //TODO: check apply Promotion here
            $this->checkAndApplyPromotion($result[$i], $cart);
        }

        return $result;
    }

    /**
     * @param $promotion
     * @param $cart
     * @since __DEPLOY_VERSION__
     */
    protected function checkAndApplyPromotion(&$promotion, &$cart){
        $result = false;

        /*if (empty($promotion)
            || empty($cart)
            || empty($cart['idx'])
            || $cart['idx'] == 0) {
            return $result;
        }*/

        $data = $promotion->data ?? new \stdClass();
        $data->promotion_type = $data->promotion_type ?? '';

        switch($data->promotion_type) {
            case 'amount_product':
                break;
            case 'volume_order':
                $conditionOrderVolume = $this->getConditionOrderVolume($data);
                if ($conditionOrderVolume > 0) {
                    $operand = '<=';

                    switch (trim($operand)) {
                        case '>=':
                        default:
                            if ($conditionOrderVolume <= $this->getCartSubTotalExcludeVAT($cart)) {
                                $promotion->isApplied = true;
                                $idx = $cart['idx'] = $cart['idx']++;
                                $productAwardId = $data->product_award ?? 0;
                                $productAwardAmount = $data->award_amount ?? 0;
                                $productAwardEntity = \RedshopEntityProduct::getInstance($productAwardId);
                                $productCategory = $productAwardEntity->getCategories();
                                $categoryEntity = $productCategory->getAll();
                                $category = array_keys($categoryEntity);

                                $award = [
                                    'hidden_attribute_cartimage' => '',
                                    'product_price_excl_vat' => 0.0,
                                    'subscription_id' => 0,
                                    'product_vat' => 0,
                                    'giftcard_id' => '',
                                    'product_id' => $productAwardId,
                                    'discount_calc_output' => '',
                                    'discount_calc' => [],
                                    'product_price' => 0.0,
                                    'product_old_price' => 0.0,
                                    'product_old_price_excl_vat' => 0.0,
                                    'cart_attribute' => [],
                                    'cart_accessory' => [],
                                    'quantity' => $productAwardAmount,
                                    'category_id' => $category[0],
                                    'wrapper_id' => 0,
                                    'wrapper_price' => 0.0,
                                    'isPromotionAward' => true
                                ];

                                $cart[$idx] = $award;
                            }
                            break;
                    }
                }

                break;
            default:
                break;
        }
    }

    /**
     * @param $promotion
     * @return |null
     * @since  __DEPLOY_VERSION__
     */
    protected function getConditionOrderVolume(&$promotion) {
        return $promotion->condition_order_volume ?? null;
    }


    /**
     * @param $cart
     * @return float
     * @since  __DEPLOY_VERSION__
     */
    protected function getCartSubTotalExcludeVAT(&$cart) {
        return $cart['product_subtotal_excl_vat'] ?? 0.0;
    }
}