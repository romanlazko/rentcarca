<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class HelpCommand extends Command
{
    public static $command = '/help';

    public static $title = [
        'ru' => '❓ Помощь',
        'en' => '❓ Help'
    ];

    public static $usage = ['/help', 'help', 'Помощь', 'Help'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "*Это список доступных комманд:*"."\n",
            "/menu - 🏠 Главное меню,",
            "/start - 🏁 Старт бот."."\n",
            "*Контакты:*"."\n",
            "Администратор канала и бота - *@rentcarca_support*",
            "Разработчик бота - *@roman_lazko*" 
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