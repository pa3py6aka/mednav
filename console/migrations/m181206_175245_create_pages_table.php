<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pages`.
 */
class m181206_175245_create_pages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%pages}}', [
            'id' => $this->primaryKey(),
            'type' => $this->tinyInteger()->notNull(),
            'name' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'meta_title' => $this->string()->notNull(),
            'meta_description' => $this->text()->notNull(),
            'meta_keywords' => $this->text()->notNull(),
            'slug' => $this->string(100)->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-pages-slug', '{{%pages}}', 'slug', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pages}}');
    }
}
