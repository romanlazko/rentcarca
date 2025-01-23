<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Transmission extends Command
{
    public static $command = 'transmission';

    public static $title = [
        'ru' => 'Трансмиссия',
        'en' => 'Transmission',
    ];

    public static $usage = ['transmission'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = [];

        foreach ($this->transmissions as $key => $value) {
            $buttons[] = [array($value, AwaitTransmission::$command, $key)];
        }

        $buttons = BotApi::inlineKeyboard([
            ...$buttons,
            [array(Back::getTitle('ru'), Back::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'transmission');

        $text = implode("\n", [
            "*Выберите тип коробки передач:*"
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