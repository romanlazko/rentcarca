<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Back extends Command
{
    public static $command = 'back';

    public static $usage = ['/back', 'back'];

    public static $title = [
        'ru' => "🔙 Назад",
        'en' => "🔙 Back",
    ];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $steps = [
            'photos' => Photo::$command,
            'lease_term' => LeaseTerm::$command,
            'deposit' => Deposit::$command,
            'price' => Price::$command,
            'features' => Features::$command,
            'mileage' => Mileage::$command,
            'fuel_type' => FuelType::$command,
            'drive_type' => DriveType::$command,
            'transmission' => Transmission::$command,
            'body_type' => BodyType::$command,
            'model' => Model::$command,
            'city' => City::$command,
        ];

        $notes = $this->getConversation()->notes;

        foreach ($steps as $step => $command) {
            if (!isset($notes[$step]) OR empty($notes[$step])) {
                $this->bot->executeCommand(StepBack::$command);
            }
        }

        return $this->bot->executeCommand(Edit::$command);
    }
}