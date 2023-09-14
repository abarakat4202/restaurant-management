<?php

namespace App\Modules\Ingredient\Enums;


enum Unit: string
{
    case Gram = 'g';
    case kilogram = 'kg';

    public function toString(): string
    {
        return match($this)
        {
            
        };        
    }
}
