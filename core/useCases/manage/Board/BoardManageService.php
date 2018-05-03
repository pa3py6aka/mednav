<?php

namespace core\useCases\manage\Board;


use core\entities\Board\Board;
use core\entities\Board\BoardParameterAssignment;
use core\entities\Board\BoardTag;
use core\entities\Board\BoardTagAssignment;
use core\forms\manage\Board\BoardCreateForm;
use core\forms\manage\Board\BoardParameterForm;
use core\repositories\Board\BoardRepository;
use core\services\TransactionManager;
use Yii;

class BoardManageService
{
    private $repository;
    private $transaction;

    public function __construct(BoardRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(BoardCreateForm $form): Board
    {
        $board = Board::create(
            $form->authorId ?: Yii::$app->user->id,
            $form->name,
            $form->slug,
            $form->categoryId,
            $form->title,
            $form->description,
            $form->keywords,
            $form->note,
            $form->price,
            $form->currency,
            $form->priceFrom,
            $form->fullText,
            $form->termId,
            $form->geoId
        );
        $board->updateActiveUntil();

        $this->transaction->wrap(function () use ($form, $board) {
            $this->repository->save($board);
            $this->saveTags($board, $form->tags);
            $this->saveParameters($board, $form->params);
            $photoService = Yii::createObject(BoardPhotoService::class);
            $photoService->savePhotos($board->id, $form->photos);
        });

        return $board;
    }

    private function saveTags(Board $board, string $tags): void
    {
        $tags = explode(',', $tags);
        BoardTagAssignment::deleteAll(['board_id' => $board->id]);
        foreach ($tags as $tag) {
            $tag = trim($tag);
            if (strlen($tag) > 2) {
                if (!$tagEntity = BoardTag::find()->where(['name' => $tag])->limit(1)->one()) {
                    $tagEntity = BoardTag::create($tag);
                    if (!$tagEntity->save()) {
                        throw new \DomainException('Ошибка при сохранении тега');
                    }
                }
                if (!$assignment = $this->repository->findTagAssignment($board->id, $tagEntity->id)) {
                    $assignment = BoardTagAssignment::create($board->id, $tagEntity->id);
                    $this->repository->saveTagAssignment($assignment);
                }
            }
        }
    }

    private function saveParameters(Board $board, $parameters): void
    {
        BoardParameterAssignment::deleteAll(['board_id' => $board->id]);
        if (!is_array($parameters)) {
            return;
        }
        foreach ($parameters as $id => $value) {
            $param = $this->repository->getParameter($id);
            $assignment = BoardParameterAssignment::create($board->id, $param->id, $param->type, $value);
            $this->repository->saveParameterAssignment($assignment);
        }
    }

    public function edit($id, BoardParameterForm $form): void
    {
        $parameter = $this->repository->get($id);
        $parameter->edit($form->name, $form->type, $form->active);
        $this->repository->save($parameter);

    }

    public function remove($id, $safe = true): void
    {
        $board = $this->repository->get($id);
        if ($safe) {
            $this->repository->safeRemove($board);
        } else {
            $this->repository->remove($board);
        }
    }
}