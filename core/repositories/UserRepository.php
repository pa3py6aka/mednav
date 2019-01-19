<?php

namespace core\repositories;


use core\entities\Article\Article;
use core\entities\Board\Board;
use core\entities\Brand\Brand;
use core\entities\CNews\CNews;
use core\entities\Company\Company;
use core\entities\Expo\Expo;
use core\entities\News\News;
use core\entities\Trade\Trade;
use core\entities\User\User;
use yii\base\BaseObject;
use yii\web\NotFoundHttpException;

class UserRepository
{
    public function findByEmail($email): ?User
    {
        return User::find()->where(['email' => $email])->limit(1)->one();
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    public function get($id): User
    {
        return $this->getBy(['id' => $id]);
    }

    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail($email): User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken($token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function safeRemove(User $user): void
    {
        $user->updateStatus(User::STATUS_DELETED);
        if (!$user->save()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
        //$this->blockUserItems($user->id);
    }

    public function massRemove(array $ids, $hardRemove = false): int
    {
        if ($hardRemove) {
            return User::deleteAll(['id' => $ids]);
        }
        return User::updateAll(['status' => User::STATUS_DELETED], ['id' => $ids]);
        //$this->blockUserItems($ids);
        //return $rows;
    }

    /**
     * Деактивация всего контента пользователя(лей) при его(их) безопасном удалении
     *
     * @param int|array $ids
     */
    private function blockUserItems($ids): void
    {
        //Этот метод перенесён в БД, там автоматом срабатывает триггер и вызывается соответствующая процедура


        /*Board::updateAll(['status' => Board::STATUS_OWNER_USER_DELETED], ['author_id' => $ids ,'status' => Board::ST]);
        Article::updateAll(['status' => Article::STATUS_OWNER_USER_DELETED], ['user_id' => $ids]);
        Brand::updateAll(['status' => Brand::STATUS_OWNER_USER_DELETED], ['user_id' => $ids]);
        CNews::updateAll(['status' => CNews::STATUS_OWNER_USER_DELETED], ['user_id' => $ids]);
        Company::updateAll(['status' => Company::STATUS_OWNER_USER_DELETED], ['user_id' => $ids]);
        Expo::updateAll(['status' => Expo::STATUS_OWNER_USER_DELETED], ['user_id' => $ids]);
        News::updateAll(['status' => News::STATUS_OWNER_USER_DELETED], ['user_id' => $ids]);
        Trade::updateAll(['status' => Trade::STATUS_OWNER_USER_DELETED], ['user_id' => $ids]);*/
    }

    private function getBy(array $condition): User
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundHttpException('User not found.');
        }
        return $user;
    }

}