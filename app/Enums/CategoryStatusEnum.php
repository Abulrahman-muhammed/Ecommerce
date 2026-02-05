<?php

namespace App\Enums;

enum CategoryStatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function label(): string
    {
        return match ($this) {
            CategoryStatusEnum::ACTIVE => 'Active',
            CategoryStatusEnum::INACTIVE => 'Inactive',
        };
    }
    // get values of enum
    public static function getValues(): array
    {
        return array_column(CategoryStatusEnum::cases(), 'value');
    }
    public function color(): string
    {
        return match ($this) {
            CategoryStatusEnum::ACTIVE => 'success',
            CategoryStatusEnum::INACTIVE => 'danger',
        };
    }
}