<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statistics_detail}}`.
 */
class m230329_153001_create_statistics_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%statistics_detail}}', [
            'id' => $this->primaryKey(),
            'period_id' => $this->integer(),
            'on_cash' => $this->float(),
            'on_plastic' => $this->float(),
            'on_debt' => $this->float(),
            'other_spent' => $this->float(),
            'plastic_percent' => $this->float(),
            'product_sum' => $this->float()
        ]);
        $this->addForeignKey('fk-to-statistics-table', 'statistics_detail', 'period_id', 'statistics', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-to-statistics-table', 'statistics_detail');
        $this->dropTable('{{%statistics_detail}}');
    }
}
