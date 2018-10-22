<?php

use yii\db\Migration;

/**
 * Class m181022_140852_add_columns_to_expositions_table
 */
class m181022_140852_add_columns_to_expositions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%expositions}}', 'show_dates', $this->boolean()->notNull()->defaultValue(true)->after('views'));
        $this->addColumn('{{%expositions}}', 'city', $this->string()->notNull()->defaultValue('')->after('end_date'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%expositions}}', 'city');
        $this->dropColumn('{{%expositions}}', 'show_dates');
    }
}
