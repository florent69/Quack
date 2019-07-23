<?php

namespace App\Security\Voter;

use App\Entity\Quack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class QuackVoter extends Voter
{

    const EDIT = 'edit';
    const NEW = 'new';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::NEW, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Quack) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'edit':
                return in_array('ROLE_ADMIN', $user->getRoles()) || $user->getId() == $subject->getAuteur()->getId() ;
                break;
            case 'new':
                return in_array('ROLE_ADMIN', $user->getRoles());
                break;

            case 'delete':
                return in_array('ROLE_ADMIN', $user->getRoles()) || $user->getId() == $subject->getAuteur()->getId() ;
                break;

        }

        return false;
    }
}
