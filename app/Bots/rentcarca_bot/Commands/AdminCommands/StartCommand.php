<?php 

namespace App\Bots\rentcarca_bot\Commands\AdminCommands;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class StartCommand extends Command
{
    public static $command = 'start';

    public static $usage = ['/start', 'start'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        return $this->bot->executeCommand(MenuCommand::$command);
    }
}
