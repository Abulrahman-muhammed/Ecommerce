<?php 
namespace App\Enums;
enum OrderStatusEnum: int
{
    case PENDING = 0;
    case COMPLETED = 1;
    case CANCELLED = 2;
    case PROCESSING = 3;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::PROCESSING => 'Processing',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
            self::PROCESSING => 'primary',
        };
    }
}