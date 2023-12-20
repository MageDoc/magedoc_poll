<?php

class MageDoc_EnhancedPoll_Model_Resource_Poll_Vote_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize collection
     *
     */
    public function _construct()
    {
        $this->_init('poll/poll_vote');
    }
}
