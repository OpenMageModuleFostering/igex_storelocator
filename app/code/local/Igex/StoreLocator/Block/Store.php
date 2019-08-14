<?php

class Igex_StoreLocator_Block_Store extends Mage_Core_Block_Template 
{

	protected function _construct() 
	{
		parent::_construct();
		$pageType = Mage::helper('igex_storelocator')->getPageType();
		if ($pageType == 2) 
		{
			$this->setTemplate('igex/storelocator/2columns.phtml');
		}
		else 
		{
			$this->setTemplate('igex/storelocator/storelocator.phtml');
		}

		$collection = $this->getStores();
	        $this->setCollection($collection);
	}

	public function _prepareLayout() 
	{		
		parent::_prepareLayout();
		$pager = $this->getLayout()->createBlock('page/html_pager', 'dealer.pager');
//		$pager->setAvailableLimit(array(10 => 10, 20 => 20, 30 => 30));
		$pager->setCollection($this->getCollection());
		$this->setChild('pager', $pager);
		return $this;

	}

	public function getDefaultMarker()
	{
        	$defaultMarker = '';
    
        	if(!is_null(Mage::getStoreConfig('igex/storelocator_options/mapicon')) && Mage::getStoreConfig('igex/storelocator_options/mapicon') != '')
		{
        		$defaultMarker = 'storelocator/markers/'.Mage::getStoreConfig('igex/storelocator_options/mapicon');
           	}

       		return $defaultMarker;
	}

