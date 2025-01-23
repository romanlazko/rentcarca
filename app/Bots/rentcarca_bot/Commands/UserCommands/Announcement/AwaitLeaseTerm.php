<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitLeaseTerm extends Command
{
    public static $expectation = 'await_lease_term';

    public static $pattern = '/^await_lease_term$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = $updates->getMessage()?->getText();

        $this->getConversation()->update([
            'lease_term' => $text
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}