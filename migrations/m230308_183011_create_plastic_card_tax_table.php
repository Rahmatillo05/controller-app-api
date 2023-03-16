<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%plastic_card_tax}}`.
 */
class m230308_183011_create_plastic_card_tax_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%plastic_card_tax}}', [
            'id' => $this->primaryKey(),
            'tax_amount' => $this->money()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
        $this->insert('plastic_card_tax',  [
            'tax_amount' => 3,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%plastic_card_tax}}');
    }
}
