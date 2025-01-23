<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Deposit extends Command
{
    public static $command = 'deposit';

    public static $title = [
        'ru' => 'Депозит',
        'en' => 'Deposit',
    ];

    public static $usage = ['deposit'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitDeposit::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array(Back::getTitle('ru'), Back::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "*Укажите размер депозита:*"."\n",
            ">Если депозит не требуется укажите '\-'"
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