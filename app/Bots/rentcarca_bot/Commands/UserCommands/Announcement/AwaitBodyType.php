<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitBodyType extends Command
{
    public static $command = 'await_body_type';

    public static $usage = ['await_body_type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'body_type' => $updates->getInlineData()->getBodyType()
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}