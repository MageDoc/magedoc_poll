<?php

class MageDoc_EnhancedPoll_Model_Resource_Poll_Vote extends Mage_Poll_Model_Resource_Poll_Vote
{
    /**
     * Initialize vote resource
     *
     */
    protected function _construct()
    {
        $this->_init('poll/poll_vote', 'vote_id');
    }

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable());
        if (!is_array($field)){
            $field = array($field);
        }
        foreach ($field as $key => $_field){
            $_field  = $this->_getReadAdapter()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $_field));
            $select->where($_field . '=?', is_array($value) ? $value[$key] : $value);
        }

        return $select;
    }
}