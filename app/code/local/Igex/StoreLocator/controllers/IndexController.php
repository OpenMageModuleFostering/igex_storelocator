<?php
class Igex_StoreLocator_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {		 	
        $this->loadLayout();
		$this->getLayout()->getBlock('head')->setTitle('Store Finder');
        $this->renderLayout();
    }
	
	public function searchAction()
    {		 	
        $this->loadLayout();
		$this->getLayout()->getBlock('head')->setTitle('Store Finder');
        $this->renderLayout();
    }
	
	public function detailAction()
    {	
	//echo 'call';exit;	 	
		$getData = $this->getRequest()->getParam('id');
		if($getData=='' || empty($getData)){ $this->_redirect('*/'); }
		$store = Mage::getModel('igex_storelocator/store')->load($getData);
        $this->loadLayout();
		
		$this->getLayout()->getBlock("head")->setTitle($store->getName());
	    $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        
        $breadcrumbs->addCrumb($store->getName(), array(
                "label" => $this->__($store->getName()),
                "title" => $this->__($store->getName())
		   ));
        $this->renderLayout();
    }
}
