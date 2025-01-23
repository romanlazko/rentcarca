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
use Romanlazko\Telegram\Models\TelegramBot;
use Filament\Tables\Actions\Action;

#[Layout('layouts.app')]
class Bots extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(TelegramBot::query())
            ->columns([
                TextColumn::make('first_name')
                    ->sortable()
                    ->grow()
                    ->description(fn ($record) => $record->username),
            ])
            ->actions([
                Action::make('Chats')
                    ->hiddenLabel()
                    ->button()
                    ->icon('heroicon-o-chat-bubble-bottom-center')
                    ->url(fn ($record) => route('admin.chats', $record))
                    ->color('success'),
                Action::make('Logs')
                    ->hiddenLabel()
                    ->button()
                    ->icon('heroicon-o-clipboard-document-list')
                    ->url(fn ($record) => route('admin.logs', $record))
                    ->color('danger'),
            ]);
    }
    public function render(): View
    {
        return view('livewire.bots');
    }
}
