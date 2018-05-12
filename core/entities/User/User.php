<?php

namespace core\entities\User;

use core\components\Settings;
use core\entities\User\queries\UserQuery;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int $id
 * @property string $email
 * @property int $type
 * @property int $company_id [int(11)]
 * @property int $last_online
 * @property int $status
 *
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token [varchar(255)]
 * @property string $email_confirm_token [varchar(255)]
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $statusName
 * @property string $typeName
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_WAIT = 1;
    const STATUS_ON_PREMODERATION = 5;
    const STATUS_ACTIVE = 10;

    const TYPE_USER = 1;
    const TYPE_COMPANY = 2;

    public static function create($email, $password, $type): User
    {
        $user = new self();
        $user->email = $email;
        $user->type = $type;
        $user->last_online = time();
        $user->status = self::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();
        return $user;
    }

    public static function requestSignup($email, $password, $type): User
    {
        $user = new self();

        $status = self::STATUS_ACTIVE;
        if (Yii::$app->settings->get(Settings::USER_EMAIL_ACTIVATION)) {
            $status = self::STATUS_WAIT;
            $user->email_confirm_token = Yii::$app->security->generateRandomString();
        } else if (Yii::$app->settings->get(Settings::USER_PREMODERATION)) {
            $status = self::STATUS_ON_PREMODERATION;
        }
        $user->status = $status;

        $user->email = $email;
        $user->type = $type;
        $user->last_online = time();
        $user->setPassword($password);
        $user->generateAuthKey();
        return $user;
    }

    public function edit($email, $password, $type): void
    {
        $this->email = $email;
        if ($password) {
            $this->setPassword($password);
        }
        $this->type = $type;
    }

    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('E-mail уже подтверждён.');
        }

        if (Yii::$app->settings->get(Settings::USER_PREMODERATION)) {
            $this->status = self::STATUS_ON_PREMODERATION;
        } else {
            $this->status = self::STATUS_ACTIVE;
        }

        $this->email_confirm_token = null;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isOnModeration(): bool
    {
        return $this->status === self::STATUS_ON_PREMODERATION;
    }

    public function isDeleted(): bool
    {
        return $this->status === self::STATUS_DELETED;
    }

    public function updateStatus($status): void
    {
        $this->status = $status;
    }

    public static function getStatusesArray(): array
    {
        return [
            self::STATUS_DELETED => 'Удалён',
            self::STATUS_WAIT => 'Ожидает email-подтверждения',
            self::STATUS_ON_PREMODERATION => 'На премодерации',
            self::STATUS_ACTIVE => 'Активен',
        ];
    }

    public function getStatusName(): string
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    public static function getTypesArray(): array
    {
        return [
            self::TYPE_USER => 'Пользователь',
            self::TYPE_COMPANY => 'Компания',
        ];
    }

    public function getTypeName(): string
    {
        return ArrayHelper::getValue(self::getTypesArray(), $this->type);
    }

    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Запрос на смену пароля уже был отправлен ранее.');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Запрос на сброс пароля не был инициирован.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    public function afterFind()
    {
        $this->last_online = Yii::$app->redis->get('online-' . $this->id) ?: null;
        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function findIdentity($id): ?User
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null): void
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByPasswordResetToken($token): ?User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc

    public function rules()
    {
        return [
            [['email', 'type', 'last_online', 'auth_key', 'password_hash', 'created_at', 'updated_at'], 'required'],
            [['type', 'last_online', 'status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    } */

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail',
            'type' => 'Тип',
            'typeName' => 'Тип',
            'last_online' => 'Последний вход',
            'status' => 'Статус',
            'statusName' => 'Статус',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'created_at' => 'Добавлен',
            'updated_at' => 'Обновлён',
        ];
    }

    public static function find(): UserQuery
    {
        return new \core\entities\User\queries\UserQuery(get_called_class());
    }
}
