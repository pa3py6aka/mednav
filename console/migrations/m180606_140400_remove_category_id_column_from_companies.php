<?php

use yii\db\Migration;

/**
 * Class m180606_140400_remove_category_id_column_from_companies
 */
class m180606_140400_remove_category_id_column_from_companies extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-companies-category_id', '{{%companies}}');
        $this->dropColumn('{{%companies}}', 'category_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%companies}}', 'category_id', $this->integer()->notNull()->after('id'));
        $this->createIndex('idx-companies-category_id', '{{%companies}}', 'category_id');
        $this->addForeignKey('fk-companies-category_id', '{{%companies}}', 'category_id', '{{%company_categories}}', 'id', 'RESTRICT', 'CASCADE');
    }
}
