<?php

use yii\db\Migration;

/**
 * Class m180720_093557_add_company_id_column_to_trades
 */
class m180720_093557_add_company_id_column_to_trades extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%trades}}', 'company_id', $this->integer()->null()->after('user_id'));
        $this->createIndex('idx-trades-company_id', '{{%trades}}', 'company_id');

        foreach (\core\entities\Trade\Trade::find()->with('user.company')->each() as $trade) {
            /* @var $trade \core\entities\Trade\Trade */
            $trade->company_id = $trade->user->company->id;
            $trade->save();
        }

        $this->alterColumn('{{%trades}}', 'company_id', $this->integer()->notNull());
        $this->addForeignKey('fk-trades-company_id', '{{%trades}}', 'company_id', '{{%companies}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-trades-company_id', '{{%trades}}');
        $this->dropIndex('idx-trades-company_id', '{{%trades}}');
        $this->dropColumn('{{%trades}}', 'company_id');
    }
}
