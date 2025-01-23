<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitTransmission extends Command
{
    public static $command = 'await_transmission';

    public static $usage = ['await_transmission'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'transmission' => $updates->getInlineData()->getTransmission()
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}