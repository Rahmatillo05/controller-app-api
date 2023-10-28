<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 */
class m231027_170554_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(),
            'code' => $this->integer(),
            'error_file' => $this->string(),
            'error_line' => $this->integer(),
            'data' => $this->json(),
            'header' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }
}
