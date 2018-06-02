<?php

use yii\db\Migration;

/**
 * Class m180516_223626_create_company_category_tables
 */
class m180516_223626_create_company_category_tables extends Migration
{
    public function up()
    {
        /*$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%company_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'context_name' => $this->string()->notNull()->defaultValue(''),
            'enabled' => $this->boolean()->notNull()->defaultValue(true),
            'not_show_on_main' => $this->boolean()->notNull()->defaultValue(false),
            'children_only_parent' => $this->boolean()->notNull()->defaultValue(false),
            'slug' => $this->string()->notNull(),
            'meta_title' => $this->string()->notNull()->defaultValue(''),
            'meta_description' => $this->text()->notNull(),
            'meta_keywords' => $this->text()->notNull(),
            'title' => $this->string()->notNull()->defaultValue(''),
            'description_top' => $this->text()->notNull(),
            'description_top_on' => $this->boolean()->notNull()->defaultValue(true),
            'description_bottom' => $this->text()->notNull(),
            'description_bottom_on' => $this->boolean()->notNull()->defaultValue(true),
            'meta_title_item' => $this->string()->notNull()->defaultValue(''),
            'meta_description_item' => $this->text()->notNull(),
            'pagination' => $this->tinyInteger()->notNull()->defaultValue(1),
            'active' => $this->boolean()->notNull()->defaultValue(true),

            'tree' => $this->integer()->null(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-company_categories-slug}}', '{{%company_categories}}', 'slug', true);
        $this->createIndex('lft', '{{%company_categories}}', ['tree', 'lft', 'rgt']);
        $this->createIndex('rgt', '{{%company_categories}}', ['tree', 'rgt']);*/
    }

    public function down()
    {
        //$this->dropTable('{{%company_categories}}');
    }
}
