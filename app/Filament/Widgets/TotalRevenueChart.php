<?php

namespace App\Filament\Widgets;

use App\Models\OrderPayment;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TotalRevenueChart extends ApexChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $chartId = 'totalRevenueChart';
    protected static ?string $heading = 'Total Revenue Chart';

    // 1. Defer loading prevents the widget from slowing down the entire page load
    protected static bool $deferLoading = true;

    // 2. Custom loading indicator text while the query runs
    protected static ?string $loadingIndicator = 'Loading revenue data...';

    // 3. Define the default active filter
    public ?string $filter = 'this_year';

    // 4. Define available filters for the dropdown
    protected function getFilters(): ?array
    {
        return [
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
            'last_6_months' => 'Last 6 Months',
        ];
    }

    protected function getOptions(): array
    {
        // 5. Crucial: Return empty array if not ready, preventing premature DB queries
        if (!$this->readyToLoad) {
            return [];
        }

        // Use the current active filter
        $activeFilter = $this->filter;

        $query = OrderPayment::query()
            ->select([
                DB::raw('SUM(amount) as total'),
                DB::raw("DATE_FORMAT(payment_date, '%b') as month_name"),
                DB::raw("MONTH(payment_date) as month_num"),
                DB::raw("YEAR(payment_date) as year_num")
            ]);

        // Apply filter constraints
        if ($activeFilter === 'this_year') {
            $query->whereYear('payment_date', now()->year);
        } elseif ($activeFilter === 'last_year') {
            $query->whereYear('payment_date', now()->subYear()->year);
        } elseif ($activeFilter === 'last_6_months') {
            $query->where('payment_date', '>=', now()->subMonths(6));
        }

        $data = $query->groupBy('year_num', 'month_num', 'month_name')
            ->orderBy('year_num')
            ->orderBy('month_num')
            ->get();

        // 6. Template to ensure all 12 months show up for yearly views
        if ($activeFilter !== 'last_6_months') {
            $monthlyTemplate = [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ];

            foreach ($data as $row) {
                $monthlyTemplate[$row->month_name] = (float) $row->total;
            }

            $amounts = array_values($monthlyTemplate);
            $labels = array_keys($monthlyTemplate);
        } else {
            // "Last 6 Months" simply uses the dynamic data found
            $amounts = $data->pluck('total')->map(fn($val) => (float) $val)->toArray();
            $labels = $data->pluck('month_name')->toArray();
        }

        // Empty state fallback
        if (empty($amounts)) {
            $amounts = [0];
            $labels = ['No Data'];
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Revenue',
                    'data' => $amounts,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
            ],
            'colors' => ['#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'horizontal' => false,
                    'columnWidth' => '55%',
                ],
            ],
        ];
    }

    // 7. Using extraJsOptions (per documentation) to safely inject JS functions for currency formatting
    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return '$' + val.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return '$' + val.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    }
                }
            },
            dataLabels: {
                enabled: false
            }
        }
        JS);
    }
}
