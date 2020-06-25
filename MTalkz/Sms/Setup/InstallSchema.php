<?php
namespace MTalkz\Sms\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (!$context->getVersion()) {
            $orderTable = $installer->getTable('sales_order');
            $installer->getConnection()->addColumn(
                $orderTable,
                'sms_sent_status',
                [
                   'type'       => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                   'length'     => 1,
                   'comment'    => 'Has SMS notification been sent'
                ]
            );
        }
        $installer->endSetup();
    }
}
