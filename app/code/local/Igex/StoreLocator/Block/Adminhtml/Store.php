<?php
class Igex_StoreLocator_Block_Adminhtml_Store extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'igex_storelocator';
        $this->_controller = 'adminhtml_store';
        $this->_headerText = $this->__('Store');

        parent::__construct();
    }
}
