<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitPrice extends Command
{
    public static $expectation = 'await_price';

    public static $pattern = '/^await_price$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $text = $updates->getMessage()?->getText();

        $this->getConversation()->update([
            'price' => $text
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}