<?php

namespace core\entities\Expo;


use core\entities\Article\common\ArticleCommon;
use core\entities\CategoryAssignmentInterface;
use core\entities\StatusesTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%expositions}}".
 *
 * @property bool $show_dates [tinyint(1)]
 * @property int $start_date [int(11) unsigned]
 * @property int $end_date [int(11) unsigned]
 * @property string $city [varchar(255)]
 *
 * @property ExpoPhoto[] $photos
 * @property ExpoTagsAssignment[] $expoTagsAssignments
 * @property ExpoTag[] $tags
 * @property ExpoCategory $category
 * @property ExpoPhoto $mainPhoto
 */
class Expo extends ArticleCommon implements CategoryAssignmentInterface
{
    use StatusesTrait;

    public static function create(
        $userId,
        $companyId,
        $categoryId,
        $title,
        $metaDescription,
        $metaKeywords,
        $name,
        $slug,
        $intro,
        $fullText,
        $indirectLinks,
        $status,
        $showDates,
        $startDate,
        $endDate,
        $city
    ): Expo
    {
        $expo = new Expo();
        $expo->user_id = $userId;
        $expo->company_id = $companyId;
        $expo->category_id = $categoryId;
        $expo->title = $title;
        $expo->meta_description = $metaDescription;
        $expo->meta_keywords = $metaKeywords;
        $expo->name = $name;
        $expo->intro = $intro;
        $expo->full_text = $fullText;
        $expo->indirect_links = $indirectLinks;
        $expo->status = $status;
        $expo->views = 0;
        $expo->userSlug = $slug;
        $expo->show_dates = $showDates;
        $expo->start_date = $startDate;
        $expo->end_date = $endDate;
        $expo->city = $city;
        return $expo;
    }

    public function edit(
        $userId,
        $companyId,
        $categoryId,
        $title,
        $metaDescription,
        $metaKeywords,
        $name,
        $slug,
        $intro,
        $fullText,
        $indirectLinks,
        $showDates,
        $startDate,
        $endDate,
        $city
    ): void
    {
        $this->user_id = $userId;
        $this->company_id = $companyId;
        $this->category_id = $categoryId;
        $this->title = $title;
        $this->meta_description = $metaDescription;
        $this->meta_keywords = $metaKeywords;
        $this->name = $name;
        $this->intro = $intro;
        $this->full_text = $fullText;
        $this->indirect_links = $indirectLinks;
        $this->userSlug = $slug;
        $this->show_dates = $showDates;
        $this->start_date = $startDate;
        $this->end_date = $endDate;
        $this->city = $city;
    }

    public function getUrl(): string
    {
        return Url::to(['/expo/expo/view', 'id' => $this->id, 'slug' => $this->slug]);
    }

    public function getCity(): string
    {
        return Html::encode($this->city);
    }

    public function getCalendar(): string
    {
        return "с " . Yii::$app->formatter->asDate($this->start_date) . " по " . Yii::$app->formatter->asDate($this->end_date);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%expositions}}';
    }

    public function attributeLabels($attributeLabels = []): array
    {
        $attributeLabels = [
            'show_dates' => 'Выводить календарь',
            'start_date' => 'Начало выставки',
            'end_date' => 'Окончание выставки',
            'city' => 'Город',
        ];
        return parent::attributeLabels($attributeLabels);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(ExpoPhoto::class, ['exposition_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(ExpoPhoto::class, ['id' => 'main_photo_id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getExpoTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(ExpoTagsAssignment::class, ['exposition_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(ExpoTag::class, ['id' => 'tag_id'])->viaTable('{{%exposition_tags_assignment}}', ['exposition_id' => 'id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(ExpoCategory::class, ['id' => 'category_id']);
    }
}
