<?php

/*
  +----------------------------------------------------------------------+
  | The PECL website                                                     |
  +----------------------------------------------------------------------+
  | Copyright (c) 1999-2019 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | https://php.net/license/3_01.txt                                     |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Authors: Stig S. Bakken <ssb@fast.no>                                |
  |          Tomas V.V.Cox <cox@php.net>                                 |
  |          Martin Jansen <mj@php.net>                                  |
  |          Gregory Beaver <cellog@php.net>                             |
  |          Richard Heyes <richard@php.net>                             |
  +----------------------------------------------------------------------+
*/

namespace App\Entity;

use App\Database;

/**
 * User entity class.
 */
class User
{
    public ?string $password = null;
    private bool $admin = false;
    public bool $registered = false;

    private array $schema = [
        'handle',
        'password',
        'name',
        'email',
        'homepage',
        'created',
        'createdby',
        'lastlogin',
        'showemail',
        'registered',
        'admin',
        'userinfo',
        'pgpkeyid',
        'pgpkey',
        'wishlist',
        'longitude',
        'latitude',
        'active',
        'from_site',
    ];

    private array $values = [];

    private array $newValues = [];

    /**
     * Class constructor.
     */
    public function __construct(
        private readonly Database $database,
        public $handle
    ) {
        $row = $this->database->run("SELECT * FROM users WHERE handle = ?", [$this->handle])->fetch();

        if ($row) {
            foreach ($this->schema as $field) {
                $this->values[$field] = $row[$field];
            }

            $this->password = $row['password'];
            $this->admin = (int)$row['admin'] === 1;
            $this->registered = (int)$row['registered'] === 1;
        }
    }

    /**
     * Check if user's username matches.
     */
    public function is($handle): bool
    {
        return strtolower($handle) == strtolower($this->handle);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->admin === true;
    }

    /**
     * Setter.
     */
    public function set($column, $value)
    {
        if (!$this->validateColumn($column)) {
            return false;
        }

        $this->newValues[$column] = $value;
    }

    /**
     * Getter.
     */
    public function get($column)
    {
        return $this->values[$column] ?? null;
    }

    /**
     * Is column valid.
     */
    private function validateColumn($column): bool
    {
        return in_array($column, $this->schema);
    }

    /**
     * Save data to database.
     */
    public function save()
    {
        $elements = [];
        $arguments = [];

        foreach ($this->newValues as $column => $value) {
            if (in_array($column, ['handle', 'admin'])) {
                continue;
            }

            if (!in_array($column, $this->schema)) {
                continue;
            }

            $elements[] = $column.' = ?';
            $arguments[] = $value;
        }

        $sql = 'UPDATE users SET '.implode(', ', $elements).' WHERE handle = ?;';
        $arguments[] = $this->handle;

        return $this->database->run($sql, $arguments);
    }
}
