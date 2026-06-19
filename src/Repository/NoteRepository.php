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
  | Authors: Peter Kokot <petk@php.net>                                  |
  +----------------------------------------------------------------------+
*/

namespace App\Repository;

use App\Database;

/**
 * Repository class for retrieving user notes.
 */
readonly class NoteRepository
{
    /**
     * Class constructor.
     */
    public function __construct(
        private Database $database
    ) {}

    /**
     * Get all notes by given username.
     */
    public function getNotesByUser($user): array
    {
        $sql = 'SELECT id, nby, ntime, note FROM notes WHERE uid = :uid ORDER BY ntime';

        $statement = $this->database->run($sql, [$user]);

        return $statement->fetchAll();
    }
}
