<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands;

use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\BodyType;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\City;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Clear;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Deposit;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\DriveType;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Features;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\FuelType;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\LeaseTerm;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Mileage;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Model;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Photo;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Preview;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Price;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Steps;
use App\Bots\rentcarca_bot\Commands\UserCommands\Announcement\Transmission;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CreateAnnouncement extends Command
{
    public static $command = 'create_announcement';

    public static $title = [
        'ru' => '🏎 Создать объявление',
        'en' => '🏎 Create announcement',
    ];

    public static $usage = ['/create_announcement', 'create_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        if (!empty($this->getConversation()->notes)) {
            $buttons = BotApi::inlineKeyboard([
                [array("👉 Да, продолжить", Steps::$command, '')],
                [array(Clear::getTitle('ru'), Clear::$command, '')],
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ]);

            $text = implode("\n", [
                "*У Вас есть незаполненное объявление*"."\n",
                "Продолжить заполнять объявление?",
            ]);

            return BotApi::returnInline([
                'text' => $text,
                'chat_id'       =>  $updates->getChat()->getId(),
                'reply_markup'  =>  $buttons,
                'parse_mode'    =>  'Markdown',
                'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            ]);
        }

        return $this->bot->executeCommand(Steps::$command);
    }
}