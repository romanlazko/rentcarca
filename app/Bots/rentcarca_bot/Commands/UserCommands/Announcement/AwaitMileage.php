<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitMileage extends Command
{
    public static $expectation = 'await_mileage';

    public static $pattern = '/^await_mileage$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['mileage' => $updates->getMessage()?->getText()],
            [
                'mileage' => [
                    'required',
                    'regex:/^\d+$/', // разрешает только цифры без пробелов и букв
                ],
            ],
            [
                'mileage.required' => 'Поле Пробег обязательно к заполнению',
                'mileage.regex' => 'Поле Пробег должно содержать только цифры без пробелов и букв',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(Steps::$command);
        }

        $this->getConversation()->update($validator->validated());

        return $this->bot->executeCommand(Steps::$command);
    }
}