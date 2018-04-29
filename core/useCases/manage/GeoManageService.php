<?php

namespace core\useCases\manage;


use core\entities\Geo;
use core\forms\manage\Geo\GeoForm;
use core\repositories\GeoRepository;

class GeoManageService
{
    private $repository;

    public function __construct(GeoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(GeoForm $form): Geo
    {
        $parent = $this->repository->get($form->parentId);
        $geo = Geo::create(
            $form->name,
            $form->name_p,
            $form->slug,
            $form->popular,
            $form->active
        );
        $geo->appendTo($parent);
        $this->repository->save($geo);

        return $geo;
    }

    public function edit($id, GeoForm $form): void
    {
        $geo = $this->repository->get($id);
        $this->assertIsNotRoot($geo);
        $geo->edit(
            $form->name,
            $form->name_p,
            $form->slug,
            $form->popular,
            $form->active
        );

        if ($form->parentId != $geo->parent->id) {
            $parent = $this->repository->get($form->parentId);
            $geo->appendTo($parent);
        }

        $this->repository->save($geo);
    }

    public function remove($id): void
    {
        $geo = $this->repository->get($id);
        $this->assertIsNotRoot($geo);
        $this->repository->remove($geo);
    }

    private function assertIsNotRoot(Geo $geo): void
    {
        if ($geo->isRoot()) {
            throw new \DomainException('Нельзя редактировать системную запись.');
        }
    }
}