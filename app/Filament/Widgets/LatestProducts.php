<?php

namespace App\Filament\Widgets;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;
use App\Models\UserOrder;

class LatestProducts extends BaseWidget
{
    // exibe o widget com os itens do tamanho da tela
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return UserOrder::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\TextColumn::make('user.name')->searchable(),
            Tables\Columns\TextColumn::make('items_count'),
            Tables\Columns\TextColumn::make('orderTotal')->money('BRL'),
            Tables\Columns\TextColumn::make('created_at')->date('d/m/Y H:i:s')
        ];
    }
}
