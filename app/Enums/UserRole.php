<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum UserRole: int
{
    case ADMINISTRATOR = 1;
    case STAFF = 2;
    case MEMBER = 3;

    public function isAdmin(): bool
    {
        return $this == self::ADMINISTRATOR;
    }

    public function isStaff(): bool
    {
        return $this == self::ADMINISTRATOR;
    }

    public function isMember(): bool
    {
        return $this == self::ADMINISTRATOR;
    }

    public function getText(): string
    {
        return match ($this) {
            self::ADMINISTRATOR => "Administrator",
            self::STAFF => "Staff",
            self::MEMBER => "Member",
        };
    }

    public static function find(string $roleName): UserRole
    {
        return match ($roleName) {
            "Administrator" => self::ADMINISTRATOR,
            "Staff" => self::STAFF,
            "Member" => self::MEMBER,
        };
    }

    public static function findById(int $id): UserRole
    {
        return match ($id) {
            self::ADMINISTRATOR->value => self::ADMINISTRATOR,
            self::STAFF->value => self::STAFF,
            self::MEMBER->value => self::MEMBER,
        };
    }

    public static function getRoles(): Collection
    {
        return collect([
            self::ADMINISTRATOR->value => self::ADMINISTRATOR->getText(),
            self::STAFF->value => self::STAFF->getText(),
            self::MEMBER->value => self::MEMBER->getText(),
        ]);
    }
}
