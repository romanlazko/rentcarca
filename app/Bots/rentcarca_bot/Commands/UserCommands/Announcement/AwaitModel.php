<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitModel extends Command
{
    public static $expectation = 'await_model';

    public static $pattern = '/^await_model$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['model' => $updates->getMessage()?->getText()],
            [
                'model' => [
                    'required',
                    'max:50',
                ],
            ],
            [
                'model.required' => 'Поле Модель обязательно к заполнению',
                'model.max' => 'Поле Модель должно быть не более 50 символов',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(Steps::$command);
        }

        $validated = $validator->validated();

        $this->getConversation()->update([
            'model' => $validated['model']
        ]);

        return $this->bot->executeCommand(Steps::$command);
    }
}