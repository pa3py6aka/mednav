<?php

namespace core\useCases\manage\Board;


use core\components\Settings;
use core\entities\Board\Board;
use core\entities\Board\BoardParameterAssignment;
use core\entities\Board\BoardTag;
use core\entities\Board\BoardTagAssignment;
use core\forms\manage\Board\BoardManageForm;
use core\forms\manage\PhotosForm;
use core\helpers\MarkHelper;
use core\repositories\Board\BoardRepository;
use core\services\TransactionManager;
use Yii;
use yii\helpers\StringHelper;

class BoardManageService
{
    private $repository;
    private $transaction;

    public function __construct(BoardRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function create(BoardManageForm $form): Board
    {
        $userId = in_array($form->scenario, [BoardManageForm::SCENARIO_USER_EDIT, BoardManageForm::SCENARIO_USER_CREATE])
            ? Yii::$app->user->id
            : ($form->authorId ?: Yii::$app->user->id);
        $status = in_array($form->scenario, [BoardManageForm::SCENARIO_USER_EDIT, BoardManageForm::SCENARIO_USER_CREATE])
            ? (Yii::$app->settings->get(Settings::BOARD_MODERATION) ? Board::STATUS_ON_MODERATION : Board::STATUS_ACTIVE)
            : Board::STATUS_ACTIVE;

        $board = Board::create(
            $userId,
            $form->name,
            $form->slug,
            $form->categoryId,
            $form->title ?: '',
            $form->description ?: '',
            $form->keywords ?: trim($form->tags),
            $form->note,
            $form->price,
            $form->currency,
            $form->priceFrom,
            $form->fullText,
            $form->termId,
            $form->geoId,
            $status
        );

        if ($status === Board::STATUS_ACTIVE) {
            $board->extend();
        } else {
            $board->active_until = time();
        }

        $this->transaction->wrap(function () use ($form, $board) {
            $this->repository->save($board);
            $this->saveTags($board, $form->tags);
            $this->saveParameters($board, $form->params);
            $this->updateColumns($board);
            Yii::createObject(BoardPhotoService::class)->savePhotosFromTempFolder($board, $form->photos);
            $this->repository->save($board);
        });

        return $board;
    }

    public function edit($id, BoardManageForm $form): void
    {
        $board = $this->repository->get($id);
        $userId = in_array($form->scenario, [BoardManageForm::SCENARIO_USER_EDIT, BoardManageForm::SCENARIO_USER_CREATE])
            ? $board->author_id
            : ($form->authorId ?: $board->author_id);

        $board->edit(
            $userId,
            $form->name,
            $form->slug,
            $form->categoryId,
            $form->title,
            $form->description,
            $form->keywords ?: trim($form->tags),
            $form->note,
            $form->price,
            $form->currency,
            $form->priceFrom,
            $form->fullText,
            $form->geoId
        );

        if ($form->scenario === BoardManageForm::SCENARIO_USER_EDIT && Yii::$app->settings->get(Settings::BOARD_MODERATION)) {
            $board->setStatus(Board::STATUS_ON_MODERATION);
        }

        $this->transaction->wrap(function () use ($form, $board) {
            $this->saveTags($board, $form->tags);
            $this->saveParameters($board, $form->params);
            $this->updateColumns($board);
            $this->repository->save($board);
        });
    }

    private function saveTags(Board $board, string $tags): void
    {
        $tags = StringHelper::explode($tags, ',', true, true);
        BoardTagAssignment::deleteAll(['board_id' => $board->id]);
        foreach ($tags as $tag) {
            if (strlen($tag) > 2) {
                if (!$tagEntity = BoardTag::find()->where(['name' => $tag])->limit(1)->one()) {
                    $tagEntity = BoardTag::create($tag);
                    if (!$tagEntity->save()) {
                        throw new \DomainException('Ошибка при сохранении тега');
                    }
                }
                $assignment = BoardTagAssignment::create($board->id, $tagEntity->id);
                $this->repository->saveTagAssignment($assignment);
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

    private function updateColumns(Board $board): void
    {
        if (empty($board->title)) {
            $board->title = MarkHelper::generateStringByMarks($board->category->meta_title_item, MarkHelper::MARKS_BOARD, $board);
        }
        if (empty($board->description)) {
            $board->description = MarkHelper::generateStringByMarks($board->category->meta_description_item, MarkHelper::MARKS_BOARD, $board);
        }
    }

    public function addPhotos($id, PhotosForm $form): bool
    {
        $board = $this->repository->get($id);
        $num = $board->getPhotos()->count();
        $ok = false;
        foreach ($form->files as $file) {
            if ($num > 9) {
                Yii::$app->session->setFlash("error", "Фото {$file->name} не загружено, превышен лимит кол-ва фотографий в объявлении.");
                break;
            }
            Yii::createObject(BoardPhotoService::class)->addPhoto($board, $file);
            $num++;
            $ok = true;
        }
        $this->repository->save($board);
        return $ok;
    }

    public function extend($ids, $termId = null): void
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        foreach ($ids as $id) {
            $board = $this->repository->get($id);
            $board->extend($termId);
            $this->repository->save($board);
        }
    }

    public function publish($ids): void
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        foreach ($ids as $id) {
            $board = $this->repository->get($id);
            $board->extend();
            $this->repository->save($board);
        }
    }

    public function archive(Board $board): void
    {
        $board->setStatus(Board::STATUS_ARCHIVE);
        $this->repository->save($board);
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        return $this->repository->massRemove($ids, $hardRemove);
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