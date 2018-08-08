<?php
namespace MageReactor\PageAuthorization\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mr_page_authorization')
        )->addColumn(
            'item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'MageReactor PageAuthorization Item Id'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Item Creation Time'
        )->addColumn(
            'update_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Item Modification Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Block Active'
        )->setComment(
            'MageReactor PageAuthorization Table'
        );
        $installer->getConnection()->createTable($table);


        $table = $installer->getConnection()->newTable(
            $installer->getTable('mr_page_authorization_store')
        )->addColumn(
            'item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Item Id'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('mr_page_authorization_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('mr_page_authorization_store', 'item_id', 'mr_page_authorization', 'item_id'),
            'item_id',
            $installer->getTable('mr_page_authorization'),
            'item_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('mr_page_authorization_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'MageReactor PageAuhorization To Store Linkage Table'
        );

        $installer->getConnection()->createTable($table);



        $table = $installer->getConnection()->newTable(
            $installer->getTable('mr_page_authorization_cms_pages')
        )->addColumn(
            'item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Item Id'
        )->addColumn(
            'cms_page_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Page ID'
        )->addIndex(
            $installer->getIdxName('mr_page_authorization_cms_pages', ['cms_page_id']),
            ['cms_page_id']
        )->addForeignKey(
            $installer->getFkName('mr_page_authorization_cms_pages', 'item_id', 'mr_page_authorization', 'item_id'),
            'item_id',
            $installer->getTable('mr_page_authorization'),
            'item_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('mr_page_authorization_cms_pages', 'cms_page_id', 'cms_page', 'page_id'),
            'cms_page_id',
            $installer->getTable('cms_page'),
            'page_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'MageReactor PageAuhorization To Cms Page Linkage Table'
        );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mr_page_authorization_categories')
        )->addColumn(
            'item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'primary' => true],
            'Item Id'
        )->addColumn(
            'category_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Category Id'
        )->addIndex(
            $installer->getIdxName('mr_page_authorization_categories', ['category_id']),
            ['category_id']
        )->addForeignKey(
            $installer->getFkName('mr_page_authorization_categories', 'item_id', 'mr_page_authorization', 'item_id'),
            'item_id',
            $installer->getTable('mr_page_authorization'),
            'item_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('mr_page_authorization_categories', 'category_id', 'catalog_category_entity', 'entity_id'),
            'category_id',
            $installer->getTable('catalog_category_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'MageReactor PageAuhorization To Cms Page Linkage Table'
        );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}