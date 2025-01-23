<?php 

namespace App\Bots\rentcarca_bot\Commands\AdminCommands;

use App\Models\Announcement;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class ShowAnnouncement extends Command
{
    public static $command = 'show_announcement';

    public static $usage = ['/show_announcement', 'show_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        try {
            $announcement_id = $updates->getInlineData()->getAnnouncementId();

            $announcement = Announcement::find($announcement_id);

            $result = BotApi::sendMessageWithMedia([
                'text'                      => $this->announcementText($announcement),
                'chat_id'                   => $updates->getChat()->getId(),
                'media'                     => collect($announcement->photos)->map(function ($photo) {
                    return ['file_id' => $photo];
                })->pluck('file_id')->take(9),
                'parse_mode'                => 'Markdown',
                'disable_web_page_preview'  => 'true',
            ]);
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка публикации: {$exception->getMessage()}");
        }
        
        if ($result->getOk()) {
            return $this->bot->executeCommand(ConfirmMessage::$command);
        }
    }
}