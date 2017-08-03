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
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '0.2.0', '<')) {
            $this->upgradeTo020($setup);
        }
        if (version_compare($context->getVersion(), '0.2.1', '<')) {
            $this->upgradeTo021($setup);
        }
        if (version_compare($context->getVersion(), '1.0.0', '<')) {
            $this->upgradeTo100($setup);
        }
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->upgradeTo101($setup);
        }
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->upgradeTo102($setup);
        }
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->upgradeTo103($setup);
        }
        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->upgradeTo110($setup);
        }
    }

    protected function upgradeTo020(SchemaSetupInterface $setup)
    {
        $conn = $setup->getConnection();
        $conn->addColumn(
            'msp_notify_template',
            'name',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => false,
                'comment' => "Template name",
            ]
        );
        $conn->addColumn(
            'msp_notify_template',
            'enabled',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => true,
                'comment' => "Enabled status",
            ]
        );
    }

    protected function upgradeTo021(SchemaSetupInterface $setup)
    {
        $conn = $setup->getConnection();
        $conn->addColumn(
            'msp_notify_notification',
            'message',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => false,
                'comment' => "Message text",
            ]
        );
    }

    protected function upgradeTo100(SchemaSetupInterface $setup)
    {

        $conn = $setup->getConnection();

        $channelsTable = $conn->newTable($conn->getTableName('msp_notify_channel'));

        $channelsTable
            ->addColumn(
                'channel_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Channel id'
            )
            ->addColumn(
                'adapter_code',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'Adapter code'
            )
            ->addColumn(
                'adapter_configuration',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'Serialized adapter configuration'
            );

        $conn->createTable($channelsTable);

        $eventsTable = $conn->newTable($conn->getTableName('msp_notify_event'));

        $eventsTable
            ->addColumn(
                'event_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Event id'
            )
            ->addColumn(
                'linked_events',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'linked templates'
            )
            ->addColumn(
                'channel_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ]
            )
            ->addForeignKey(
                $conn->getForeignKeyName(
                    'msp_notify_channel',
                    'channel_id',
                    'msp_notify_event',
                    'channel_id'
                ),
                'channel_id',
                'msp_notify_channel',
                'channel_id'
            );

        $conn->createTable($eventsTable);
    }

    protected function upgradeTo101(SchemaSetupInterface $setup)
    {
        $conn = $setup->getConnection();
        $table = $conn->getTableName('msp_notify_notification');

        $conn->truncateTable($table);

        $conn->changeColumn(
            $table,
            'template_id',
            'event_id',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => false,
                'unsigned' => true,
            ]
        );

        $conn->addColumn(
            $table,
            'channel_id',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => false,
                'unsigned' => true,
                'comment' => 'channel id'
            ]
        );

        $conn->addForeignKey(
            $conn->getForeignKeyName(
                'msp_notify_notification',
                'channel_id',
                'msp_notify_channel',
                'channel_id'
            ),
            $table,
            'channel_id',
            'msp_notify_channel',
            'channel_id'
        );

        $conn->addForeignKey(
            $conn->getForeignKeyName(
                'msp_notify_notification',
                'event_id',
                'msp_notify_event',
                'event_id'
            ),
            $table,
            'event_id',
            'msp_notify_event',
            'event_id'
        );
    }

    protected function upgradeTo102(SchemaSetupInterface $setup)
    {

        $conn = $setup->getConnection();

        $channelTable = $conn->getTableName('msp_notify_channel');

        $conn->addColumn(
            $channelTable,
            'name',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => false,
                'comment' => 'human readable name'
            ]
        );
    }


    protected function upgradeTo103(SchemaSetupInterface $setup)
    {

        $conn = $setup->getConnection();

        $eventTable = $conn->getTableName('msp_notify_event');

        $conn->addColumn(
            $eventTable,
            'name',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => false,
                'comment' => 'human readable name'
            ]
        );
    }

    protected function upgradeTo110(SchemaSetupInterface $setup)
    {

        $conn = $setup->getConnection();

        $notificationTable = $conn->getTableName('msp_notify_notification');

        $conn->dropColumn($notificationTable, 'channel_id');
        $conn->dropColumn($notificationTable, 'adapter_code');
        $conn->dropColumn($notificationTable, 'adapter_configuration');
    }
}
