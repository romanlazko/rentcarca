<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Rulles extends Command
{
    public static $command = 'rulles';

    public static $title = [
        'ru' => '📜 Правила',
        'en' => '📜 Rulles'
    ];

    public static $usage = ['/rulles', 'rulles'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "C правилами можно ознакомиться [тут](https://t.me/rentcarca_support)."
        ]);

        return BotApi::returnInline([
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}