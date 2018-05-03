<?php

namespace core\entities\Board;

use core\entities\Currency;
use core\entities\Geo;
use core\entities\User\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%boards}}".
 *
 * @property int $id
 * @property int $author_id
 * @property string $name
 * @property string $slug
 * @property int $category_id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $note
 * @property int $price
 * @property int $currency_id
 * @property int $price_from
 * @property string $full_text
 * @property int $term_id
 * @property int $geo_id
 * @property int $status
 * @property int $active_until
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $author
 * @property BoardCategory $category
 * @property Geo $geo
 * @property BoardTerm $term
 * @property Currency $currency
 */
class Board extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ON_MODERATION = 1;
    const STATUS_NOT_ACTIVE = 3;
    const STATUS_ACTIVE = 5;
    const STATUS_ARCHIVE = 8;

    public static function tableName(): string
    {
        return '{{%boards}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc

    public function rules()
    {
        return [
            [['author_id', 'name', 'slug', 'category_id', 'title', 'currency', 'term_id', 'geo_id', 'active_until', 'created_at', 'updated_at'], 'required'],
            [['author_id', 'category_id', 'price', 'currency', 'term_id', 'geo_id', 'status', 'active_until', 'created_at', 'updated_at'], 'integer'],
            [['description', 'keywords', 'full_text'], 'string'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['note'], 'string', 'max' => 100],
            [['price_from'], 'string', 'max' => 1],
            [['slug'], 'unique'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BoardCategory::class, 'targetAttribute' => ['category_id' => 'id']],
            [['geo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geo::class, 'targetAttribute' => ['geo_id' => 'id']],
        ];
    }*/

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'category_id' => 'Category ID',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'note' => 'Note',
            'price' => 'Price',
            'currency' => 'Ден. единица',
            'price_from' => 'Price From',
            'full_text' => 'Full Text',
            'term_id' => 'Term ID',
            'geo_id' => 'Geo ID',
            'status' => 'Status',
            'active_until' => 'Active Until',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(BoardCategory::class, ['id' => 'category_id']);
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }

    public function getTerm()
    {
        return $this->hasOne(BoardTerm::class, ['id' => 'term_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }
}
