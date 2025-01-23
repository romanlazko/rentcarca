<?php 

namespace App\Bots\rentcarca_bot\Commands\AdminCommands;

use App\Enums\Status;
use App\Models\Announcement;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Announcements extends Command
{
    public static $command = 'announcemens';

    public static $title = [
        'ru' => 'Объявления',
        'en' => 'Announcements'
    ];

    public static $usage = ['/announcemens', 'announcemens'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $count = Announcement::whereIn('status', [Status::paid, Status::created])->count();
        $announcements = Announcement::whereIn('status', [Status::paid, Status::created])->limit(10)->get();

        $buttons = BotApi::inlineKeyboard([
            ...$announcements->map(function ($announcement) {
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
                return [array($icon($announcement->status).$announcement->model, ShowAnnouncement::$command, $announcement->id)];
            }),
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'announcement_id');

        $text = implode("\n", [
            "Все объявления: {$count}"
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}