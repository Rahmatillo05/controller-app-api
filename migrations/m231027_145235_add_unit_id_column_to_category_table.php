<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%category}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%unit}}`
 */
class m231027_145235_add_unit_id_column_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category}}', 'unit_id', $this->integer());
        $this->addColumn('{{%product}}', 'unit_id', $this->integer());
        $this->addColumn('product', 'status', $this->smallInteger()->defaultValue(1));
        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-category-unit_id}}',
            '{{%category}}',
            'unit_id'
        );
        $this->createIndex(
            '{{%idx-product-unit_id}}',
            '{{%product}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-category-unit_id}}',
            '{{%category}}',
            'unit_id',
            '{{%unit}}',
            'id',
        );
        $this->addForeignKey(
            '{{%fk-product-unit_id}}',
            '{{%product}}',
            'unit_id',
            '{{%unit}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-category-unit_id}}',
            '{{%category}}'
        );
        $this->dropForeignKey(
            '{{%fk-product-unit_id}}',
            '{{%product}}'
        );
        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-category-unit_id}}',
            '{{%category}}'
        );
        $this->dropIndex(
            '{{%idx-product-unit_id}}',
            '{{%product}}'
        );
        $this->dropColumn('{{%category}}', 'unit_id');
        $this->dropColumn('{{%product}}', 'unit_id');
    }
}
