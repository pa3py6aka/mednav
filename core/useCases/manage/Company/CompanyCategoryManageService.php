<?php

namespace core\useCases\manage\Company;


use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryRegion;
use core\forms\manage\Company\CompanyCategoryRegionForm;
use core\forms\manage\Company\CompanyCategoryForm;
use core\repositories\Company\CompanyCategoryRepository;
use core\services\TransactionManager;

class CompanyCategoryManageService
{
    private $repository;
    private $transaction;

    public function __construct(CompanyCategoryRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(CompanyCategoryForm $form): CompanyCategory
    {
        $category = CompanyCategory::create
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

    public function edit($id, CompanyCategoryForm $form): void
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
                $categoryRegion = CompanyCategoryRegion::fastCreate($categoryId, $id);
                $this->repository->saveRegion($categoryRegion);
            }
        });
    }

    public function saveRegion($categoryId, $regionId, CompanyCategoryRegionForm $form): void
    {
        if (!$categoryRegion = $this->repository->getRegion($categoryId, $regionId)) {
            $categoryRegion = CompanyCategoryRegion::fastCreate($categoryId, $regionId);
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