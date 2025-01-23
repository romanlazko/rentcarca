<?php

namespace App\Bots\rentcarca_bot\Commands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command as DefaultCommand;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\Exceptions\TelegramException;

abstract class Command extends DefaultCommand
{

    public $status_translations = [
        'created' => 'Создано и ожидает оплаты',
        'published' => 'Опубликовано',
        'approved' => 'Подтверждено',
        'rejected' => 'Отклонено',
        'paid' => 'Оплачено и ожидает публикации',
    ];

    public $body_types = [
        'sedan' => 'Седан',
        'universal' => 'Универсал',
        'coupe' => 'Купе',
        'hatchback' => 'Хэтчбек',
        'minivan' => 'Минивэн',
        'suv' => 'Внедорожник',
        'pickup' => 'Пикап',
        'cabriolet' => 'Кабриолет',
        'van' => 'Фургон',
        'crossover' => 'Кроссовер',
        'bus' => 'Автобус',
        'liftback' => 'Лифтбек'
    ];

    public $fuel_types = [
        'gasoline' => 'Бензин',
        'diesel' => 'Дизель',
        'electric' => 'Электро',
        'gas' => 'Газ',
        'electro_gasoline' => 'Гибрид (Электро-Бензин)',
        'electro_diesel' => 'Гибрид (Электро-Дизель)',
        'gas_gasoline' => 'Гибрид (Газ-Бензин)',
        'gas_diesel' => 'Гибрид (Газ-Дизель)',
    ];

    public $transmissions = [
        'automatic' => 'Автомат',
        'manual' => 'Механическая',
        'robot' => 'Робот',
    ];

    public $drive_types = [
        'front' => 'Передний',
        'back' => 'Задний',
        'full' => 'Полный',
        '4x4' => '4x4',
    ];

    public $features = [
        '0' => '',
        '1' => 'Кожаный салон',
        '2' => 'Cистема навигации',
        '3' => 'Bluetooth',
        '4' => 'Камера заднего вида',
        '5' => 'Подогрев сидений',
        '6' => 'CarPlay',
        '7' => 'Панорамная крыша',
        '8' => 'Круиз-контроль',
        '9' => 'Парктроник',
    ];

    public function announcementText(object $announcement)
    {
        $text = [];

        if ($announcement->model) {
            $text[] = "*{$announcement->model}*"."\n"; 
        }

        if ($announcement->body_type) {
            $text[] = "_Тип кузова:_ {$this->body_types[$announcement->body_type]}";
        }

        if ($announcement->transmission) {
            $text[] = "_Тип коробки:_ {$this->transmissions[$announcement->transmission]}";
        }

        if ($announcement->drive_type) {
            $text[] = "_Тип привода:_ {$this->drive_types[$announcement->drive_type]}";
        }

        if ($announcement->fuel_type) {
            $text[] = "_Тип топлива:_ {$this->fuel_types[$announcement->fuel_type]}";
        }

        if ($announcement->mileage) {
            $text[] = "_Пробег:_ {$announcement->mileage} miles";
        }

        if (isset($announcement->features) AND !empty($announcement->features) AND is_array($announcement->features) AND $announcement->features) {
            $features = [];

            foreach (array_filter($announcement->features) as $key => $value) {
                $features[] = $this->features[$value];
            }

            $text[] = implode(" ", [
                "_Дополнительные функции:_ ",
                implode(', ', $features)
            ]);
        }

        if ($announcement->price) {
            $text[] = "\n"."_Стоимость:_ {$announcement->price}";
        }

        if ($announcement->deposit) {

            $text[] = $announcement->deposit == '-' ? "_Депозит:_ не требуется" : "_Депозит:_ {$announcement->deposit}$";
        }

        if ($announcement->lease_term) {
            $text[] = "_Минимальный срок аренды:_ {$announcement->lease_term}"."\n";
        }

        if (isset($announcement->telegram_chat)) {
            $text[] = "[🔗Контакт на пользователя](tg://user?id={$announcement->telegram_chat->chat_id})"."\n";
        }

        if ($announcement->city) {
            $text[] = "#{$announcement->city}";
        } 

        return implode("\n", $text);
    }

    protected function hasPrivateForwards(): ?bool
    {
        return BotApi::getChat(['chat_id' => $this->updates->getChat()->getId()])->getResult()->getHasPrivateForwards();
    }

    protected function sendPrivacyInstructions(array $buttons = []): Response
    {
        try {
            $text = implode("\n", [
                "*Ой ой*"."\n",
                "Мы не можем подтвердить связь поскольку твои настройки конфиденциальности не позволяют нам сослаться на тебя."."\n",
                "Сделай все как указанно в [инструкции](https://telegra.ph/Kak-razreshit-peresylku-soobshchenij-12-27) что бы исправить это."."\n",
                "*Настройки конфиденциальности вступят в силу в течении 5-ти минут, после этого нажми на кнопку «Продолжить»*",
            ]);

            return BotApi::returnInline([
                'text'          => $text,
                'reply_markup'  => $buttons,
                'chat_id'       => $this->updates->getChat()->getId(),
                'parse_mode'    => "Markdown",
                'message_id'    => $this->updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            ]);
        }
        catch(TelegramException $e){
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $this->updates->getCallbackQuery()->getId(),
                'text'              => "Настройки еще не вступили в силу. Подождите 5 минут после изменения настроек и попробуйте еще раз.",
                'show_alert'        => true
            ]);
        }
    }
}