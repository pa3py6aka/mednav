<?php

namespace core\entities\News;

use core\entities\PhotoInterface;
use core\entities\PhotoTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%article_photos}}".
 *
 * @property int $id
 * @property int $news_id
 * @property string $file
 * @property int $sort
 *
 * @property News $news
 * @property News[] $newss
 */
class NewsPhoto extends ActiveRecord implements PhotoInterface
{
    use PhotoTrait;

    public static function create($newsId, $file, $sort): NewsPhoto
    {
        $photo = new static();
        $photo->news_id = $newsId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    public static function getRelationAttribute(): string
    {
        return 'news_id';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news_photos}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'news_id' => 'News ID',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }

    public function getNews(): ActiveQuery
    {
        return $this->hasOne(News::class, ['id' => 'news_id']);
    }

    public function getNewss(): ActiveQuery
    {
        return $this->hasMany(News::class, ['main_photo_id' => 'id']);
    }
}
