<?php
/**
 * MageSpecialist
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magespecialist.it so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2017 Skeeller srl (http://www.magespecialist.it)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace MSP\Notify\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $connection = $setup->getConnection();

        $tableName = $connection->getTableName('msp_notify_template');

        $table = $connection->newTable($tableName);
        $table->addColumn(
            'msp_notify_template_id',
            Table::TYPE_INTEGER,
            null,
            [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
            'Template id'
        )
            ->addColumn(
                'adapter_code',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'Adapter identifier'
            )
            ->addColumn(
                'events',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => true,
                ],
                'Comma separated event list'
            )
            ->addColumn(
                'adapter_configuration',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => true
                ],
                'serialized configuration string'
            );

        $connection->createTable($table);

        $tableName = $connection->getTableName('msp_notify_notification');

        $table = $connection->newTable($tableName);
        $table->addColumn(
            'msp_notify_notification_id',
            Table::TYPE_INTEGER,
            null,
            [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
            'Notification id'
        )
            ->addColumn(
                'template_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Notification template'
            )
            ->addColumn(
                'status',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'Execution status'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => true
                ],
                'creation timestamp'
            )
            ->addColumn(
                'executed_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => true
                ],
                'execution timestamp'
            )
            ->addColumn(
                'adapter_code',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'Adapter identifier'
            )
            ->addColumn(
                'event',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => true,
                ],
                'Originating event'
            )
            ->addColumn(
                'adapter_configuration',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => true
                ],
                'serialized configuration string'
            );

        $connection->createTable($table);

        $connection->addForeignKey(
            $setup->getFkName('msp_notify_notification', 'template_id', 'msp_notify_template', 'msp_notify_template_id'),
            'msp_notify_notification',
            'template_id',
            'msp_notify_template',
            'msp_notify_template_id'
        );

        $setup->endSetup();
    }
}
