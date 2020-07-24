<?php

class MageDoc_EnhancedPoll_Block_Adminhtml_Poll_Edit_Tab_Form extends Mage_Adminhtml_Block_Poll_Edit_Tab_Form
{
    protected function _prepareForm()
    {
        $pollData = Mage::getSingleton('adminhtml/session')->getPollData();

        parent::_prepareForm();
        /**
         * @var $form Varien_Data_Form
         */
        $form = $this->getForm();

        $fieldset = $form->getElement('poll_form');

        $fieldset->addField('type', 'select', array(
            'label'     => Mage::helper('poll')->__('Type'),
            'name'      => 'type',
            'values'    => Mage::getSingleton('magedoc_poll/source_pollType')
                ->getOptionArray(),
        ));

        if( $pollData ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getPollData());
        } elseif( Mage::registry('poll_data') ) {
            $form->setValues(Mage::registry('poll_data')->getData());
        }
    }
}