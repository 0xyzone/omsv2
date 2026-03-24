<?php

namespace App\Filament\Widgets;

use App\Models\ExpenseRecord;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TotalExpenseChart extends ApexChartWidget
{
    protected static ?int $sort = 3;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'totalExpenseChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'TotalExpenseChart';

    // Defer loading to prevent dashboard lag
    protected static bool $deferLoading = true;
    protected static ?string $loadingIndicator = 'Loading expense data...';

    // Default filter state
    public ?string $filter = 'this_year';

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
        // Don't run queries until the widget is ready to load on the screen
        if (!$this->readyToLoad) {
            return [];
        }

        $activeFilter = $this->filter;

        // Base query with 'finalized' constraint
        $query = ExpenseRecord::query()
            ->where('status', 'finalized')
            ->select([
                DB::raw('SUM(final_amount) as total'),
                DB::raw("DATE_FORMAT(created_at, '%b') as month_name"),
                DB::raw("MONTH(created_at) as month_num"),
                DB::raw("YEAR(created_at) as year_num")
            ]);

        // Apply time filters based on created_at
        if ($activeFilter === 'this_year') {
            $query->whereYear('created_at', now()->year);
        } elseif ($activeFilter === 'last_year') {
            $query->whereYear('created_at', now()->subYear()->year);
        } elseif ($activeFilter === 'last_6_months') {
            $query->where('created_at', '>=', now()->subMonths(6));
        }

        $data = $query->groupBy('year_num', 'month_num', 'month_name')
            ->orderBy('year_num')
            ->orderBy('month_num')
            ->get();

        // 12-Month fill structure for yearly views
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
            // Dynamic labels for 6-months view
            $amounts = $data->pluck('total')->map(fn($val) => (float) $val)->toArray();
            $labels = $data->pluck('month_name')->toArray();
        }

        // Failsafe for entirely empty data sets
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
                    'name' => 'Expenses',
                    'data' => $amounts,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
            ],
            'colors' => ['#ef4444'], // Changed to red to differentiate from Revenue
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 4,
                    'horizontal' => false,
                    'columnWidth' => '55%',
                ],
            ],
        ];
    }

    // Safely inject JS functions for axis and tooltip formatting
    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return 'रु ' + val.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return 'रु ' + val.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    }
                }
            },
            dataLabels: {
                enabled: false
            }
        }
        JS);
    }/*  */
}
