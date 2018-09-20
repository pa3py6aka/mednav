<?php

use yii\db\Migration;

/**
 * Class m180916_114308_add_company_id_column_to_articles
 */
class m180916_114308_add_company_id_column_to_articles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%articles}}', 'company_id', $this->integer()->null()->after('user_id'));
        $this->addForeignKey('fk-articles-company_id', '{{%articles}}', 'company_id', '{{%companies}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-articles-company_id', '{{%articles}}');
        $this->dropColumn('{{%articles}}', 'company_id');
    }
}
