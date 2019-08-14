<?php

class Igex_StoreLocator_Helper_Data extends Mage_Core_Helper_Abstract 
{

	public function getJsonData($address) 
	{
		$storeId = Mage::app()->getStore()->getId();
		$configUrl = Mage::getStoreConfig('igex/storelocator_options/apiurl', $storeId);
		if ($configUrl != '') 
		{
			$url = Mage::getStoreConfig('igex/storelocator_options/apiurl')."?address=$address&sensor=false";
		}
		else 
		{
			$url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&sensor=false';
		}
		
		$rCURL = curl_init();
		curl_setopt($rCURL, CURLOPT_URL, $url);
		curl_setopt($rCURL, CURLOPT_HEADER, 0);
		curl_setopt($rCURL, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		curl_setopt($rCURL, CURLOPT_RETURNTRANSFER, 1);
		$aData = curl_exec($rCURL);
		curl_close($rCURL);
		$json = json_decode($aData);
		return $json;
	}

	public function getSearchLabel($radius) 
	{
		$unit = $this->getRadiusUnit();
		$searchLabel = '';
		if ($unit == 1) 
		{
			$searchLabel = $radius . ' Kilometers'; 
		}
		else 
		{
			$searchLabel = $radius . ' Miles';
		}
		return $searchLabel;
	}

	public function getSearchRadius() 
	{
		$storeId = Mage::app()->getStore()->getId();
		return Mage::getStoreConfig('igex/search_options/default_search_radius', $storeId);
	}

	public function getSearchRadiusOptions() 
	{
		$storeId = Mage::app()->getStore()->getId();
		$radius = Mage::getStoreConfig('igex/search_options/search_radius', $storeId);
		$options = array();
		if ($radius != '') 
		{
			$radius_array = explode(',', $radius);;
			if (count($radius_array)) 
			{
				foreach ($radius_array as $item) 
				{
					$options[] = array(
						'value' => trim($item),
						'label'	=> $this->getSearchLabel(trim($item)));
				}
			}
		}
		return $options;
	}

	public function getRadiusUnit() 
	{
		$storeId = Mage::app()->getStore()->getId();
		return Mage::getStoreConfig('igex/search_options/distance_units', $storeId);
	}

	public function getPageType() 
	{
		$storeId = Mage::app()->getStore()->getId();
		return (int) Mage::getStoreConfig('igex/storelocator_options/page_type', $storeId);
	}
}
