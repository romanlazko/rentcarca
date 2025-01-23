<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitCity extends Command
{
    public static $expectation = 'await_city';

    public static $pattern = '/^await_city$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['city' => $updates->getMessage()?->getText()],
            [
                'city' => [
                    'required',
                    'alpha',
                    'regex:/^\S+$/',
                    'max:30',
                ],
            ],
            [
                'city.required' => 'Поле Город обязательно к заполнению',
                'city.alpha' => 'Поле Город должно содержать только буквы без пробелов',
                'city.regex' => 'Поле Город должно быть одним словом без пробелов',
                'city.max' => 'Поле Город должно быть не более 30 символов',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(Steps::$command);
        }

        $validated = $validator->validated();

        $this->getConversation()->update([
            'city' => $validated['city']
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}