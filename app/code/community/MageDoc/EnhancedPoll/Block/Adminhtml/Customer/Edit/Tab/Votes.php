<?php

class MageDoc_EnhancedPoll_Block_Adminhtml_Customer_Edit_Tab_Votes extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_defaultSort     = 'vote_id';
    protected $_defaultDir      = 'desc';

    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_votes_grid');

        $this->setUseAjax(true);
    }

    protected function _prepareColumns()
    {
        $operators = array();

        $this->addColumn('vote_time', array(
            'header' => Mage::helper('magedoc')->__('Created At'),
            'width' => '50px',
            'index' => 'vote_time',
            'type'  => 'datetime',
        ));

        $this->addColumn('poll_title', array(
            'header' => Mage::helper('magedoc')->__('Poll'),
            'index' => 'poll_title',
        ));

        $this->addColumn('poll_answer', array(
            'header' => Mage::helper('magedoc')->__('Answer'),
            'index' => 'poll_answer'
        ));

        if (Mage::helper('callbackrequest')->isModuleEnabled('Testimonial_MageDoc')) {
            $managers =  Mage::getModel('magedoc/source_orderManager')
                ->getOptionArray();
        } else {
            $items = Mage::getResourceModel('admin/user_collection');
            $managers = array();
            foreach($items as $item){
                $managers[$item->getUserId()] = $item->getName();
            }
        }

        $this->addColumn('manager',
            array(
                'header'        => Mage::helper('callbackrequest')->__('Manager'),
                'index'         => 'manager_name',
                'filter_index'  => 'manager_id',
                'type'          => 'options',
                'options'       => $managers,
                'show_missing_option_values'    => true,
                'frame_callback' => array($this, 'decorateManager'),
            )
        );

        parent::_prepareColumns();

    }

    protected function _prepareCollection()
    {
        /**
         * @var $customer Mage_Customer_Model_Customer
         */
        $customer = $this->getCustomer();

        /**
         * @var $votesCollection MageDoc_EnhancedPoll_Model_Resource_Poll_Vote_Collection
         */
        $votesCollection = Mage::getResourceModel('magedoc_poll/poll_vote_collection');
        $columns = array(
            'vote_time',
            'manager_id',
            'manager_name',
        );
        $votesCollection->getSelect()->reset(Zend_Db_Select::COLUMNS)
            ->columns($columns);
        $votesCollection->addFieldToFilter('customer_id', $customer->getId());
        $votesCollection->getSelect()
            ->join(
                array('p' => $votesCollection->getTable('poll/poll')),
                'p.poll_id = main_table.poll_id',
                array(
                    'poll_title'
                )
            );
        $pollAnswerExpression = new Zend_Db_Expr('IFNULL(pa.answer_title, IF(main_table.type = \'integer\', main_table.int_value, IF(main_table.type = \'decimal\', main_table.decimal_value, main_table.text_value)))');
        $votesCollection->getSelect()
            ->joinLeft(
                array('pa' => $votesCollection->getTable('poll/poll_answer')),
                'main_table.poll_id = pa.poll_id
                        AND pa.answer_id = main_table.poll_answer_id
                        AND main_table.type = \'options\'',
                array(
                    'poll_answer' => $pollAnswerExpression
                )
            );
        $votesCollection->addFilterToMap('poll_answer', $pollAnswerExpression);

        $this->setCollection($votesCollection);

        parent::_prepareCollection();

        return $votesCollection;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/votesGrid', array('_current'=>true, 'id' => $this->getCustomer()->getId()));
    }

    /**
     * @return Mage_Customer_Model_Customer
     */

    public function getCustomer()
    {
        return Mage::registry('current_customer');
    }

    protected function _havingFilterCallback($collection, $column)
    {
        $field = ( $column->getFilterIndex() ) ? $column->getFilterIndex() : $column->getIndex();
        $cond = $column->getFilter()->getCondition();
        if ($field && $cond){
            $collection->addFieldToHavingFilter($field, $cond);
        }
    }

    public function decorateManager($value, $row, $column, $isExport)
    {
        return $row->getManagerName();
    }
}