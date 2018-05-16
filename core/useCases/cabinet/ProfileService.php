<?php

namespace core\useCases\cabinet;


use core\entities\User\User;
use core\forms\User\UserProfileForm;
use core\repositories\UserRepository;

class ProfileService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function editProfile(User $user, UserProfileForm $form): void
    {
        $user->email = $form->email;
        $user->last_name = $form->last_name;
        $user->name = $form->name;
        $user->patronymic = $form->patronymic;
        $user->gender = $form->gender;
        $user->birthday = date('Y-m-d', strtotime($form->birthday));
        $user->phone = $form->phone;
        $user->site = $form->site;
        $user->skype = $form->skype;
        $user->organization = $form->organization;
        $user->geo_id = $form->geoId;

        $this->repository->save($user);
    }

    public function editUserType(User $user, $type): void
    {
        if (!in_array($type, array_keys(User::getTypesArray()))) {
            throw new \DomainException('Такого типа не существует');
        }

        $user->setType($type);
        $this->repository->save($user);
    }

    public function updatePassword(User $user, $password): void
    {
        $user->setPassword($password);
        $user->password_reset_token = null;
        $this->repository->save($user);
    }
}