<?php 
namespace App\Enums;
enum PaymentStatusEnum: int
{
    case PENDING = 0;
    case COMPLETED = 1;
    case CANCELLED = 2;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
    // color for badge
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}