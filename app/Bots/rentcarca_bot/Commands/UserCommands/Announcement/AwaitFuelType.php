<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitFuelType extends Command
{
    public static $command = 'await_fuel_type';

    public static $usage = ['await_fuel_type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->update([
            'fuel_type' => $updates->getInlineData()->getFuelType()
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}