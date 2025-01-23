<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class BodyType extends Command
{
    public static $command = 'body_type';

    public static $title = [
        'ru' => 'Тип кузова',
        'en' => 'Body type',
    ];

    public static $usage = ['body_type'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = [];

        foreach (array_filter($this->body_types) as $key => $value) {
            $buttons[] = [array($value, AwaitBodyType::$command, $key)];
        }

        $buttons = BotApi::inlineKeyboard([
            ...$buttons,
            [
                array(Back::getTitle('ru'), Back::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ],
        ], 'body_type');

        $text = implode("\n", [
            "*Выберите тип кузова автомобиля:*"
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