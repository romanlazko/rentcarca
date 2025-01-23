<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitModel extends Command
{
    public static $expectation = 'await_model';

    public static $pattern = '/^await_model$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = $updates->getMessage()?->getText();

        $this->getConversation()->update([
            'model' => $text
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}