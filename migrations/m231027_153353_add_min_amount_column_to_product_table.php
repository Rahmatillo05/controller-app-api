<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%product}}`.
 */
class m231027_153353_add_min_amount_column_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'min_amount', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', 'min_amount');
    }
}
