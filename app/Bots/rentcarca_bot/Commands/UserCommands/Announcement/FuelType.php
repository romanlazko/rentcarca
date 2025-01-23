<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class FuelType extends Command
{
    public static $command = 'fuel_type';

    public static $title = [
        'ru' => 'Тип топлива',
        'en' => 'Fuel type',
    ];

    public static $usage = ['fuel_type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = [];

        foreach ($this->fuel_types as $key => $value) {
            $buttons[] = [array($value, AwaitFuelType::$command, $key)];
        }

        $buttons = BotApi::inlineKeyboard([
            ...$buttons,
            [
                array(Back::getTitle('ru'), Back::$command, ''),
                array("Подтвердить", AwaitFeatures::$command, '')
            ],
        ], 'fuel_type');

        $text = implode("\n", [
            "*Выберите тип топлива:*"
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