<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitFeatures extends Command
{
    public static $command = 'await_features';

    public static $usage = ['await_features'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $features = array_filter(explode(':', $updates->getInlineData()->getFeatures()));

        $this->getConversation()->update([
            'features' => !empty($features) ? $features : ['0']
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}