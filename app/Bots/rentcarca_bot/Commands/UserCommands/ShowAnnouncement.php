<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands;

use App\Models\Announcement;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use App\Enums\Status;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class ShowAnnouncement extends Command
{
    public static $command = 'show_announcement';

    public static $title = [
        'ru' => 'Мои объявления',
        'en' => 'My announcements'
    ];

    public static $usage = ['/show_announcement', 'show_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement_id = $updates->getInlineData()->getAnnouncementId();

        $announcement = Announcement::find($announcement_id);

        $buttons = BotApi::inlineKeyboard([
            $announcement->status == Status::created ? [array(Pay::getTitle('ru'), Pay::$command, '')] : [],
            [array("🔙 Назад", MyAnnouncements::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

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

        $text = implode("\n", [
            $this->announcementText($announcement)."\n",
            "Статус: {$icon($announcement->status)} ".$this->status_translations[$announcement->status->name]
        ]);

        return BotApi::returnInline([
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}