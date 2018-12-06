<?php

namespace core\useCases;


use core\entities\Page;
use core\forms\PageForm;
use core\repositories\PageRepository;

class PageService
{
    private $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($type, PageForm $form): Page
    {
        $page = Page::create(
            $type,
            $form->name,
            $form->content,
            $form->metaTitle,
            $form->metaDescription,
            $form->metaKeywords,
            $form->slug ?: $form->name
        );

        $this->repository->save($page);
        return $page;
    }

    public function edit($id, PageForm $form): void
    {
        $page = $this->repository->get($id);
        $page->edit(
            $form->name,
            $form->content,
            $form->metaTitle,
            $form->metaDescription,
            $form->metaKeywords,
            $form->slug
        );
        $this->repository->save($page);
    }

    public function massRemove(array $ids): int
    {
        return $this->repository->massRemove($ids);
    }

    public function remove($id): void
    {
        $page = $this->repository->get($id);
        $this->repository->remove($page);
    }
}