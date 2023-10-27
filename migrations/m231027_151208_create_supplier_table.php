<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier}}`.
 */
class m231027_151208_create_supplier_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplier}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'phone' => $this->string(),
            'address' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%supplier}}');
    }
}
