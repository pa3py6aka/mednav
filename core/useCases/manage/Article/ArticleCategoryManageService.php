<?php

namespace core\useCases\manage\Article;


use core\entities\Article\ArticleCategory;
use core\forms\manage\Article\ArticleCategoryForm;
use core\repositories\Article\ArticleCategoryRepository;
use core\services\TransactionManager;

class ArticleCategoryManageService
{
    private $repository;
    private $transaction;

    public function __construct(ArticleCategoryRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(ArticleCategoryForm $form): ArticleCategory
    {
        $category = ArticleCategory::create
        (
            $form->name,
            $form->contextName,
            $form->enabled,
            $form->notShowOnMain,
            $form->childrenOnlyParent,
            $form->slug,
            $form->metaTitle,
            $form->metaDescription,
            $form->metaKeywords,
            $form->title,
            $form->descriptionTop,
            $form->descriptionTopOn,
            $form->descriptionBottom,
            $form->descriptionBottomOn,
            $form->metaTitleItem ?: '',
            $form->metaDescriptionItem ?: '',
            $form->metaTitleOther,
            $form->metaDescriptionOther,
            $form->metaKeywordsOther,
            $form->titleOther,
            $form->pagination,
            $form->active
        );

        if ($form->parentId) {
            $parent = $this->repository->get($form->parentId);
            $category->appendTo($parent);
        } else {
            $category->makeRoot();
        }

        $this->repository->save($category);

        return $category;
    }

    public function edit($id, ArticleCategoryForm $form): void
    {
        $category = $this->repository->get($id);
        $category->edit
        (
            $form->name,
            $form->contextName,
            $form->enabled,
            $form->notShowOnMain,
            $form->childrenOnlyParent,
            $form->slug,
            $form->metaTitle,
            $form->metaDescription,
            $form->metaKeywords,
            $form->title,
            $form->descriptionTop,
            $form->descriptionTopOn,
            $form->descriptionBottom,
            $form->descriptionBottomOn,
            $form->metaTitleItem,
            $form->metaDescriptionItem,
            $form->metaTitleOther,
            $form->metaDescriptionOther,
            $form->metaKeywordsOther,
            $form->titleOther,
            $form->pagination,
            $form->active
        );

        $currentParentId = $category->parent ? $category->parent->id : '';
        if ($form->parentId != $currentParentId) {
            if ($form->parentId) {
                $parent = $this->repository->get($form->parentId);
                $category->appendTo($parent);
            } else {
                $category->makeRoot();
            }
        }

        $this->repository->save($category);
    }

    public function remove($id): void
    {
        $category = $this->repository->get($id);
        $this->repository->remove($category);
    }
}