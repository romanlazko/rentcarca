<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Romanlazko\Telegram\Models\TelegramChat;

class Announcement extends Model
{
    protected $guarded = [];

    protected $casts = [
        'photos' => 'array',
        'status' => Status::class,
        'features' => 'array'
    ];

    public function telegram_chat()
    {
        return $this->belongsTo(TelegramChat::class);
    }
}
