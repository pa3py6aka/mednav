<?php

use yii\db\Migration;

/**
 * Class m180606_055139_remove_tree_column_from_board_categories
 */
class m180606_055139_remove_tree_column_from_board_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%board_categories}}', 'tree');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%board_categories}}', 'tree', $this->integer()->null()->after('active'));
    }
}
