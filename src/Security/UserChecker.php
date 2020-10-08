<?php

namespace App\Security;

use App\Entity\Users as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->getStatus()->getName()==\App\Entity\Statuses::STATUS_DISABLED) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Twoje konto jest nieaktywne.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
//        if (!$user instanceof AppUser) {
//            return;
//        }
//
//        if ($user->getStatus()->getName()==\App\Entity\Statuses::STATUS_CHANGE_PASSWORD_REQUEST) {
//            throw new AccountExpiredException('Musisz zmienić hasło');
//        }
    }
}