<?php
namespace App\Enums;

enum PaymentMethodEnum: string
{
    case CASH_ON_DELIVERY = 'cash_on_delivery';
    case CREDIT_CARD = 'credit_card';

    public function label(): string
    {
        return match($this) {
            self::CASH_ON_DELIVERY => 'Cash on Delivery',
            self::CREDIT_CARD => 'Credit Card',
        };
    }
    // color for badge
    public function color(): string
    {
        return match($this) {
            self::CASH_ON_DELIVERY => 'secondary',
            self::CREDIT_CARD => 'primary',
        };
    }

}
