<?php 

namespace App\Bots\rentcarca_bot\Commands\AdminCommands;

use App\Models\Announcement;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use App\Enums\Status;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class Publish extends Command
{
    public static $command = 'publish';

    public static $title = [
        'ru' => '👉 Опубликовать',
        'en' => '👉 Publish'
    ];

    public static $usage = ['/publish', 'publish'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        try {
            $announcement_id = $updates->getInlineData()->getAnnouncementId();

            $announcement = Announcement::find($announcement_id);

            $response = BotApi::sendMessageWithMedia([
                'text'                      => $this->announcementText($announcement),
                'chat_id'                   => "-1002367719758",
                'media'                     => collect($announcement->photos)->map(function ($photo) {
                    return ['file_id' => $photo];
                })->pluck('file_id')->take(9),
                'parse_mode'                => 'Markdown',
                'disable_web_page_preview'  => 'true',
            ]);

            if ($response->getOk()) {
                $announcement->update([
                    'status' => Status::published
                ]);
            }
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка публикации: {$exception->getMessage()}");
        }

        return $this->bot->executeCommand(Announcements::$command);
    }
}