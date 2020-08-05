<?php


namespace App\Domain\Interfaces;

use App\User;
interface IUsersRepository
{
    static function getById(int $id);
    static function getByExtId(string $externalId);
    static function create(string $email, string $name, string $provider, string $externalId);
    static function current();
    static function personalSettings();
}
