<?php
class MOIP_Onestepcheckout_Model_Observer
{
    public function model_config_data_save_before($ovserver)
    {
    }

    public function checkout_cart_add_product_complete($ovserver)
    {
        if (Mage::getStoreConfig('onestepcheckout/config/disable_shop_cart')) {
            Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/onepage'));
            Mage::app()->getResponse()->sendResponse();
            exit;
        }
    }

    /**
     * Store/website-scoped replacement for upstream's unconditional global/rewrite.
     * Uses rewritePathInfo (not HTTP redirect) so Magento keeps layout handles like
     * checkout_onepage_cadastro while dispatching MOIP_Onestepcheckout_IndexController.
     *
     * @param Varien_Event_Observer $observer
     */
    public function applyMoipCheckoutPathRewrite(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfigFlag('onestepcheckout/config/enabled')) {
            return;
        }

        /** @var Mage_Core_Controller_Request_Http $request */
        $request = Mage::app()->getRequest();
        $path = $request->getPathInfo();

        $rewritten = preg_replace('#^(/)?checkout/onepage#', '$1onestepcheckout/index', $path, 1, $count);
        if ($count) {
            $request->rewritePathInfo($rewritten);
            return;
        }

        $rewritten = preg_replace('#^(/)?sales/order/reorder#', '$1onestepcheckout/order/reorder', $path, 1, $count);
        if ($count) {
            $request->rewritePathInfo($rewritten);
        }
    }
}
