<?php

namespace core\useCases\manage\Trade;


use core\entities\Trade\TradeCategory;
use core\entities\Trade\TradeCategoryRegion;
use core\forms\manage\Trade\TradeCategoryRegionForm;
use core\forms\manage\Trade\TradeCategoryForm;
use core\repositories\Trade\TradeCategoryRepository;
use core\services\TransactionManager;

class TradeCategoryManageService
{
    private $repository;
    private $transaction;

    public function __construct(TradeCategoryRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(TradeCategoryForm $form): TradeCategory
    {
        $category = TradeCategory::create
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

    public function edit($id, TradeCategoryForm $form): void
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

    public function attachRegions($categoryId, array $regionIds): void
    {
        $this->transaction->wrap(function () use ($categoryId, $regionIds) {
            $this->repository->clearAttachedRegions($categoryId);
            foreach ($regionIds as $id) {
                $categoryRegion = TradeCategoryRegion::fastCreate($categoryId, $id);
                $this->repository->saveRegion($categoryRegion);
            }
        });
    }

    public function saveRegion($categoryId, $regionId, TradeCategoryRegionForm $form): void
    {
        if (!$categoryRegion = $this->repository->getRegion($categoryId, $regionId)) {
            $categoryRegion = TradeCategoryRegion::fastCreate($categoryId, $regionId);
        }

        $categoryRegion->edit(
            $form->metaTitle,
            $form->metaDescription,
            $form->metaKeywords,
            $form->title,
            $form->descriptionTop,
            $form->descriptionTopOn,
            $form->descriptionBottom,
            $form->descriptionBottomOn
        );

        $this->repository->saveRegion($categoryRegion);
    }

    public function remove($id): void
    {
        $category = $this->repository->get($id);
        $this->repository->remove($category);
    }
}