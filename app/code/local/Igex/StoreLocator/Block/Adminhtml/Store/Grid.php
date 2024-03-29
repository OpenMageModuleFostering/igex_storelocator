<?php
class Igex_StoreLocator_Block_Adminhtml_Store_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        // Set some defaults for our grid

        $this->setDefaultSort('entity_id');
        $this->setId('StoreLocatorGrid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);

    }

    protected function _getCollectionClass()
    {
        return 'igex_storelocator/store_collection';
    }

    protected function _prepareCollection()
    {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->setFirstStoreFlag(true);
        //$collection = Mage::getModel('igex_storelocator/store')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        // Add the columns that should appear in the grid
        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'align' =>'right',
                'index' => 'entity_id'
            )
        );
		
		
        $this->addColumn('image', array(
            'header'    => Mage::helper('igex_storelocator')->__('Dealer Icon'),
            'width'     => '100',
            'renderer'  => 'igex_storelocator/adminhtml_store_renderer_image',
            'index'     => 'image',
        ));

        $this->addColumn('name',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('Store Name'),
                'index' => 'name',
            )
        );
		
		$this->addColumn('topvisible',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('Top Store Visible'),
                'index'     => 'topvisible',
                'type'      => 'options',
                'options'   => array(
                    0 => Mage::helper('igex_storelocator')->__('Disabled'),
                    1 => Mage::helper('igex_storelocator')->__('Enabled'),
                ),
            )
        );

	$this->addColumn('phone',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('Telephone'),
                'index' => 'phone',
            )
        );
		
		
	$this->addColumn('email',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('Email'),
                'index' => 'email',
            )
        );
		
        $this->addColumn('address',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('Address'),
                'index' => 'address',
            )
        );


	$this->addColumn('state',
            array(
                'header'=> 'State',
                'index' => 'state',
            )
        );

        $this->addColumn('city',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('City'),
                'index' => 'city',
            )
        );

	$this->addColumn('zipcode',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('Postal Code'),
                'index' => 'zipcode',
            )
        );
		
	

        /*$this->addColumn('country_id', array(
            'header'    => Mage::helper('customer')->__('Country'),
            'width'     => '100',
            'type'      => 'country',
            'index'     => 'country_id',
        ));

        

        $this->addColumn('fax',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('Fax'),
                'index' => 'fax',
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('igex_storelocator')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => false,
                'store_view'    => false,
                'sortable'      => true,
                'filter_condition_callback'
                => array($this, '_filterStoreCondition'),
            ));
        }*/

        $this->addColumn('status',
            array(
                'header'=> Mage::helper('igex_storelocator')->__('Status'),
                'index'     => Mage::helper('igex_storelocator')->__('status'),
                'type'      => 'options',
                'options'   => array(
                    0 => Mage::helper('igex_storelocator')->__('Disabled'),
                    1 => Mage::helper('igex_storelocator')->__('Enabled'),
                ),
            )
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('igex_storelocator')->__('CSV'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('igex_storelocator');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('igex_storelocator')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('igex_storelocator')->__('Are you sure?')
        ));

        $statuses = array(1 => Mage::helper('igex_storelocator')->__('Enabled'), 0 => Mage::helper('igex_storelocator')->__('Disabled'));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('igex_storelocator')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('igex_storelocator')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        // This is where our row data will link to
        return $this->getUrl('*/*/edit', array('entity_id' => $row->getId()));
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

}
