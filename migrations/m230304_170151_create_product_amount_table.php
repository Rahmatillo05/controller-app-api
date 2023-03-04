<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_amount}}`.
 */
class m230304_170151_create_product_amount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_amount}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'has_came_product' => $this->integer(),
            'sold_product' => $this->integer(),
            'remaining_product' => $this->integer()
        ]);
        $this->addForeignKey('fk-from-amount-to-product', 'product_amount', 'product_id', 'product', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-from-amount-to-product', 'product_amount');
        $this->dropTable('{{%product_amount}}');
    }
}
