<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Features extends Command
{
    public static $command = 'features';

    public static $title = [
        'ru' => 'Особенности',
        'en' => 'Features',
    ];

    public static $usage = ['features'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = [];

        foreach (array_filter($this->features) as $key => $value) {
            $buttons[] = [array($value, Features::$command, $key)];
        }

        $buttons = BotApi::inlineCheckbox([
            ...$buttons,
            [array("Подтвердить", AwaitFeatures::$command, '')],
            [array(Back::getTitle('ru'), Back::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ], 'features');

        $text = implode("\n", [
            "*Дополнительные функции:*"."\n",
            ">Укажите важные особенности автомобиля\. Можно указать сразу несколько",
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