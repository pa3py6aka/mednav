<?php

namespace core\entities\News;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%article_tags_assignment}}".
 *
 * @property int $news_id
 * @property int $tag_id
 *
 * @property News $news
 * @property NewsTag $tag
 */
class NewsTagsAssignment extends ActiveRecord
{
    public static function create($newsId, $tagId): NewsTagsAssignment
    {
        $assignment = new static();
        $assignment->news_id = $newsId;
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news_tags_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'news_id' => 'News ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getNews(): ActiveQuery
    {
        return $this->hasOne(News::class, ['id' => 'news_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(NewsTag::class, ['id' => 'tag_id']);
    }
}
