<?php 

namespace App\Bots\rentcarca_bot\Commands\AdminCommands;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use App\Enums\Status;
use App\Models\Announcement;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class ConfirmMessage extends Command
{
    public static $command = 'confirm';

    public static $usage = ['confirm'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement_id = $updates->getInlineData()->getAnnouncementId();

        $announcement = Announcement::find($announcement_id);

        $button = match ($announcement->status) {
            Status::created => [array(Paid::getTitle('ru'), Paid::$command, '')],
            Status::paid => [array(Publish::getTitle('ru'), Publish::$command, '')],
            default => []
        };

        $buttons = BotApi::inlineKeyboard([
            $button,
            [array(Reject::getTitle('ru'), Reject::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        try {
            BotApi::deleteMessage([
                'chat_id'       => $updates->getChat()->getId(),
                'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            ]);
        } catch (\Throwable $th) {
        }

        $text = implode("\n", [
            "Действия:"."\n",
        ]);

        return BotApi::sendMessage([
            'text'          => $text, 
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => 'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            'reply_markup'  => $buttons
        ]);
    }
}