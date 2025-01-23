<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Edit extends Command
{
    public static $command = 'edit';

    public static $title = [
        'ru' => '📝 Редактирование объявления',
        'en' => '📝 Edit announcement',
    ];

    public static $usage = ['edit'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $notes = $this->getConversation()->notes;

        $features = [];

        foreach ($notes['features'] as $key => $value) {
            $features[] = $this->features[$value];
        }

        $features = implode(', ', $features);

        $buttons = BotApi::inlineKeyboard([
            [array(Model::getTitle('ru').": {$notes['model']}", Model::$command, '')],
            [array(City::getTitle('ru').": {$notes['city']}", City::$command, '')],
            [array(BodyType::getTitle('ru').": {$this->body_types[$notes['body_type']]}", BodyType::$command, '')],
            [array(Transmission::getTitle('ru').": {$this->transmissions[$notes['transmission']]}", Transmission::$command, '')],
            [array(DriveType::getTitle('ru').": {$this->drive_types[$notes['drive_type']]}", DriveType::$command, '')],
            [array(FuelType::getTitle('ru').": {$this->fuel_types[$notes['fuel_type']]}", FuelType::$command, '')],
            [array(Mileage::getTitle('ru').": {$notes['mileage']}", Mileage::$command, '')],
            [array(Features::getTitle('ru').": {$features}", Features::$command, '')],
            [array(LeaseTerm::getTitle('ru').": {$notes['lease_term']}", LeaseTerm::$command, '')],
            [array(Deposit::getTitle('ru').": {$notes['deposit']}", Deposit::$command, '')],
            [array(Price::getTitle('ru').": {$notes['price']}", Price::$command, '')],
            [array("🔙 Назад", ConfirmMessage::$command, '')],
        ]);

        return BotApi::returnInline([
            'text'          =>  "*Редактирование объявления*",
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}