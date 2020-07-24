<?php

class MageDoc_EnhancedPoll_Model_Observer
{
    public function core_abstract_save_before(Varien_Event_Observer $observer)
    {
        $poll = $observer->getDataObject();
        if ($poll instanceof Mage_Poll_Model_Poll){
            $request = Mage::app()->getFrontController()->getRequest();
            if ($type = $request->getPost('type')){
                $poll->setType($type);
                if ($type != MageDoc_EnhancedPoll_Model_Source_PollType::OPTIONS) {
                    foreach ($poll->getAnswers() as $answer) {
                        $answer->setId(0);
                        $answer->setAnswerTitle(time());
                    }
                    $request->setParam('deleteAnswer', null);
                }
            }
        }
    }
}