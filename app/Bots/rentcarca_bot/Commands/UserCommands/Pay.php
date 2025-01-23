<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands;

use App\Models\Announcement;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Pay extends Command
{
    public static $command = 'pay';

    public static $title = [
        'ru' => '💵 Оплатить',
        'en' => '💵 Pay'
    ];

    public static $usage = ['/pay', 'pay'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(Paid::getTitle('ru'), Paid::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "*Для завершения публикации необходимо оплатить 3 доллара.*"."\n",
            "Пожалуйста, перейдите по ссылке ниже для оплаты через PayPal и *в комментарии укажите ваш ник в Telegram и ваше название автомобиля (марка и модель):*"."\n",

            "[Ссылка на оплату PayPal](https://paypal.me/Oleksandrkrl)"."\n",

            "Если у вас возникнут проблемы с оплатой, вы можете изучить [инструкцию по оплате тут](https://t.me/rentcarca_support)."."\n",
            "_После оплаты отправьте скриншот подтверждения модератору_ *@rentcarca_support*. @rentcarca_support. Как только оплата будет проверена, ваше объявление появится в канале._"
        ]);

        return BotApi::returnInline([
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}