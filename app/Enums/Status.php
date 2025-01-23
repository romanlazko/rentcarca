<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\App;

enum Status: int 
{
    case created = 1;
    case rejected = 2;
    case approved = 3;
    case published = 4;
    case sold = 5;
    case disabled = 6;
    case paid = 7;

}