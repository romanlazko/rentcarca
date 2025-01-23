<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Model extends Command
{
    public static $command = 'model';

    public static $title = [
        'ru' => 'Модель',
        'en' => 'Model',
    ];

    public static $usage = ['model'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitModel::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [
                array(Back::getTitle('ru'), Back::$command, ''),
                array("Подтвердить", AwaitFeatures::$command, '')
            ],
        ]);

        $text = implode("\n", [
            "*Введите марку, модель и год выпуска автомобиля:*"."\n",
            ">Максимально 50 символов, без использования эмодзи"."\n\n",
            "_Примеры: Toyota Camry 2020, BMW X5 xDrive 2019, Mercedes\-Benz E\-Class 2021_"
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