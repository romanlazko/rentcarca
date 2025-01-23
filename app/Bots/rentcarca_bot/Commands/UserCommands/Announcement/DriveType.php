<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class DriveType extends Command
{
    public static $command = 'drive_type';

    public static $title = [
        'ru' => 'Тип привода',
        'en' => 'Drive type',
    ];

    public static $usage = ['drive_type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = [];

        foreach ($this->drive_types as $key => $value) {
            $buttons[] = [array($value, AwaitDriveType::$command, $key)];
        }

        $buttons = BotApi::inlineKeyboard([
            ...$buttons,
            [
                array(Back::getTitle('ru'), Back::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ],
        ], 'drive_type');

        $text = implode("\n", [
            "*Выберите тип привода:*"
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