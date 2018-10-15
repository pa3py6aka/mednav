<?php

namespace core\useCases\manage\Brand;


use core\entities\Brand\BrandCategory;
use core\forms\manage\Brand\BrandCategoryForm;
use core\repositories\Brand\BrandCategoryRepository;
use core\services\TransactionManager;

class BrandCategoryManageService
{
    private $repository;
    private $transaction;

    public function __construct(BrandCategoryRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(BrandCategoryForm $form): BrandCategory
    {
        $category = BrandCategory::create
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

        if ($form->parentId) {
            $parent = $this->repository->get($form->parentId);
            $category->appendTo($parent);
        } else {
            $category->makeRoot();
        }

        $this->repository->save($category);

        return $category;
    }

    public function edit($id, BrandCategoryForm $form): void
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