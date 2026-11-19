<?php
/**
 * Applies MoIP checkout path rewrite after Magento URL rewrites, only when
 * onestepcheckout/config/enabled is true for the current store/website.
 */
class MOIP_Onestepcheckout_Model_Url_Rewrite_Request extends Mage_Core_Model_Url_Rewrite_Request
{
    public function rewrite()
    {
        $result = parent::rewrite();
        $this->_applyMoipCheckoutRewrite();
        return $result;
    }

    protected function _applyMoipCheckoutRewrite()
    {
        if (!Mage::isInstalled() || !Mage::getStoreConfigFlag('onestepcheckout/config/enabled')) {
            return;
        }

        $path = $this->_request->getPathInfo();

        $rewritten = preg_replace('#^(/)?checkout/onepage#', '$1onestepcheckout/index', $path, 1, $count);
        if ($count) {
            $this->_request->rewritePathInfo($rewritten);
            return;
        }

        $rewritten = preg_replace('#^(/)?sales/order/reorder#', '$1onestepcheckout/order/reorder', $path, 1, $count);
        if ($count) {
            $this->_request->rewritePathInfo($rewritten);
        }
    }
}
