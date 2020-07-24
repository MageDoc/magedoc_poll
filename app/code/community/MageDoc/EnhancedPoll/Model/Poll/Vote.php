<?php

class MageDoc_EnhancedPoll_Model_Poll_Vote extends Mage_Poll_Model_Poll_Vote
{
    protected function _construct()
    {
        $this->_init('magedoc_poll/poll_vote');
    }
}