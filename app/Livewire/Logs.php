<?php

namespace App\Livewire;

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
use Romanlazko\Telegram\Models\TelegramLog;
use Novadaemon\FilamentPrettyJson\PrettyJson;
use Romanlazko\Telegram\Models\TelegramBot;

#[Layout('layouts.app')]
class Logs extends Component implements HasForms, HasTable
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
            ->heading("{$this->telegram_bot->first_name} logs")
            ->defaultSort('created_at', 'desc')
            ->query(
                TelegramLog::where('telegram_bot_id', $this->telegram_bot->id))
            ->columns([
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
                TextColumn::make('message')
                    ->sortable()
                    ->grow()
                    ->lineClamp(2)
                    ->limit(50)
                    ->badge()
                    ->color('danger'),
                TextColumn::make('line')
            ])
            ->recordAction('view')
            ->actions([
                ViewAction::make()
                    ->form([
                        Textarea::make('message')
                            ->rows(10),
                        PrettyJson::make('params'),
                        Textarea::make('file'),
                        TextInput::make('line'),
                        Textarea::make('trace')
                            ->rows(10),

                    ])
                    ->hiddenLabel()
                    ->button()
                    ->color('warning'),
            ])
            ->poll('2s');
    }

    public function render(): View
    {
        return view('livewire.bots');
    }
}
