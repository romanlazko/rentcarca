<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands;

use App\Models\Announcement;
use Romanlazko\Telegram\App\BotApi;
use App\Bots\rentcarca_bot\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Paid extends Command
{
    public static $command = 'paid';

    public static $title = [
        'ru' => '✅ Оплачено',
        'en' => '✅ Paid'
    ];

    public static $usage = ['/paid', 'paid'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array("🔙 Назад", Pay::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "*Спасибо! Ваше объявление отправлено на рассмотрение администратору.*"."\n",
            "Мы проверим его в течение 48 часов. Если оно соответствует правилам, оно будет опубликовано в ближайшее время."."\n",
            "_В случае необходимости дополнительной информации или правок, администратор свяжется с вами._"
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