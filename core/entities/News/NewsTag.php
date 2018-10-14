<?php

namespace core\entities\News;

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
 * @property NewsTagsAssignment[] $newsTagsAssignments
 * @property News[] $news
 */
class NewsTag extends ActiveRecord
{
    public static function create($name): NewsTag
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
        return '{{%news_tags}}';
    }

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

    public function getNewsTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(NewsTagsAssignment::class, ['tag_id' => 'id']);
    }

    public function getNews(): ActiveQuery
    {
        return $this->hasMany(News::class, ['id' => 'news_id'])->viaTable('{{%news_tags_assignment}}', ['tag_id' => 'id']);
    }
}
