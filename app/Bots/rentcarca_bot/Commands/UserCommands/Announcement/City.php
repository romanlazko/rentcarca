<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class City extends Command
{
    public static $command = 'city';

    public static $title = [
        'ru' => 'Город',
        'en' => 'City',
    ];

    public static $usage = ['city'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitCity::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array(Back::getTitle('ru'), Back::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "*Введите город аренды:*"."\n",
            ">Напишите название города на английском языке, слитно"."\n\n",
            "_Примеры: LosAngeles, SanFrancisco, SanDiego, Sacramento, SanJose_"
        ]);

        $data = [
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'MarkdownV2',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}