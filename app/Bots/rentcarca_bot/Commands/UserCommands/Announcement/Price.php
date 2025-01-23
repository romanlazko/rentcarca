<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Price extends Command
{
    public static $command = 'price';

    public static $title = [
        'ru' => 'Цена',
        'en' => 'Price',
    ];

    public static $usage = ['price'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitPrice::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [
                array(Back::getTitle('ru'), Back::$command, ''),
                array("Подтвердить", AwaitFeatures::$command, '')
            ],
        ]);

        $text = implode("\n", [
            "*Укажите тарифы аренды за день, неделю или месяц:*"."\n",
            ">Укажите стоимость в $\. Можно указать несколько вариантов"."\n\n",
            "_Пример: 50$ в день, 300$ в неделю, 1000$ в месяц_"
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