<?php

namespace App\Enums;

enum StoreStatusEnum: int
{
    case Active = 1;
    case Inactive = 0;

    public function label(): string
    {
        return match ($this) {
            StoreStatusEnum::Active => 'Active',
            StoreStatusEnum::Inactive => 'Inactive',
        };
    }

    public function color(): string
    {
        return match ($this) {
            StoreStatusEnum::Active => 'success',
            StoreStatusEnum::Inactive => 'danger',
        };
    }
}