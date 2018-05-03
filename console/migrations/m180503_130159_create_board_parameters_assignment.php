<?php

use yii\db\Migration;

/**
 * Class m180503_130159_create_board_parameters_assignment
 */
class m180503_130159_create_board_parameters_assignment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%board_parameters_assignment}}', [
            'board_id' => $this->integer()->notNull(),
            'parameter_id' => $this->integer()->notNull(),
            'option_id' => $this->integer()->null(),
            'value' => $this->string()->notNull()->defaultValue(''),
        ], $tableOptions);
        $this->addPrimaryKey('pk-board_parameters_assignment', '{{%board_parameters_assignment}}', ['board_id', 'parameter_id']);
        $this->createIndex('idx-board_parameters_assignment-board_id', '{{%board_parameters_assignment}}', 'board_id');
        $this->addForeignKey('fk-board_parameters_assignment-board_id', '{{%board_parameters_assignment}}', 'board_id', '{{%boards}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-board_parameters_assignment-parameter_id', '{{%board_parameters_assignment}}', 'parameter_id', '{{%board_parameters}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-board_parameters_assignment-option_id', '{{%board_parameters_assignment}}', 'option_id', '{{%board_parameter_options}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%board_parameters_assignment}}');
    }
}
