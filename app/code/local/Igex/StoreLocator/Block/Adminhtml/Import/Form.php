<?php

class Igex_StoreLocator_Block_Adminhtml_Import_Form extends Mage_Adminhtml_Block_Widget_Form 
{
	protected function _prepareForm() 
	{
		$sampleCSVpath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'import_stores/sample.csv';
		$form = new Varien_Data_Form(array(
				'id' => 'edit_form',
				'action' => $this->getUrl('*/adminhtml_import/save'),
				'method' => 'post',
				'enctype' => 'multipart/form-data'
			)
		);
        $fieldset = $form->addFieldset('edit_form', array('legend'=>Mage::helper('igex_storelocator')->__('Add dealers via CSV file')));
        $fieldset->addField('csv_file', 'file', array(
                'name'  => 'csv_file',
                'label' => Mage::helper('igex_storelocator')->__('Choose CSV file to import'),
                'after_element_html' => Mage::helper('igex_storelocator')->__('<br/> Download : <a href="%s">Sample CSV file</a>', $sampleCSVpath)
            )
        );

	$fieldset->addField('note', 'label', array(
          'value'     => Mage::helper('igex_storelocator')->__('Note : A CSV file may contain many stores. Please donot edit the first row of the CSV file, as this will not import the data properly.'),
        ));

		$form->setUseContainer(true);
		$this->setForm($form);
		return parent::_prepareForm();
	}
}
