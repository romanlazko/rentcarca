<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class Preview extends Command
{
    public static $command = 'preview';

    public static $title = [
        'ru' => 'Предпросмотр объявления',
        'en' => 'Preview',
    ];

    public static $usage = ['preview'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        try {
            $notes = $this->getConversation()->notes;

            $result = BotApi::sendMessageWithMedia([
                'text'                      => $this->announcementText((object) $notes),
                'chat_id'                   => $updates->getChat()->getId(),
                'media'                     => collect($notes['photos'])->map(function ($photo) {
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