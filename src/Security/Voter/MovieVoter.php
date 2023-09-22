<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieVoter extends Voter
{
    public const VIEW = 'movie.view';
    public const EDIT = 'movie.edit';
    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (\in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return true;
        }

        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        assert($user instanceof User);

        return match ($attribute) {
            self::VIEW => $this->checkView($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };
    }

    public function checkView(Movie $movie, User $user): bool
    {
        if ('G' === $movie->getRated()) {
            return true;
        }

        $age = $user->getBirthday()?->diff(new \DateTimeImmutable())->y ?? null;

        return match ($movie->getRated()) {
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17,
            default => false,
        };
    }

    public function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkView($movie, $user)
            && $movie->getCreatedBy() === $user;
    }
}
