<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Steps extends Command
{
    public static $command = 'steps';

    public static $usage = ['/steps', 'steps'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $steps = [
            'city' => City::$command,
            'model' => Model::$command,
            'body_type' => BodyType::$command,
            'transmission' => Transmission::$command,
            'drive_type' => DriveType::$command,
            'fuel_type' => FuelType::$command,
            'mileage' => Mileage::$command,
            'features' => Features::$command,
            'price' => Price::$command,
            'deposit' => Deposit::$command,
            'lease_term' => LeaseTerm::$command,
            'photos' => Photo::$command,
        ];

        $notes = $this->getConversation()->notes;

        foreach ($steps as $step => $command) {
            if (!isset($notes[$step]) OR empty($notes[$step])) {
                return $this->bot->executeCommand($command);
            }
        }

        return $this->bot->executeCommand(Preview::$command);
    }
}