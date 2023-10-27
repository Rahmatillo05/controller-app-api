<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_list}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%supplier}}`
 */
class m231027_151634_create_product_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_list}}', [
            'id' => $this->primaryKey(),
            'supplier_id' => $this->integer(),
            'date' => $this->integer(),
            'status' => $this->smallInteger(2),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        // creates index for column `supplier_id`
        $this->createIndex(
            '{{%idx-product_list-supplier_id}}',
            '{{%product_list}}',
            'supplier_id'
        );

        // add foreign key for table `{{%supplier}}`
        $this->addForeignKey(
            '{{%fk-product_list-supplier_id}}',
            '{{%product_list}}',
            'supplier_id',
            '{{%supplier}}',
            'id',
        );
        $this->addColumn('storage_product', 'product_list_id', $this->integer());

        $this->createIndex(
            '{{%idx-storage_product-product_list_id}}',
            '{{%storage_product}}',
            'product_list_id'
        );

        // add foreign key for table `{{%supplier}}`
        $this->addForeignKey(
            '{{%fk-storage_product-product_list_id}}',
            '{{%storage_product}}',
            'product_list_id',
            '{{%product_list}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%supplier}}`
        $this->dropForeignKey(
            '{{%fk-product_list-supplier_id}}',
            '{{%product_list}}'
        );

        // drops index for column `supplier_id`
        $this->dropIndex(
            '{{%idx-product_list-supplier_id}}',
            '{{%product_list}}'
        );
        $this->dropForeignKey(
            '{{%fk-storage_product-product_list_id}}',
            '{{%storage_product}}'
        );

        // drops index for column `supplier_id`
        $this->dropIndex(
            '{{%idx-storage_product-product_list_id}}',
            '{{%storage_product}}'
        );
        $this->dropTable('{{%product_list}}');
    }
}
