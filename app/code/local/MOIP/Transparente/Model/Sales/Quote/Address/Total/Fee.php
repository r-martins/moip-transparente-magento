<?php
class MOIP_Transparente_Model_Sales_Quote_Address_Total_Fee extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function __construct()
    {
        $this->setCode('fee_moip');
    }
    public function getLabel()
    {
        return Mage::helper('transparente')->__('Juros de parcelamento');
    }

     public function getLabelDiscount()
    {
        return Mage::helper('transparente')->__('Desconto na compra à vista');
    }
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this;
        }


        // Upstream bug: used the code string 'fee_moip' as amount (PHP 8 TypeError on int+string).
        $amount = (float) $address->getFeeMoip();
        $baseAmount = (float) $address->getBaseFeeMoip();

        if ($amount) {
            $this->_addAmount($amount);
            $this->_addBaseAmount($baseAmount ?: $amount);
        }

        return $this;
    }
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amt = $address->getFeeMoip();
        $baseamt = $address->getBaseFeeMoip();
        if ($amt > 0) {
            $address->addTotal(array(
                    'code'=>$this->getCode(),
                    'title'=>$this->getLabel(),
                    'value'=> $amt
            ));
        } elseif($amt < 0) {
             $address->addTotal(array(
                    'code'=>$this->getCode(),
                    'title'=>$this->getLabelDiscount(),
                    'value'=> $amt
            ));
        }
        return $this;
    }
}
