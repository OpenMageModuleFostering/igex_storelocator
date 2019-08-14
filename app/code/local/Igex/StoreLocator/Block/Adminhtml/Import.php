<?php

class Igex_StoreLocator_Block_Adminhtml_Import extends Mage_Adminhtml_Block_Widget_Form_Container 
{
	public function __construct() {
		parent::__construct();
		$this->_updateButton('save', 'label', Mage::helper('igex_storelocator')->__('Import'));
		$this->_removeButton('delete');
        	$this->_removeButton('back');
		
		$this->_blockGroup = 'igex_storelocator';
	        $this->_controller = 'adminhtml';
		$this->_mode = 'import';
	}
	
	public function getHeaderText() {
		return Mage::helper('igex_storelocator')->__('Import Stores');
	}
}
