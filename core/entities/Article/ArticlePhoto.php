<?php

namespace core\entities\Article;

use core\entities\PhotoInterface;
use core\entities\PhotoTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%article_photos}}".
 *
 * @property int $id
 * @property int $article_id
 * @property string $file
 * @property int $sort
 *
 * @property Article $article
 * @property Article[] $articles
 */
class ArticlePhoto extends ActiveRecord implements PhotoInterface
{
    use PhotoTrait;

    public static function create($articleId, $file, $sort): ArticlePhoto
    {
        $photo = new static();
        $photo->article_id = $articleId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    public static function getRelationAttribute(): string
    {
        return 'article_id';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_photos}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['article_id', 'file'], 'required'],
            [['article_id', 'sort'], 'integer'],
            [['file'], 'string', 'max' => 255],
            [['file'], 'unique'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::className(), 'targetAttribute' => ['article_id' => 'id']],
        ];
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }

    public function getArticle(): ActiveQuery
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }

    public function getArticles(): ActiveQuery
    {
        return $this->hasMany(Article::class, ['main_photo_id' => 'id']);
    }
}
