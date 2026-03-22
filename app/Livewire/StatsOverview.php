<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Analytics';
    protected ?string $description = 'An overview of some analytics.';
    protected function getStats(): array
    {
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\OrderPayment::sum('amount');
        $totalExpenses = \App\Models\ExpenseRecordItem::sum('total');
        return [
            Stat::make('Total Orders', $totalOrders)
                ->description('15% increase from last week'),
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description($this->calcRevenueDescriptionPercentage()),
            Stat::make('Total Expenses', '$' . number_format($totalExpenses, 2))
                ->description($this->calcExpensesDescriptionPercentage()),
        ];
    }

    public function calcOrdersDescriptionPercentage(): string
    {
        $previousDate = now()->subWeek();
        $today = now();
        $previousOrders = \App\Models\Order::whereBetween('created_at', [$previousDate, $today])->count();
        $currentOrders = \App\Models\Order::whereBetween('created_at', [now()->subWeek(), now()])->count();
        if ($previousOrders == 0) {
            return 'N/A';
        }
        $percentageChange = (($currentOrders - $previousOrders) / $previousOrders) * 100;
        $sign = $percentageChange > 0 ? '+' : '';
        return $sign . number_format($percentageChange, 2) . '% increase from last week';
    }

    public function calcRevenueDescriptionPercentage(): string
    {
        $previousDate = now()->subWeek();
        $today = now();
        $previousRevenue = \App\Models\OrderPayment::whereBetween('created_at', [$previousDate, $today])->sum('amount');
        $currentRevenue = \App\Models\OrderPayment::whereBetween('created_at', [now()->subWeek(), now()])->sum('amount');
        if ($previousRevenue == 0) {
            return 'N/A';
        }
        $percentageChange = (($currentRevenue - $previousRevenue) / $previousRevenue) * 100;
        $sign = $percentageChange > 0 ? '+' : '';
        return $sign . number_format($percentageChange, 2) . '% increase from last week';
    }

    public function calcExpensesDescriptionPercentage(): string
    {
        $previousDate = now()->subWeek();
        $today = now();
        $previousExpenses = \App\Models\ExpenseRecordItem::whereBetween('created_at', [$previousDate, $today])->sum('total');
        $currentExpenses = \App\Models\ExpenseRecordItem::whereBetween('created_at', [now()->subWeek(), now()])->sum('total');
        if ($previousExpenses == 0) {
            return 'N/A';
        }
        $percentageChange = (($currentExpenses - $previousExpenses) / $previousExpenses) * 100;
        $sign = $percentageChange > 0 ? '+' : '';
        return $sign . number_format($percentageChange, 2) . '% increase from last week';
    }
}
