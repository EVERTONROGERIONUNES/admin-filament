<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total de Pedidios', UserOrder::count()),
            Card::make(
                'Total Ganho - 30 dias',
                'R$ ' . $this->getThirtyDaysOrders()
            ),
            Card::make('Total de Usuários', User::count()),
        ];
    }

    protected function getThirtyDaysOrders()
    {
        $result = OrderItem::where('created_at', '>', now()->subDays(30))->sum('order_value');
        return number_format($result / 100, 2, ',', '.');
    }
}
