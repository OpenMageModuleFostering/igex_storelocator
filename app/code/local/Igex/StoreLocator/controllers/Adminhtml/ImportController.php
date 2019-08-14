    <?php

class Igex_StoreLocator_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action 
{
	public function indexAction()
	{ 
		$this->loadLayout()->renderLayout();
	}

	public function saveAction() 
	{
		if ($data = $this->getRequest()->getPost()) {
			if(isset($_FILES['csv_file']['name']) && $_FILES['csv_file']['name'] != '') {
				try {
					$uploader = new Varien_File_Uploader('csv_file');
	           			$uploader->setAllowedExtensions(array('csv'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$path = Mage::getBaseDir('media').DS.'dealers'. DS ;
					$uploader->save($path, $_FILES['csv_file']['name'] );
					$filepath = $path.$_FILES['csv_file']['name'];
					$handler = new Varien_File_Csv();
					$importData = $handler->getData($filepath);
					$keys = $importData[0];
					foreach($keys as $key=>$value)
					{
						$keys[$key] = str_replace(' ', '_', strtolower($value));
					}
					$count = count($importData);
					$model = Mage::getModel('igex_storelocator/store');
					while(--$count>0) {
						$currentData = $importData[$count];
						$data = array_combine($keys, $currentData);
						array_shift($data);
						if((!$data['long'] || !$data['lat']) && ($data['address'] || $data['zipcode'])) {
							if($data['address']) {
								$address = urlencode($data['address']);
							} else {
								$address = urlencode($data['zipcode']);
							}
								$json = Mage::helper('igex_storelocator')->getJsonData($address);							
							$data['lat'] = strval($json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'});
							$data['long'] = strval($json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'});
						}
						// set default store & status
						$data['stores'] = 0;
						$data['status'] = 1;
						$model->setData($data)->save();
					}
					
				} catch (Exception $e) {
					//do nothing here
		    		}
			}
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('igex_storelocator')->__('Data imported Successfully.'));
			$this->_redirect('*/adminhtml_store/index');
		}
	}
}
