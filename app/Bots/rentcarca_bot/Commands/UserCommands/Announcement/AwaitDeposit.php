<?php 

namespace App\Bots\rentcarca_bot\Commands\UserCommands\Announcement;

use App\Bots\rentcarca_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\rentcarca_bot\Commands\UserCommands\MenuCommand;
use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitDeposit extends Command
{
    public static $expectation = 'await_deposit';

    public static $pattern = '/^await_deposit$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['deposit' => $updates->getMessage()?->getText()],
            [
                'deposit' => [
                    'required',
                    'regex:/^(\d+|-)$/',
                ],
            ],
            [
                'deposit.required' => 'Поле Депозит обязательно к заполнению',
                'deposit.regex' => 'Поле Депозит должно содержать только цифры или символ "-"',
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