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

namespace App;

use App\Utils\DsnConverter;

/**
 * Configuration handler class.
 */
class Config
{
    /**
     * Class constructor.
     */
    public function __construct(
        private array $values
    ) {
        $this->mapDsn();
    }

    /**
     * Map DSN string to database configuration if it has been defined. String
     * db_dsn "mysqli://user:password@host/database" is mapped to db_*
     * configuration values.
     */
    private function mapDsn(): void
    {
        if (empty($this->values['db_dsn'])) {
            return;
        }

        $dsnConverter = new DsnConverter();

        $array = $dsnConverter->toArray($this->values['db_dsn']);

        $this->values['db_username'] = $array['username'];
        $this->values['db_password'] = $array['password'];
        $this->values['db_host'] = $array['host'];
        $this->values['db_name'] = $array['database'];
    }

    /**
     * Get configuration value by key.
     */
    public function get($key)
    {
        return $this->values[$key] ?? '';
    }
}
