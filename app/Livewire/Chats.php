<?php

namespace App\Livewire;

use Filament\Tables\Actions\DeleteAction;
use Romanlazko\Telegram\Models\TelegramBot;
use Romanlazko\Telegram\Models\TelegramChat;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Romanlazko\Telegram\Models\TelegramLog;
use Novadaemon\FilamentPrettyJson\PrettyJson;

#[Layout('layouts.app')]
class Chats extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public TelegramBot $telegram_bot;

    public function mount(TelegramBot $telegram_bot)
    {
        $this->telegram_bot = $telegram_bot;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading("{$this->telegram_bot->first_name} chats")
            ->query(
                $this->telegram_bot
                    ->chats()
                    ->getQuery()
                )
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    ->label('Чат')
                    ->searchable(['first_name', 'last_name', 'username', 'title', 'chat_id'])
                    ->state(function (TelegramChat $telegram_chat) {
                        return "$telegram_chat->first_name $telegram_chat->last_name $telegram_chat->title";
                    })
                    ->description(fn (TelegramChat $telegram_chat) => "{$telegram_chat?->username} ({$telegram_chat?->chat_id})"),
                TextColumn::make('type')
                    ->sortable()
                    ->badge(),
                SelectColumn::make('role')
                    ->sortable()
                    ->options([
                        'admin' => 'admin',
                        'user' => 'user'
                    ]),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->label('Последняя активность')
                    ->dateTime(),

            ])
            ->actions([
                DeleteAction::make()
                    ->hiddenLabel()
                    ->button(),
            ]);
    }



    public function render(): View
    {
        return view('livewire.bots');
    }
}
