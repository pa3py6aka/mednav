<?php

namespace core\useCases\manage\Board;


use core\entities\Board\BoardParameter;
use core\entities\Board\BoardParameterOption;
use core\forms\manage\Board\BoardParameterForm;
use core\forms\manage\Board\BoardParameterOptionForm;
use core\repositories\Board\BoardParameterRepository;

class BoardParameterManageService
{
    private $repository;

    public function __construct(BoardParameterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(BoardParameterForm $form): BoardParameter
    {
        $parameter = BoardParameter::create($form->name, $form->type, $form->active);
        $parameter->sort = BoardParameter::find()->max('sort') + 1;
        $this->repository->save($parameter);

        return $parameter;
    }

    public function edit($id, BoardParameterForm $form): void
    {
        $parameter = $this->repository->get($id);
        $parameter->edit($form->name, $form->type, $form->active);
        $this->repository->save($parameter);
    }

    public function remove($id): void
    {
        $parameter = $this->repository->get($id);
        if ($parameter->id == 1) {
            throw new \DomainException("Системный параметр нельзя удалять.");
        }
        $this->repository->remove($parameter);
    }

    public function createOption($parameterId, BoardParameterOptionForm $form): BoardParameterOption
    {
        $option = BoardParameterOption::create($parameterId, $form->name, $form->slug);
        $option->sort = BoardParameterOption::find()->max('sort') + 1;
        $this->repository->saveOption($option);

        return $option;
    }

    public function editOption($id, BoardParameterOptionForm $form): void
    {
        $option = $this->repository->getOption($id);
        $option->edit($form->name, $form->slug);
        $this->repository->saveOption($option);
    }

    public function removeOption($id): void
    {
        $option = $this->repository->getOption($id);
        $this->repository->removeOption($option);
    }
}