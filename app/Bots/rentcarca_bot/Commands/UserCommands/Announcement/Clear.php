<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Clear extends Command
{
    public static $command = 'clear';

    public static $title = [
        'ru' => '🚫 Очистить и начать заново',
        'en' => '🚫 Clear and start over',
    ];

    public static $usage = ['clear'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->clear();

        return $this->bot->executeCommand(Steps::$command);
    }
}