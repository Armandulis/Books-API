<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserService
 */
class UserService
{
    /**
     * UserService constructor
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository              $userRepository,
    )
    {
    }

    /**
     * Check if a user exists by email
     * @param string $email The email address to check
     * @return bool
     */
    public function existsByEmail(string $email): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        return $user !== null;
    }

    /**
     * Create a new user
     * @param User $user The user to be created
     * @param string $plaintextPassword The plain text password for the user
     * @return void
     */
    public function createUser(User $user, string $plaintextPassword): void
    {
        // Hash password, then save hashed password in the database
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
    }
}
