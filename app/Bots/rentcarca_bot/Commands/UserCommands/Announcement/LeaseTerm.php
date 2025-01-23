<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class LeaseTerm extends Command
{
    public static $command = 'lease_term';

    public static $title = [
        'ru' => 'Срок аренды',
        'en' => 'Lease term',
    ];

    public static $usage = ['lease_term'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitLeaseTerm::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array(Back::getTitle('ru'), Back::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "*Минимальный срок аренды:*"."\n",
            ">Укажите минимальный срок аренды автомобиля, в днях, неделях или месяцах\."."\n\n",
            "_Пример: *3 дня, 1 неделя, 3 месяца*_"
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