	public function getStores()
	{
	        $search_add = Mage::getStoreConfig('igex/search_options/search_by_address'); 
		$search_state = Mage::getStoreConfig('igex/search_options/search_by_state'); 
		$search_zip = Mage::getStoreConfig('igex/search_options/search_by_zipcode'); 
		$search_rad = Mage::getStoreConfig('igex/search_options/search_by_radius'); 
	        $show_state = Mage::getStoreConfig('igex/search_options/state_search'); 

		//If state search is enabled, return stores
		$state_req = Mage::app()->getRequest()->getParam('state');
		if($state_req!='' && $show_state==1)
		{	
			$stores = Mage::getModel('igex_storelocator/store')->getCollection()->addFieldToFilter('status',1)
				->addFieldToFilter('state',$state_req);

			return $stores;
		}
		
		$postData = Mage::app()->getRequest()->getPost();

		//Getting full collection of active stores
		$stores = Mage::getModel('igex_storelocator/store')->getCollection()->addFieldToFilter('status',1);

		//Filtering by address
		if($postData['address']!='' && $search_add==1)
		{
			$stores = Mage::getModel('igex_storelocator/store')->getCollection()->addFieldToFilter('status',1)
				->addFieldToFilter('address',array('like'=>'%'.$postData['address'].'%'));
				
		}
	
		//Filtering by state
		if($postData['state_or_stub']!='' && $search_zip==1)
     		{   

			$stores = Mage::getModel('igex_storelocator/store')->getCollection()->addFieldToFilter('status',1)
				->addFieldToFilter(array('city','state'),array(array('like'=>'%'.$postData['state_or_stub'].'%'),array('like'=>'%'.$postData['state_or_stub'].'%')));	
			
		}

		//Filtering by zipcode
		if($postData['zipcode']!='' && $search_zip==1)
		{
			$stores = Mage::getModel('igex_storelocator/store')->getCollection()->addFieldToFilter('status',1)
				->addFieldToFilter('zipcode',$postData['zipcode']);
		}

		//Filtering by radius
		if($postData['zipcode']!='' && $postData['radius']!='' && $search_rad==1)
		{
			
			$stores = Mage::getModel('igex_storelocator/store')->getCollection()->addFieldToFilter('status',1);
			$kilometer = $postData['radius'];

			$units = Mage::helper('igex_storelocator')->getRadiusUnit();			
            
			// Finding lat-long from user-entered zipcode
			if(strlen($postData['zipcode'])<=4)
			{
				$zipcode=$postData['zipcode'].",australia";
			}
			else
			{
				$zipcode=trim(str_replace(' ','',$postData['zipcode']));
			}
			$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$zipcode."&sensor=false";
			$details = file_get_contents($url);
			$result = json_decode($details,true);
			$lat=$result['results'][0]['geometry']['location']['lat'];  //from user
			$lng=$result['results'][0]['geometry']['location']['lng'];

			//Fetching values from db
 			$resource = Mage::getSingleton('core/resource');
     
       			// Retrieve the read connection 
 		        $readConnection = $resource->getConnection('core_read');
     
   			$query ="SELECT `entity_id`, `lat`, `long`  FROM `igex_storelocator`";
     
       			// Execute the query and store the results in $results 
		        $results = $readConnection->fetchAll($query);
			$data = array();
			$lati = array();
			$longi = array();
			$final= array();
			
        	  	for($j=0;$j<count($results);$j++)
		  	{
				$lati[] = $results[$j]['lat'];
				$longi[] = $results[$j]['long'];
		   	}
	
		 	 if(!function_exists('distance'))			
		 	 {
				function distance($lat1, $lng1, $lat2, $lng2,$unit)
		         	{
					$pi80 = M_PI / 180;
					$lat1 *= $pi80;
					$lng1 *= $pi80;
					$lat2 *= $pi80;
					$lng2 *= $pi80;
			
					$r = 6372.797; // mean radius of Earth in km
					$dlat = $lat2 - $lat1;
					$dlng = $lng2 - $lng1;
					$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
					$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
					$km = $r * $c;
					if($unit==1)
					{
						return $km;
					}
					
					return ($km/1.609344);
		        	}
		   	}
		
	  	   	 $dist = array();

			//Finding distance	
	  	  	for($i=0;$i<count($lati);$i++)
	  	  	{
				$dist[]=distance($lati[$i],$longi[$i],$lat,$lng,$units);
	  	  	} 		
	
			//Getting nearby stores
		  	for($k=0;$k<count($dist);$k++)
		  	{
				if($dist[$k]<$kilometer)
				{
        	 			$final[]=$results[$k]['entity_id'];
				}
		  	 }

        	    	$stores = $stores->addFieldToFilter('entity_id',array('in'=> $final));
			$stores->getSelect();
	     	}

			$storesCollection = new Varien_Data_Collection();
			
		foreach($stores as $store)
		{
	        	if(!is_null($store->getCountryId()))
	        	{
	            		$store->setCountryId($this->getCountryByCode($store->getCountryId()));
	            	}
		       else
		       {
	               		$store->setCountryId($this->__('AU'));
	               }
	
	               if(!is_null($store->getImage()) || $store->getImage() != '' && $show_img==1)
		       {
	               		$imgUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$store->getImage();
	               }
		       elseif (!is_null(Mage::getStoreConfig('igex/storelocator_options/defaultimage')) && Mage::getStoreConfig('igex/storelocator_options/defaultimage') != '')
		       {
	                	$imgUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'storelocator/images/'.Mage::getStoreConfig('igex/storelocator_options/defaultimage');
	               }
		       else
		       {
	               		$imgUrl = $this->getLogoSrc();
	               }
	               $store->setImage($imgUrl);
			
	               $storesCollection->addItem($store);

		}
			return $storesCollection;
	}

	public function getPagerHtml() 
	{
		return $this->getChildHtml('pager');
	}

	public function getGoogleApiUrl()
	{
	        $apiUrl = Mage::getStoreConfig('igex/storelocator_options/apiurl');
	        if(is_null($apiUrl))
	            $apiUrl = "http://maps.googleapis.com/maps/api/js?v=3";
	        $apiKey = "&key=".Mage::getStoreConfig('igex/storelocator_options/apikey');
	        $apiSensor = Mage::getStoreConfig('igex/storelocator_options/apisensor');
	        $sensor = ($apiSensor == 0) ? 'false' : 'true';
	        $urlGoogleApi = $apiUrl."&sensor=".$sensor.$apiKey."&callback=initialize&libraries=places";
        
	        return $urlGoogleApi;
	}

	public function getCountryByCode($code)
	{
		return Mage::getModel('directory/country')->loadByCode($code)->getName();
	}

	public function getLogoSrc()
	{
	        if (empty($this->_data['logo_src'])) 
		{
	            $this->_data['logo_src'] = Mage::getStoreConfig('design/header/logo_src');
	        }
	        return $this->getSkinUrl($this->_data['logo_src']);
	}

}
