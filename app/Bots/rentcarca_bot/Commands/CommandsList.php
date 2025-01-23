<?php
namespace App\Bots\rentcarca_bot\Commands;

use Romanlazko\Telegram\App\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static protected $commands = [
        'admin'     => [
            AdminCommands\MenuCommand::class,
            AdminCommands\StartCommand::class,
            AdminCommands\HelpCommand::class,

            AdminCommands\Announcements::class,
            AdminCommands\ShowAnnouncement::class,
            AdminCommands\ConfirmMessage::class,
            AdminCommands\Paid::class,
            AdminCommands\Publish::class,
            AdminCommands\Reject::class,

        ],
        'user'      => [
            UserCommands\StartCommand::class,
            UserCommands\MenuCommand::class,
            UserCommands\HelpCommand::class,
            UserCommands\CreateAnnouncement::class,
            UserCommands\Rulles::class,

            
            UserCommands\Announcement\Steps::class,
            UserCommands\Announcement\Back::class,
            UserCommands\Announcement\StepBack::class,
            UserCommands\Announcement\Clear::class,

            UserCommands\Announcement\Model::class,
            UserCommands\Announcement\AwaitModel::class,

            UserCommands\Announcement\City::class,
            UserCommands\Announcement\AwaitCity::class,

            UserCommands\Announcement\BodyType::class,
            UserCommands\Announcement\AwaitBodyType::class,

            UserCommands\Announcement\Transmission::class,
            UserCommands\Announcement\AwaitTransmission::class,

            UserCommands\Announcement\DriveType::class,
            UserCommands\Announcement\AwaitDriveType::class,

            UserCommands\Announcement\FuelType::class,
            UserCommands\Announcement\AwaitFuelType::class,

            UserCommands\Announcement\Mileage::class,
            UserCommands\Announcement\AwaitMileage::class,

            UserCommands\Announcement\Features::class,
            UserCommands\Announcement\AwaitFeatures::class,

            UserCommands\Announcement\Price::class,
            UserCommands\Announcement\AwaitPrice::class,

            UserCommands\Announcement\Deposit::class,
            UserCommands\Announcement\AwaitDeposit::class,

            UserCommands\Announcement\LeaseTerm::class,
            UserCommands\Announcement\AwaitLeaseTerm::class,

            UserCommands\Announcement\Photo::class,
            UserCommands\Announcement\AwaitPhoto::class,

            UserCommands\Announcement\Preview::class,
            UserCommands\Announcement\ConfirmMessage::class,

            UserCommands\Announcement\Edit::class,
            UserCommands\Announcement\Store::class,

            UserCommands\MyAnnouncements::class,
            UserCommands\ShowAnnouncement::class,
            UserCommands\Pay::class,
            UserCommands\Paid::class
        ],
        'supergroup' => [
        ],
        'default'   => [
        ]
        
    ];

    static public function getCommandsList(?string $auth)
    {
        return array_merge(
            (self::$commands[$auth] ?? []), 
            (self::$default_commands[$auth] ?? [])
        ) 
        ?? self::getCommandsList('default');
    }

    static public function getAllCommandsList()
    {
        foreach (self::$commands as $auth => $commands) {
            $commands_list[$auth] = self::getCommandsList($auth);
        }
        return $commands_list;
    }
}
