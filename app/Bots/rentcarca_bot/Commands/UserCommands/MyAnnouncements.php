<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands;

use App\Enums\Status;
use App\Models\Announcement;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class MyAnnouncements extends Command
{
    public static $command = 'my_announcements';

    public static $title = [
        'ru' => '🔄 Мои объявления',
        'en' => '🔄 My announcements'
    ];

    public static $usage = ['/my_announcements', 'my_announcements'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $announcements = Announcement::where('telegram_chat_id', $telegram_chat->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $buttons = BotApi::inlineKeyboard([
            ...$announcements
                ->map(function (Announcement $announcement) {
                    $icon = function ($status) {
                        return match ($status) {
                            Status::created => '⏳',
                            Status::published => '✅',
                            Status::approved => '💵',
                            Status::rejected => '🚫',
                            Status::paid => '💰',
                            default => ''
                        };
                    };
                    return [array($icon($announcement->status) . $announcement->model, ShowAnnouncement::$command, $announcement->id)];
                })
                ->toArray(),
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'announcement_id');

        return BotApi::returnInline([
            'text'          =>  "Мои объявления:",
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}