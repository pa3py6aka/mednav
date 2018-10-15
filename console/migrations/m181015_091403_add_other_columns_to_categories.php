<?php

use yii\db\Migration;

/**
 * Class m181015_091403_add_other_columns_to_categories
 */
class m181015_091403_add_other_columns_to_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%board_categories}}', 'meta_title_other', $this->string()->notNull()->defaultValue('')->after('meta_description_item'));
        $this->addColumn('{{%board_categories}}', 'meta_description_other', $this->text()->after('meta_title_other'));
        $this->addColumn('{{%board_categories}}', 'meta_keywords_other', $this->text()->after('meta_description_other'));
        $this->addColumn('{{%board_categories}}', 'title_other', $this->string()->notNull()->defaultValue('')->after('meta_keywords_other'));

        $this->addColumn('{{%article_categories}}', 'meta_title_other', $this->string()->notNull()->defaultValue('')->after('meta_description_item'));
        $this->addColumn('{{%article_categories}}', 'meta_description_other', $this->text()->after('meta_title_other'));
        $this->addColumn('{{%article_categories}}', 'meta_keywords_other', $this->text()->after('meta_description_other'));
        $this->addColumn('{{%article_categories}}', 'title_other', $this->string()->notNull()->defaultValue('')->after('meta_keywords_other'));

        $this->addColumn('{{%brand_categories}}', 'meta_title_other', $this->string()->notNull()->defaultValue('')->after('meta_description_item'));
        $this->addColumn('{{%brand_categories}}', 'meta_description_other', $this->text()->after('meta_title_other'));
        $this->addColumn('{{%brand_categories}}', 'meta_keywords_other', $this->text()->after('meta_description_other'));
        $this->addColumn('{{%brand_categories}}', 'title_other', $this->string()->notNull()->defaultValue('')->after('meta_keywords_other'));

        $this->addColumn('{{%cnews_categories}}', 'meta_title_other', $this->string()->notNull()->defaultValue('')->after('meta_description_item'));
        $this->addColumn('{{%cnews_categories}}', 'meta_description_other', $this->text()->after('meta_title_other'));
        $this->addColumn('{{%cnews_categories}}', 'meta_keywords_other', $this->text()->after('meta_description_other'));
        $this->addColumn('{{%cnews_categories}}', 'title_other', $this->string()->notNull()->defaultValue('')->after('meta_keywords_other'));

        $this->addColumn('{{%company_categories}}', 'meta_title_other', $this->string()->notNull()->defaultValue('')->after('meta_description_item'));
        $this->addColumn('{{%company_categories}}', 'meta_description_other', $this->text()->after('meta_title_other'));
        $this->addColumn('{{%company_categories}}', 'meta_keywords_other', $this->text()->after('meta_description_other'));
        $this->addColumn('{{%company_categories}}', 'title_other', $this->string()->notNull()->defaultValue('')->after('meta_keywords_other'));

        $this->addColumn('{{%news_categories}}', 'meta_title_other', $this->string()->notNull()->defaultValue('')->after('meta_description_item'));
        $this->addColumn('{{%news_categories}}', 'meta_description_other', $this->text()->after('meta_title_other'));
        $this->addColumn('{{%news_categories}}', 'meta_keywords_other', $this->text()->after('meta_description_other'));
        $this->addColumn('{{%news_categories}}', 'title_other', $this->string()->notNull()->defaultValue('')->after('meta_keywords_other'));

        $this->addColumn('{{%trade_categories}}', 'meta_title_other', $this->string()->notNull()->defaultValue('')->after('meta_description_item'));
        $this->addColumn('{{%trade_categories}}', 'meta_description_other', $this->text()->after('meta_title_other'));
        $this->addColumn('{{%trade_categories}}', 'meta_keywords_other', $this->text()->after('meta_description_other'));
        $this->addColumn('{{%trade_categories}}', 'title_other', $this->string()->notNull()->defaultValue('')->after('meta_keywords_other'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%board_categories}}', 'meta_title_other');
        $this->dropColumn('{{%board_categories}}', 'meta_description_other');
        $this->dropColumn('{{%board_categories}}', 'meta_keywords_other');
        $this->dropColumn('{{%board_categories}}', 'title_other');

        $this->dropColumn('{{%article_categories}}', 'meta_title_other');
        $this->dropColumn('{{%article_categories}}', 'meta_description_other');
        $this->dropColumn('{{%article_categories}}', 'meta_keywords_other');
        $this->dropColumn('{{%article_categories}}', 'title_other');

        $this->dropColumn('{{%brand_categories}}', 'meta_title_other');
        $this->dropColumn('{{%brand_categories}}', 'meta_description_other');
        $this->dropColumn('{{%brand_categories}}', 'meta_keywords_other');
        $this->dropColumn('{{%brand_categories}}', 'title_other');

        $this->dropColumn('{{%cnews_categories}}', 'meta_title_other');
        $this->dropColumn('{{%cnews_categories}}', 'meta_description_other');
        $this->dropColumn('{{%cnews_categories}}', 'meta_keywords_other');
        $this->dropColumn('{{%cnews_categories}}', 'title_other');

        $this->dropColumn('{{%company_categories}}', 'meta_title_other');
        $this->dropColumn('{{%company_categories}}', 'meta_description_other');
        $this->dropColumn('{{%company_categories}}', 'meta_keywords_other');
        $this->dropColumn('{{%company_categories}}', 'title_other');

        $this->dropColumn('{{%news_categories}}', 'meta_title_other');
        $this->dropColumn('{{%news_categories}}', 'meta_description_other');
        $this->dropColumn('{{%news_categories}}', 'meta_keywords_other');
        $this->dropColumn('{{%news_categories}}', 'title_other');

        $this->dropColumn('{{%trade_categories}}', 'meta_title_other');
        $this->dropColumn('{{%trade_categories}}', 'meta_description_other');
        $this->dropColumn('{{%trade_categories}}', 'meta_keywords_other');
        $this->dropColumn('{{%trade_categories}}', 'title_other');
    }
}
