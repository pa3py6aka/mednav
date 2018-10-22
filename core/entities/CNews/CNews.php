<?php

namespace core\entities\CNews;

use core\entities\Article\queries\ArticlesQuery;
use core\entities\Article\common\ArticleCommon;
use core\entities\CategoryAssignmentInterface;
use core\entities\Company\Company;
use core\entities\StatusesTrait;
use core\entities\User\User;
use yii\db\ActiveQuery;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%cnews}}".
 *
 * @property CNewsPhoto[] $photos
 * @property CNewsTagsAssignment[] $cNewsTagsAssignments
 * @property CNewsTag[] $tags
 * @property CNewsCategory $category
 * @property CNewsPhoto $mainPhoto
 */
class CNews extends ArticleCommon implements CategoryAssignmentInterface
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
    ): CNews
    {
        $article = new CNews();
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
        return Url::to(['/cnews/cnews/view', 'id' => $this->id, 'slug' => $this->slug]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cnews}}';
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(CNewsPhoto::class, ['cnews_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(CNewsPhoto::class, ['id' => 'main_photo_id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getCNewsTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(CNewsTagsAssignment::class, ['cnews_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(CNewsTag::class, ['id' => 'tag_id'])->viaTable('{{%cnews_tags_assignment}}', ['cnews_id' => 'id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(CNewsCategory::class, ['id' => 'category_id']);
    }
}
