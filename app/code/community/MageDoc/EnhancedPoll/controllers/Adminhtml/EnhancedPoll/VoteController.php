<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Poll
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Poll vote controller
 *
 * @file        Vote.php
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class MageDoc_EnhancedPoll_Adminhtml_EnhancedPoll_VoteController extends Mage_Adminhtml_Controller_Action
{
    protected $_publicActions = array('add');

    /**
     * Add Vote to Poll
     *
     * @return void
     */
    public function addAction()
    {
        $pollId     = intval($this->getRequest()->getParam('poll_id'));
        $answerId   = $this->getRequest()->getParam('vote');
        $customerId = intval($this->getRequest()->getParam('customer_id'));
        $storeId = intval($this->getRequest()->getParam('store_id'));

        /** @var $poll Mage_Poll_Model_Poll */
        $poll = Mage::getModel('poll/poll')->load($pollId);

        /**
         * Check poll data
         */
        if ($poll->getId() && !$poll->getClosed() && $customerId) {
            $pollType = $poll->getType();
            $answerField = 'poll_answer_id';
            switch ($pollType) {
                case MageDoc_EnhancedPoll_Model_Source_PollType::INT:
                case MageDoc_EnhancedPoll_Model_Source_PollType::BOOL:
                    $answerField = 'int_value';
                    $answerId = strlen($answerId)
                        ? (int)$answerId
                        : null;
                    break;
                case MageDoc_EnhancedPoll_Model_Source_PollType::TEXT:
                    $answerField = 'text_value';
                    break;
                case MageDoc_EnhancedPoll_Model_Source_PollType::DECIMAL:
                    $answerField = 'decimal_value';
                    $answerId = $answerId = strlen($answerId)
                        ? floatval($answerId)
                        : null;
                    break;
                default:
                    $answerId = (int)($answerId);
            }
            $user = Mage::getSingleton('admin/session')->getUser();
            $voteData = array(
                'poll_id' => $pollId,
                $answerField => $answerId,
                'customer_id' => $customerId,
                'type'  => $pollType,
                'manager_id' => $user->getId(),
                'manager_name' => $user->getName()
            );

            $vote = Mage::getModel('magedoc_poll/poll_vote');
            $vote->load(array($pollId, $customerId), array('poll_id', 'customer_id'));
            $vote
                ->addData($voteData)
                ->setIpAddress(Mage::helper('core/http')->getRemoteAddr(true));

            $vote->save();
            Mage::dispatchEvent(
                'poll_vote_add',
                array(
                    'poll'  => $poll,
                    'vote'  => $vote
                )
            );
        }
    }

    protected function _validateFormKey()
    {
        return true;
    }
}
