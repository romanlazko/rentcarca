<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class MenuCommand extends Command
{
    public static $command = '/menu';

    public static $title = [
        'ru' => '🏠 Главное меню',
        'en' => '🏠 Menu'
    ];

    public static $usage = ['/menu', 'menu', 'Главное меню', 'Menu'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(CreateAnnouncement::getTitle('ru'), CreateAnnouncement::$command, '')],
            [array(MyAnnouncements::getTitle('ru'), MyAnnouncements::$command, '')],
            [array(Rulles::getTitle('ru'), Rulles::$command, '')],
            [array(HelpCommand::getTitle('ru'), HelpCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Добро пожаловать в бот по аренде автомобилей! 👋🏻"."\n",

            "Я помогу тебе легко создать объявление о сдаче твоей машины в аренду в канале RentCarCa"
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}