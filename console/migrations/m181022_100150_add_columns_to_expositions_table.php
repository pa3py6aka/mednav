<?php

use yii\db\Migration;

/**
 * Class m181022_100150_add_columns_to_expositions_table
 */
class m181022_100150_add_columns_to_expositions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%expositions}}', 'start_date', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('views'));
        $this->addColumn('{{%expositions}}', 'end_date', $this->integer()->unsigned()->notNull()->defaultValue(0)->after('start_date'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%expositions}}', 'end_date');
        $this->dropColumn('{{%expositions}}', 'start_date');
    }
}
