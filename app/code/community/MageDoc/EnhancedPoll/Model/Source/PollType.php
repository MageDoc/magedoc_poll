<?php

class MageDoc_EnhancedPoll_Model_Source_PollType
{
    const OPTIONS = 'options';
    const INT = 'integer';
    const BOOL = 'bool';
    const TEXT = 'text';
    const DECIMAL = 'decimal';

    protected $_options = array();

    public function __construct()
    {
        $hlp = Mage::helper('magedoc_poll');
        $this->_options = array(
            self::OPTIONS => $hlp->__('Options'),
            self::INT => $hlp->__('Number'),
            self::BOOL => $hlp->__('Yes/No'),
            self::TEXT => $hlp->__('Text'),
            self::DECIMAL => $hlp->__('Decimal Number')
        );
    }

    public function getOptionArray()
    {
        $optionArray = array();
        foreach ($this->_options as $value => $label){
            $optionArray[] = array(
                'value' =>  $value,
                'label' => $label,
            );
        }
        return $optionArray;
    }

    public function getOptionHash()
    {
        return $this->_options;
    }

    /**
     * Get a text for option value
     *
     * @param  string|int $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        $options = $this->getOptionArray();
        // Fixed for tax_class_id and custom_design
        if (sizeof($options) > 0) foreach($options as $option) {
            if (isset($option['value']) && $option['value'] == $value) {
                return isset($option['label']) ? $option['label'] : $option['value'];
            }
        } // End
        if (isset($options[$value])) {
            return $options[$value];
        }
        return false;
    }
}