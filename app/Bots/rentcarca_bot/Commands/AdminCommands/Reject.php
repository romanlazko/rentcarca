<?php 

namespace App\Bots\rentcarca_bot\Commands\AdminCommands;

use App\Models\Announcement;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use App\Enums\Status;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Reject extends Command
{
    public static $command = 'reject';

    public static $title = [
        'ru' => '🚫 Отклонить',
        'en' => '🚫 Reject'
    ];

    public static $usage = ['/reject', 'reject'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement_id = $updates->getInlineData()->getAnnouncementId();

        Announcement::find($announcement_id)->update([
            'status' => Status::rejected
        ]);

        return $this->bot->executeCommand(Announcements::$command);
    }
}