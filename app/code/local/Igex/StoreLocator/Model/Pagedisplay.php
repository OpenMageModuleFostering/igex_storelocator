<?php

class Igex_StoreLocator_Model_Pagedisplay
{
	public function toOptionArray()
	{
	        return array(
	            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('1 column')),
	            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('2 column')),
        );
	}

	public function toArray()
	{
	        return array(
		1 => Mage::helper('adminhtml')->__('1 column'),
            	2 => Mage::helper('adminhtml')->__('2 column'),
        );
	}
}
