<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookVoter extends Voter
{
    public const VIEW = 'book.view';

    public function __construct(private AuthorizationCheckerInterface $checker)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Book
            && self::VIEW === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User && !$this->checker->isGranted('ROLE_ADMIN')) {
            return false;
        }

        /** @var Book $subject */
        return $user === $subject->getCreatedBy() || $this->checker->isGranted('ROLE_ADMIN');
    }
}