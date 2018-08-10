<?php

namespace core\entities\Article;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "{{%article_tags}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @property ArticleTagsAssignment[] $articleTagsAssignments
 * @property Article[] $articles
 */
class ArticleTag extends ActiveRecord
{
    public static function create($name): ArticleTag
    {
        $tag = new static();
        $tag->name = $name;
        return $tag;
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => Slug::class,
                'slugAttribute' => 'slug',
                'attribute' => 'name',
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_tags}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['slug'], 'unique'],
        ];
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
        ];
    }

    public function getArticleTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(ArticleTagsAssignment::class, ['tag_id' => 'id']);
    }

    public function getArticles(): ActiveQuery
    {
        return $this->hasMany(Article::class, ['id' => 'article_id'])->viaTable('{{%article_tags_assignment}}', ['tag_id' => 'id']);
    }
}
