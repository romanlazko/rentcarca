<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class ConfirmMessage extends Command
{
    public static $command = 'confirm';

    public static $title = [
        'ru' => 'Предпросмотр объявления',
        'en' => 'Preview',
    ];

    public static $usage = ['confirm'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(Store::getTitle('ru'), Store::$command, '')],
            [array(Edit::getTitle('ru'), Edit::$command, '')],
            [
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ],
        ]);

        try {
            BotApi::deleteMessage([
                'chat_id'       => $updates->getChat()->getId(),
                'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        

        return BotApi::sendMessage([
            'text'          => "Так будет выглядеть твое объявление." ."\n\n". "*Публикуем?*", 
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => 'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            'reply_markup'  => $buttons
        ]);
    }
}