<?php

namespace App\Filament\Widgets;
// grafico em barras
//use Filament\Widgets\BarChartWidget;

//grafico em linha

use App\Models\User;
use App\Models\UserOrder;
use Filament\Widgets\LineChartWidget;

use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrderChart extends LineChartWidget
{
    // exibe o widget com os itens do tamanho da tela
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Usuários';

    protected function getData(): array
    {
        $data = Trend::model(User::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Usuários por Mês',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
