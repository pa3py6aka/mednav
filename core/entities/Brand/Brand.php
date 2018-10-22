<?php

namespace core\entities\Brand;


use core\entities\Article\common\ArticleCommon;
use core\entities\CategoryAssignmentInterface;
use core\entities\StatusesTrait;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%articles}}".
 *
 * @property BrandPhoto[] $photos
 * @property BrandTagsAssignment[] $brandTagsAssignments
 * @property BrandTag[] $tags
 * @property BrandCategory $category
 * @property BrandPhoto $mainPhoto
 */
class Brand extends ArticleCommon implements CategoryAssignmentInterface
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
        $status
    ): Brand
    {
        $article = new Brand();
        $article->user_id = $userId;
        $article->company_id = $companyId;
        $article->category_id = $categoryId;
        $article->title = $title;
        $article->meta_description = $metaDescription;
        $article->meta_keywords = $metaKeywords;
        $article->name = $name;
        $article->intro = $intro;
        $article->full_text = $fullText;
        $article->indirect_links = $indirectLinks;
        $article->status = $status;
        $article->views = 0;
        $article->userSlug = $slug;
        return $article;
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
        $indirectLinks
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
    }

    public function getUrl()
    {
        return Url::to(['/brand/brand/view', 'id' => $this->id, 'slug' => $this->slug]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%brands}}';
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(BrandPhoto::class, ['brand_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(BrandPhoto::class, ['id' => 'main_photo_id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getNewsTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(BrandTagsAssignment::class, ['brand_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(BrandTag::class, ['id' => 'tag_id'])->viaTable('{{%brand_tags_assignment}}', ['brand_id' => 'id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(BrandCategory::class, ['id' => 'category_id']);
    }
}
