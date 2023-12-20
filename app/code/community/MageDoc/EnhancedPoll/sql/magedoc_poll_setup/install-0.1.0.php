<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableName = 'poll/poll';

$installer->getConnection()
    ->addColumn($installer->getTable('poll/poll'), 'type', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    =>  36,
        'nullable' => false,
        'default' => 'options',
        'comment' => 'Poll Type',
    ));
$installer->getConnection()
    ->addColumn($installer->getTable('poll/poll'), 'settings', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Poll Settings Json',
    ));

$installer->getConnection()->addIndex(
    $installer->getTable($tableName),
    $installer->getIdxName($tableName, 'type'),
    'type'
);

$tableName = 'poll/poll_vote';

$installer->getConnection()
    ->addColumn($installer->getTable($tableName), 'type', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    =>  36,
        'nullable' => false,
        'default' => 'options',
        'comment' => 'Poll Type',
    ));
$installer->getConnection()
    ->addColumn($installer->getTable($tableName), 'text_value', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Poll Vote Text Value',
    ));
$installer->getConnection()
    ->addColumn($installer->getTable($tableName), 'int_value', array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'default' => null,
        'comment' => 'Poll Vote Int Value',
    ));
$installer->getConnection()
    ->addColumn($installer->getTable($tableName), 'decimal_value', array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'length' => '12,4',
        'nullable' => true,
        'default' => null,
        'comment' => 'Poll Vote Decimal Value',
    ));

$installer->getConnection()
    ->addColumn($installer->getTable($tableName), 'manager_id', array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'default' => null,
        'unsigned' => true,
        'comment' => 'Poll Vote Manager Id',
    ));

$installer->getConnection()
    ->addColumn($installer->getTable($tableName), 'manager_name', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length' => 255,
        'nullable' => true,
        'default' => null,
        'comment' => 'Poll Vote Manager Name',
    ));

$installer->getConnection()->addIndex(
    $installer->getTable($tableName),
    $installer->getIdxName($tableName, 'type'),
    'type'
);

$installer->getConnection()->addIndex(
    $installer->getTable($tableName),
    $installer->getIdxName($tableName, array('poll_id', 'customer_id')),
    array('poll_id', 'customer_id')
);

$installer->endSetup();