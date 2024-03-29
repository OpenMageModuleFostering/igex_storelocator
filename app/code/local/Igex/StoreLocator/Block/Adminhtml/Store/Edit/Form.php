<?php
class Igex_StoreLocator_Block_Adminhtml_Store_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('store_form');
        $this->setTitle($this->__('Store Information'));
    }

    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('igex_storelocator');

        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data')
        );

        $fieldset = $form->addFieldset('igex_storelocator_form', array(
            'legend'    => Mage::helper('checkout')->__('Store Information')
        ));


        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', array(
                'name' => 'entity_id',
            ));
        }

        $fieldset->addField('created_time', 'hidden', array(
            'name' => 'created_time',
        ));

        $fieldset->addField('update_time', 'hidden', array(
            'name' => 'update_time',
        ));


        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('igex_storelocator')->__('Status'),
            'name'      => 'status',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('igex_storelocator')->__('Enabled'),
                ),

                array(
                    'value'     => 0,
                    'label'     => Mage::helper('igex_storelocator')->__('Disabled'),
                ),
            ),
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('igex_storelocator')->__('Store View'),
                'title'     => Mage::helper('igex_storelocator')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            
	$fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Store Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
        ));
		
			
		$fieldset->addField('topvisible', 'select', array(
            'label'     => 'Top Store Visible',
            'name'      => 'topvisible',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('igex_storelocator')->__('Enabled'),
                ),

                array(
                    'value'     => 0,
                    'label'     => Mage::helper('igex_storelocator')->__('Disabled'),
                ),
            ),
        ));

		
	$fieldset->addField('store_url', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Website'),
            'required'  => false,
            'name'      => 'store_url',
	));

	$fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Email'),
	    'class'     => 'validate-email',
            'required'  => false,
            'name'      => 'email',
        ));

	$fieldset->addField('phone', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Telephone'),
            'name'      => 'phone',
        ));


        $fieldset->addField('address', 'textarea', array(
            'label'     => Mage::helper('igex_storelocator')->__('Address'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'address',
        ));

        $fieldset->addField('zipcode', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Postal Code'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'zipcode',
        ));
		
	$fieldset->addField('state', 'text', array(
            'label'     => 'State',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'state',
        ));

        $fieldset->addField('city', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('City'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'city',
        ));


        $countryList = Mage::getModel('directory/country')->getCollection()->toOptionArray();
		
		

        $fieldset->addField('country_id', 'select', array(
            'label'     => Mage::helper('igex_storelocator')->__('Country'),
            'name'      => 'country_id',
            'title'     => 'country',
            'values'    => $countryList
        ));

	$fieldset->addField('lat', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Latitude'),
            'class'     => 'required-entry',
            'required'  => false,
            'name'      => 'lat',
        ));

	$fieldset->addField('long', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Longitude'),
            'class'     => 'required-entry',
            'required'  => false,
            'name'      => 'long',
        ));


        
        /*$fieldset->addField('fax', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Fax'),
            'name'      => 'fax',
        ));*/

        /*$fieldset->addField('description', 'textarea', array(
            'label'     => Mage::helper('igex_storelocator')->__('Description'),
            'name'      => 'description',
        ));

        $fieldset->addField('lat', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Latitude'),
            'required'  => true,
            'name'      => 'lat',
        ));

        $fieldset->addField('long', 'text', array(
            'label'     => Mage::helper('igex_storelocator')->__('Longitude'),
            'required'  => true,
            'name'      => 'long',
        ));*/

        $fieldset->addField('image', 'image', array(
            'label'     => Mage::helper('igex_storelocator')->__('Dealer Icon'),
            'required'  => false,
            'name'      => 'image',
        ));
        
        /*$fieldset->addField('marker', 'image', array(
                'label'     => Mage::helper('igex_storelocator')->__('Marker'),
                'required'  => false,
                'name'      => 'marker',
        ));*/
		
		
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
