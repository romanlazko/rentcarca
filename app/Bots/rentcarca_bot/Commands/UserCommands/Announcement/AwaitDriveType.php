<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitDriveType extends Command
{
    public static $command = 'await_drive_type';

    public static $usage = ['await_drive_type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'drive_type' => $updates->getInlineData()->getDriveType()
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}