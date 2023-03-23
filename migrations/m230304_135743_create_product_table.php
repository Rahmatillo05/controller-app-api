<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m230304_135743_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'product_name' => $this->string(200)->notNull(),
            'all_amount' => $this->integer()->notNull(),
            'purchase_price' => $this->integer()->notNull(),
            'wholesale_price' => $this->integer()->notNull(),
            'retail_price' => $this->integer()->notNull(),
            'created_at' => $this->integer()
        ]);
        $this->addForeignKey('fk-from-product-to-category', 'product', 'category_id', 'category', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-from-product-to-category', 'product');
        $this->dropTable('{{%product}}');
    }
}
