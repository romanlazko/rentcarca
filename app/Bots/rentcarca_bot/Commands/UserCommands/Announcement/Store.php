<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use App\Bots\rentcarca_bot\Commands\UserCommands\Pay;
use App\Enums\Status;
use App\Models\Announcement;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class Store extends Command
{
    public static $command = 'store';

    public static $title = [
        'ru' => '💾 Сохранить и оплатить',
        'en' => '💾 Save and pay',
    ];

    public static $usage = ['store'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        if ($this->hasPrivateForwards()) {
            return $this->sendPrivacyInstructions(
                BotApi::inlineKeyboard([
                    [array('Продолжить', Store::$command, '')],
                ])
            );
        }

        $notes = $this->getConversation()->notes;

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $announcement = Announcement::create([
            'telegram_chat_id' => $telegram_chat->id,
            'model' => $notes['model'],
            'city'  => $notes['city'],
            'body_type'  => $notes['body_type'],
            'transmission'  => $notes['transmission'],
            'drive_type'  => $notes['drive_type'],
            'fuel_type'  => $notes['fuel_type'],
            'mileage'  => $notes['mileage'],
            'features'  => $notes['features'],
            'price'  => $notes['price'],
            'deposit'  => $notes['deposit'],
            'lease_term'  => $notes['lease_term'],
            'photos'  => $notes['photos'],
        ]);

        if ($announcement) {
            $this->getConversation()->clear();

            return $this->bot->executeCommand(Pay::$command);
        }

        return $this->handleError("Ошибка сохранения.");
    }
}