<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Database\Explorer;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nette\Utils\Validators;

/**
 * Users management.
 */
final class UserFacade implements Nette\Security\Authenticator {
    use Nette\SmartObject;

    public const PasswordMinLength = 7;

    private Explorer $database;
    private Passwords $passwords;

    public function __construct(Explorer $database, Passwords $passwords) {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    /**
     * Performs an authentication.
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(string $username, string $password): SimpleIdentity {
        $row = $this->database->table('users')
            ->where('user_id', $username)
            ->fetch();

        if (!$row) {
            throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
        } elseif (!$this->passwords->verify($password, $row['password'])) {
            throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
        } elseif ($this->passwords->needsRehash($row['password'])) {
            $row->update([
                'password' => $this->passwords->hash($password),
            ]);
        }

        $data = $row->toArray();
        unset($data['password']);
        return new SimpleIdentity($row->id, '', $data);
    }

    /**
     * Adds new user.
     * @throws DuplicateNameException
     */
    public function add(string $user_id, string $email, string $password): void {
        Validators::assert($email, 'email');
        try {
            $this->database->table('users')->insert([
                'user_id' => $user_id,
                'email' => $email,
                'password' => $this->passwords->hash($password),
            ]);
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicateNameException;
        }
    }
}

class DuplicateNameException extends \Exception {
}
