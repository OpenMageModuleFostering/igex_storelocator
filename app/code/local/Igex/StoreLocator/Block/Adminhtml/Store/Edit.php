<?php
class Igex_StoreLocator_Block_Adminhtml_Store_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Init class
     */
    public function __construct()
    {
        $this->_blockGroup = 'igex_storelocator';

        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_store';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('igex_storelocator')->__('Save Store'));
        $this->_updateButton('delete', 'label', Mage::helper('igex_storelocator')->__('Delete Store'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('igex_storelocator')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $apiUrl = Mage::getStoreConfig('igex/storelocator_options/apiurl');
        $apiKey = Mage::getStoreConfig('igex/storelocator_options/apikey');
        $apiSensor = Mage::getStoreConfig('igex/storelocator_options/apisensor');
        $sensor = ($apiSensor == 0) ? 'false' : 'true';
        $img="";
        $marker = "var marker = new google.maps.Marker({position: latLng, map: map });";
        if(!is_null(Mage::getStoreConfig('igex/storelocator_options/mapicon')) && Mage::getStoreConfig('igex/storelocator_options/mapicon') != '') {
            $img = "var imgMarker =  new google.maps.MarkerImage('".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'storelocator/markers/'.Mage::getStoreConfig('igex/storelocator_options/mapicon')."');";
            $marker = "var marker = new google.maps.Marker({position: latLng, icon: imgMarker,map: map });";
        }

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function capitalize(str) {
                var pieces = str.split(' ');
                for ( var i = 0; i < pieces.length; i++ )
                {
                    var j = pieces[i].charAt(0).toUpperCase();
                    pieces[i] = j + pieces[i].substr(1);
                }
                return pieces.join(' ');
            }
        ";
    }

    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('igex_storelocator')->getId()) {
            return $this->__('Edit Store');
        }
        else {
            return $this->__('New Store');
        }
    }
}
