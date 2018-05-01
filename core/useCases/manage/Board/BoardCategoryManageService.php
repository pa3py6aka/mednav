<?php

namespace core\useCases\manage\Board;


use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryParameter;
use core\entities\Board\BoardCategoryRegion;
use core\forms\manage\Board\BoardCategoryForm;
use core\forms\manage\Board\BoardCategoryRegionForm;
use core\repositories\Board\BoardCategoryRepository;
use core\services\TransactionManager;

class BoardCategoryManageService
{
    private $repository;
    private $transaction;

    public function __construct(BoardCategoryRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(BoardCategoryForm $form): BoardCategory
    {
        $category = BoardCategory::create
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
            $form->pagination,
            $form->active
        );

        if ($form->parentId) {
            $parent = $this->repository->get($form->parentId);
            $category->appendTo($parent);
        } else {
            $category->makeRoot();
        }

        $this->transaction->wrap(function () use ($category, $form) {
            $this->repository->save($category);
            foreach ($form->parameters as $parameter) {
                $categoryParameter = BoardCategoryParameter::create($category->id, $parameter);
                $this->repository->saveParameter($categoryParameter);
            }
        });

        return $category;
    }

    public function edit($id, BoardCategoryForm $form): void
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

        $this->transaction->wrap(function () use ($category, $form) {
            $this->repository->save($category);
            BoardCategoryParameter::deleteAll(['category_id' => $category->id]);
            foreach ($form->parameters as $parameter) {
                $categoryParameter = BoardCategoryParameter::create($category->id, $parameter);
                $this->repository->saveParameter($categoryParameter);
            }
        });
    }

    public function attachRegions($categoryId, array $regionIds): void
    {
        $this->transaction->wrap(function () use ($categoryId, $regionIds) {
            $this->repository->clearAttachedRegions($categoryId);
            foreach ($regionIds as $id) {
                $categoryRegion = BoardCategoryRegion::fastCreate($categoryId, $id);
                $this->repository->saveRegion($categoryRegion);
            }
        });
    }

    public function saveRegion($categoryId, $regionId, BoardCategoryRegionForm $form): void
    {
        if (!$categoryRegion = $this->repository->getRegion($categoryId, $regionId)) {
            $categoryRegion = BoardCategoryRegion::fastCreate($categoryId, $regionId);
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