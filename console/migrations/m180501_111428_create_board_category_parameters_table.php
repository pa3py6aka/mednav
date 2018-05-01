<?php

use yii\db\Migration;

/**
 * Handles the creation of table `board_category_parameters`.
 */
class m180501_111428_create_board_category_parameters_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%board_category_parameters}}', [
            'category_id' => $this->integer()->notNull(),
            'parameter_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-board_category_parameters', '{{%board_category_parameters}}', ['category_id', 'parameter_id']);
        $this->createIndex('idx-board_category_parameters-category_id', '{{%board_category_parameters}}', 'category_id');
        $this->addForeignKey('fk-board_category_parameters-category_id', '{{%board_category_parameters}}', 'category_id', '{{%board_categories}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-board_category_parameters-parameter_id', '{{%board_category_parameters}}', 'parameter_id', '{{%board_parameters}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%board_category_parameters}}');
    }
}
