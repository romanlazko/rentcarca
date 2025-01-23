<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Mileage extends Command
{
    public static $command = 'mileage';

    public static $title = [
        'ru' => 'Пробег',
        'en' => 'Mileage',
    ];

    public static $usage = ['mileage'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitMileage::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [
                array(Back::getTitle('ru'), Back::$command, ''),
                array("Подтвердить", AwaitFeatures::$command, '')
            ],
        ]);

        $text = implode("\n", [
            "*Укажите пробег автомобиля в милях:*"."\n",
            ">Максимально 10 символов, только цифры"."\n\n",
            "_Пример: 281000_"
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