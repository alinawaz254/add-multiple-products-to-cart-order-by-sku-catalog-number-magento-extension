<?php
/**
 * Order By Catalog Number Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to ali.nawaz@cooperativecomputing.com so we can send you a copy immediately.
 *
 * @category    Cooperative Computing
 * @package     CooperativeComputing_AddMultipleProducts
 * @author      Ali Nawaz <ali.nawaz@cooperativecomputing.com>
 * @copyright   Copyright 2015 Cooperative Computing (http://www.cooperativecomputing.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class CooperativeComputing_AddMultipleProducts_AddController extends Mage_Core_Controller_Front_Action {
 
    public function indexAction() {
        $product_ids        = $this->getRequest()->getParam('product_ids');
        $product_quantities = $this->getRequest()->getParam('product_quantities');

        $cart = Mage::getModel('checkout/cart');
        $cart->init();
        /* @var $pModel Mage_Catalog_Model_Product */
        foreach ($product_ids as $key => $product_id) {
            if ($product_id == '') {
                continue;
            }
            $pModel = Mage::getModel('catalog/product')->load($product_id);
            if ($pModel->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE) {
                try {
                    $qty = isset($product_quantities[$key]) && $product_quantities[$key] > 0 ? $product_quantities[$key] : 1;
                    $cart->addProduct($pModel, array('qty' => $qty));
                }
                catch (Exception $e) {
                    continue;
                }
            }
        }
        $cart->save();
        if ($this->getRequest()->isXmlHttpRequest()) {
            exit('1');
        }
        $this->_redirect('checkout/cart');
    }

}
