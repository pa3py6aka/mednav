<?php

use yii\db\Migration;

/**
 * Handles the creation of table `companies`.
 */
class m180511_203417_create_companies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%companies}}', [
            'id' => $this->primaryKey(),
            'form' => $this->string(50)->notNull(),
            'name' => $this->string()->notNull(),
            'logo' => $this->string()->notNull()->defaultValue(''),
            'slug' => $this->string()->notNull(),
            'site' => $this->string()->notNull()->defaultValue(''),
            'geo_id' => $this->integer()->notNull(),
            'address' => $this->string()->notNull(),
            'phones' => $this->string()->notNull()->defaultValue('{}'),
            'fax' => $this->string(50)->notNull()->defaultValue(''),
            'email' => $this->string()->notNull(),
            'info' => $this->text(),
            'title' => $this->string()->notNull(),
            'short_description' => $this->text(),
            'description' => $this->text()->notNull(),
            'main_photo_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-companies-slug', '{{%companies}}', 'slug', true);
        $this->createIndex('idx-companies-geo_id', '{{%companies}}', 'geo_id');
        $this->addForeignKey('fk-companies-geo_id', '{{%companies}}', 'geo_id', '{{%geo}}', 'id', 'RESTRICT', 'CASCADE');

        $this->addColumn('{{%users}}', 'company_id', $this->integer()->after('type'));
        $this->addForeignKey('fk-users-company_id', '{{%users}}', 'company_id', '{{%companies}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%company_photos}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()->unique(),
            'sort' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);
        $this->createIndex('idx-company_photos-company_id', '{{%company_photos}}', 'company_id');
        $this->addForeignKey('fk-company_photos-company_id', '{{%company_photos}}', 'company_id', '{{%companies}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-companies-main_photo_id', '{{%companies}}', 'main_photo_id', '{{%company_photos}}', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('{{%company_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('{{%company_tags_assignment}}', [
            'company_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk-company_tags_assignment', '{{%company_tags_assignment}}', ['company_id', 'tag_id']);
        $this->createIndex('idx-company_tags_assignment-company_id', '{{%company_tags_assignment}}', 'company_id');
        $this->addForeignKey('fk-company_tags_assignment-company_id', '{{%company_tags_assignment}}', 'company_id', '{{%companies}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-company_tags_assignment-tag_id', '{{%company_tags_assignment}}', 'tag_id', '{{%company_tags}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%companies}}');
    }
}
