<?php

use yii\db\Migration;

/**
 * Handles the creation of table `board_parameter_groups`.
 */
class m180501_105528_create_board_parameter_groups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%board_parameters}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type' => $this->tinyInteger()->notNull()->defaultValue(1),
            'active' => $this->boolean()->notNull()->defaultValue(true),
            'sort' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createTable('{{%board_parameter_options}}', [
            'id' => $this->primaryKey(),
            'parameter_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-board_parameter_options-parameter_id', '{{%board_parameter_options}}', 'parameter_id');
        $this->addForeignKey('fk-board_parameter_options-parameter_id', '{{%board_parameter_options}}', 'parameter_id', '{{%board_parameters}}', 'id', 'CASCADE', 'CASCADE');

        $this->insert('{{%board_parameters}}', [
            'id' => 1,
            'name' => 'Тип',
            'type' => 1,
            'active' => 1,
            'sort' => 0,
        ]);

        $this->batchInsert('{{%board_parameter_options}}', ['parameter_id', 'name', 'slug'], [
            [1, 'Продам', 'sell'],
            [1, 'Куплю', 'buy'],
            [1, 'Услуга', 'service'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%board_parameter_options}}');
        $this->dropTable('{{%board_parameters}}');
    }
}
