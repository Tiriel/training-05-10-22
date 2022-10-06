<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieVoter extends Voter
{
    public const VIEW = 'movie.view';
    public const EDIT = 'movie.edit';

    public function __construct(
        private readonly AuthorizationCheckerInterface $checker
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        assert($subject instanceof Movie);
        if ($subject->getRated() === 'G') {
            return true;
        }

        $user = $token->getUser();
        $admin = false;
        if (!$user instanceof User && !$admin = $this->checker->isGranted('ROLE_ADMIN')) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $admin === true || $this->checkView($subject, $user),
            self::EDIT => $admin === true || $this->checkEdit($subject, $user),
            default => false
        };
    }

    private function checkView(Movie $movie, User $user): bool
    {
        if (!$user->getBirthday()) {
            return false;
        }

        $age = $user->getBirthday()->diff(new \DateTimeImmutable())->y;
        return match ($movie->getRated()) {
            'PG', 'PG-13' => $age >= 13,
            'R', 'NC-17' => $age >= 17,
            default => false
        };
    }

    private function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkView($movie, $user) && $user === $movie->getCreatedBy();
    }
}