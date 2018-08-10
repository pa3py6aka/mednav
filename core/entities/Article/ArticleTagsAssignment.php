<?php

namespace core\entities\Article;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%article_tags_assignment}}".
 *
 * @property int $article_id
 * @property int $tag_id
 *
 * @property Article $article
 * @property ArticleTag $tag
 */
class ArticleTagsAssignment extends ActiveRecord
{
    public static function create($articleId, $tagId): ArticleTagsAssignment
    {
        $assignment = new static();
        $assignment->article_id = $articleId;
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_tags_assignment}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['article_id', 'tag_id'], 'required'],
            [['article_id', 'tag_id'], 'integer'],
            [['article_id', 'tag_id'], 'unique', 'targetAttribute' => ['article_id', 'tag_id']],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleTags::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getArticle(): ActiveQuery
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(ArticleTag::class, ['id' => 'tag_id']);
    }
}
