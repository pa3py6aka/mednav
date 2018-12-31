<?php

use yii\db\Migration;

/**
 * Class m181223_170124_add_text_column_to_dialogs
 */
class m181223_170124_add_text_column_to_dialogs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dialogs}}', 'text', $this->text()->comment('Используется только для импортированных сообщений со старой версии сайта')->notNull());
        $this->addColumn('{{%dialogs}}', 'date', $this->integer()->notNull()->defaultValue(0));

        $this->addColumn('{{%support_dialogs}}', 'text', $this->text()->comment('Используется только для импортированных сообщений со старой версии сайта')->notNull());
        $this->addColumn('{{%support_dialogs}}', 'date', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%dialogs}}', 'text');
        $this->dropColumn('{{%dialogs}}', 'date');
        $this->dropColumn('{{%support_dialogs}}', 'text');
        $this->dropColumn('{{%support_dialogs}}', 'date');
    }
}
