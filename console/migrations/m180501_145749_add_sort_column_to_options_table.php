<?php

use yii\db\Migration;

/**
 * Handles adding sort to table `options`.
 */
class m180501_145749_add_sort_column_to_options_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%board_parameter_options}}', 'sort', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%board_parameter_options}}', 'sort');
    }
}
