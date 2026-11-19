<?php
class MOIP_Onestepcheckout_Model_Observer
{
    public function model_config_data_save_before($ovserver)
    {
    }

    public function checkout_cart_add_product_complete($ovserver)
    {
        if (Mage::getStoreConfig('onestepcheckout/config/disable_shop_cart')) {
            Mage::app()->getResponse()->setRedirect(Mage::getUrl('onestepcheckout/index'));
            Mage::app()->getResponse()->sendResponse();
            exit;
        }
    }

    /**
     * Store/website-scoped replacement for the old global /checkout/onepage rewrite.
     */
    public function redirectToMoipCheckoutIfEnabled(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfigFlag('onestepcheckout/config/enabled')) {
            return;
        }
        $controller = $observer->getControllerAction();
        if (!$controller || $controller->getRequest()->isAjax()) {
            return;
        }
        $controller->getResponse()
            ->setRedirect(Mage::getUrl('onestepcheckout/index'))
            ->sendResponse();
        $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
    }

    public function redirectReorderToMoipCheckoutIfEnabled(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfigFlag('onestepcheckout/config/enabled')) {
            return;
        }
        $controller = $observer->getControllerAction();
        if (!$controller) {
            return;
        }
        $controller->getResponse()
            ->setRedirect(Mage::getUrl('onestepcheckout/order/reorder', array(
                '_query' => $controller->getRequest()->getParams(),
            )))
            ->sendResponse();
        $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
    }
}
