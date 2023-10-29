<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%storage_product}}`.
 */
class m231029_125822_add_income_amount_column_to_storage_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('storage_product', 'income_amount', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('storage_product', 'income_amount');
    }
}
