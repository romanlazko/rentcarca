<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Photo extends Command
{
    public static $command = 'photo';

    public static $title = '';

    public static $usage = ['photo'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->unsetNote('photo');

        $updates->getFrom()->setExpectation(AwaitPhoto::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array(Back::getTitle('ru'), Back::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        $data = [
            'text'          =>  "Пришли мне *фотографии*, *максимально 9 фото*."."\n",
            'reply_markup'  =>  $buttons,
            'chat_id'       =>  $updates->getChat()->getId(),
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    =>  "Markdown"
        ];     
                        
        return BotApi::returnInline($data);
    }
}