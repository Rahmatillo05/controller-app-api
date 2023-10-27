<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%storage_repository}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%category}}`
 * - `{{%product}}`
 * - `{{%unit}}`
 */
class m231027_150628_create_storage_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%storage_product}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'product_id' => $this->integer(),
            'unit_id' => $this->integer(),
            'amount' => $this->double(),
            'price' => $this->double(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-storage_product-category_id}}',
            '{{%storage_product}}',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            '{{%fk-storage_product-category_id}}',
            '{{%storage_product}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-storage_product-product_id}}',
            '{{%storage_product}}',
            'product_id'
        );

        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-storage_product-product_id}}',
            '{{%storage_product}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE'
        );

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-storage_product-unit_id}}',
            '{{%storage_product}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-storage_product-unit_id}}',
            '{{%storage_product}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            '{{%fk-storage_product-category_id}}',
            '{{%storage_product}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-storage_product-category_id}}',
            '{{%storage_product}}'
        );

        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-storage_product-product_id}}',
            '{{%storage_product}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-storage_product-product_id}}',
            '{{%storage_product}}'
        );

        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-storage_product-unit_id}}',
            '{{%storage_product}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-storage_product-unit_id}}',
            '{{%storage_product}}'
        );

        $this->dropTable('{{%storage_product}}');
    }
}
