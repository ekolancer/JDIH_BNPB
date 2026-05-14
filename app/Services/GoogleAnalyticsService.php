<?php

namespace App\Services;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Illuminate\Support\Facades\Cache;

class GoogleAnalyticsService
{
    protected BetaAnalyticsDataClient $client;
    protected string $propertyId;

    public function __construct()
    {
        $credentialsPath = config('services.google_analytics.credentials_path');

        $this->client = new BetaAnalyticsDataClient([
            'credentials' => $credentialsPath,
            'transport'   => 'rest',
        ]);

        $this->propertyId = config('services.google_analytics.property_id');
    }

    /**
     * Overview stats: sessions, users, pageviews, bounce rate, avg session duration
     */
    public function getOverviewStats(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        return Cache::remember("ga_overview_{$startDate}_{$endDate}", 300, function () use ($startDate, $endDate) {
            $request = (new RunReportRequest())
                ->setProperty("properties/{$this->propertyId}")
                ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])])
                ->setMetrics([
                    new Metric(['name' => 'sessions']),
                    new Metric(['name' => 'totalUsers']),
                    new Metric(['name' => 'screenPageViews']),
                    new Metric(['name' => 'bounceRate']),
                    new Metric(['name' => 'averageSessionDuration']),
                    new Metric(['name' => 'newUsers']),
                ]);

            $response = $this->client->runReport($request);
            $row      = $response->getRows()->offsetGet(0) ?? null;

            if (! $row) {
                return $this->emptyOverview();
            }

            $values = [];
            foreach ($row->getMetricValues() as $v) {
                $values[] = $v->getValue();
            }

            return [
                'sessions'             => (int) ($values[0] ?? 0),
                'users'                => (int) ($values[1] ?? 0),
                'pageviews'            => (int) ($values[2] ?? 0),
                'bounce_rate'          => round((float) ($values[3] ?? 0) * 100, 2),
                'avg_session_duration' => $this->formatDuration((float) ($values[4] ?? 0)),
                'new_users'            => (int) ($values[5] ?? 0),
            ];
        });
    }

    /**
     * Sessions over time (daily) for chart
     */
    public function getSessionsOverTime(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        return Cache::remember("ga_sessions_time_{$startDate}_{$endDate}", 300, function () use ($startDate, $endDate) {
            $request = (new RunReportRequest())
                ->setProperty("properties/{$this->propertyId}")
                ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])])
                ->setDimensions([new Dimension(['name' => 'date'])])
                ->setMetrics([
                    new Metric(['name' => 'sessions']),
                    new Metric(['name' => 'totalUsers']),
                    new Metric(['name' => 'screenPageViews']),
                ])
                ->setOrderBys([
                    (new OrderBy())
                        ->setDimension(new DimensionOrderBy(['dimension_name' => 'date']))
                        ->setDesc(false),
                ]);

            $response  = $this->client->runReport($request);
            $labels    = [];
            $sessions  = [];
            $users     = [];
            $pageviews = [];

            foreach ($response->getRows() as $row) {
                $rawDate   = $row->getDimensionValues()->offsetGet(0)->getValue();
                $labels[]  = substr($rawDate, 0, 4) . '-' . substr($rawDate, 4, 2) . '-' . substr($rawDate, 6, 2);
                $metrics   = $row->getMetricValues();
                $sessions[]  = (int) $metrics->offsetGet(0)->getValue();
                $users[]     = (int) $metrics->offsetGet(1)->getValue();
                $pageviews[] = (int) $metrics->offsetGet(2)->getValue();
            }

            return compact('labels', 'sessions', 'users', 'pageviews');
        });
    }

    /**
     * Top pages by pageviews
     */
    public function getTopPages(string $startDate = '30daysAgo', string $endDate = 'today', int $limit = 10): array
    {
        return Cache::remember("ga_top_pages_{$startDate}_{$endDate}_{$limit}", 300, function () use ($startDate, $endDate, $limit) {
            $request = (new RunReportRequest())
                ->setProperty("properties/{$this->propertyId}")
                ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])])
                ->setDimensions([
                    new Dimension(['name' => 'pagePath']),
                    new Dimension(['name' => 'pageTitle']),
                ])
                ->setMetrics([
                    new Metric(['name' => 'screenPageViews']),
                    new Metric(['name' => 'totalUsers']),
                    new Metric(['name' => 'averageSessionDuration']),
                ])
                ->setOrderBys([
                    (new OrderBy())
                        ->setMetric(new MetricOrderBy(['metric_name' => 'screenPageViews']))
                        ->setDesc(true),
                ])
                ->setLimit($limit);

            $response = $this->client->runReport($request);
            $pages    = [];

            foreach ($response->getRows() as $row) {
                $dims    = $row->getDimensionValues();
                $mets    = $row->getMetricValues();
                $pages[] = [
                    'path'     => $dims->offsetGet(0)->getValue(),
                    'title'    => $dims->offsetGet(1)->getValue(),
                    'views'    => (int) $mets->offsetGet(0)->getValue(),
                    'users'    => (int) $mets->offsetGet(1)->getValue(),
                    'duration' => $this->formatDuration((float) $mets->offsetGet(2)->getValue()),
                ];
            }

            return $pages;
        });
    }

    /**
     * Traffic sources (channel grouping)
     */
    public function getTrafficSources(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        return Cache::remember("ga_traffic_sources_{$startDate}_{$endDate}", 300, function () use ($startDate, $endDate) {
            $request = (new RunReportRequest())
                ->setProperty("properties/{$this->propertyId}")
                ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])])
                ->setDimensions([new Dimension(['name' => 'sessionDefaultChannelGroup'])])
                ->setMetrics([
                    new Metric(['name' => 'sessions']),
                    new Metric(['name' => 'totalUsers']),
                ])
                ->setOrderBys([
                    (new OrderBy())
                        ->setMetric(new MetricOrderBy(['metric_name' => 'sessions']))
                        ->setDesc(true),
                ]);

            $response = $this->client->runReport($request);
            $sources  = [];

            foreach ($response->getRows() as $row) {
                $sources[] = [
                    'channel'  => $row->getDimensionValues()->offsetGet(0)->getValue(),
                    'sessions' => (int) $row->getMetricValues()->offsetGet(0)->getValue(),
                    'users'    => (int) $row->getMetricValues()->offsetGet(1)->getValue(),
                ];
            }

            return $sources;
        });
    }

    /**
     * Device breakdown (desktop/mobile/tablet)
     */
    public function getDeviceBreakdown(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        return Cache::remember("ga_devices_{$startDate}_{$endDate}", 300, function () use ($startDate, $endDate) {
            $request = (new RunReportRequest())
                ->setProperty("properties/{$this->propertyId}")
                ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])])
                ->setDimensions([new Dimension(['name' => 'deviceCategory'])])
                ->setMetrics([
                    new Metric(['name' => 'sessions']),
                    new Metric(['name' => 'totalUsers']),
                ])
                ->setOrderBys([
                    (new OrderBy())
                        ->setMetric(new MetricOrderBy(['metric_name' => 'sessions']))
                        ->setDesc(true),
                ]);

            $response = $this->client->runReport($request);
            $devices  = [];

            foreach ($response->getRows() as $row) {
                $devices[] = [
                    'device'   => ucfirst($row->getDimensionValues()->offsetGet(0)->getValue()),
                    'sessions' => (int) $row->getMetricValues()->offsetGet(0)->getValue(),
                    'users'    => (int) $row->getMetricValues()->offsetGet(1)->getValue(),
                ];
            }

            return $devices;
        });
    }

    /**
     * Top countries by users
     */
    public function getTopCountries(string $startDate = '30daysAgo', string $endDate = 'today', int $limit = 10): array
    {
        return Cache::remember("ga_countries_{$startDate}_{$endDate}_{$limit}", 300, function () use ($startDate, $endDate, $limit) {
            $request = (new RunReportRequest())
                ->setProperty("properties/{$this->propertyId}")
                ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])])
                ->setDimensions([new Dimension(['name' => 'country'])])
                ->setMetrics([
                    new Metric(['name' => 'totalUsers']),
                    new Metric(['name' => 'sessions']),
                ])
                ->setOrderBys([
                    (new OrderBy())
                        ->setMetric(new MetricOrderBy(['metric_name' => 'totalUsers']))
                        ->setDesc(true),
                ])
                ->setLimit($limit);

            $response  = $this->client->runReport($request);
            $countries = [];

            foreach ($response->getRows() as $row) {
                $countries[] = [
                    'country'  => $row->getDimensionValues()->offsetGet(0)->getValue(),
                    'users'    => (int) $row->getMetricValues()->offsetGet(0)->getValue(),
                    'sessions' => (int) $row->getMetricValues()->offsetGet(1)->getValue(),
                ];
            }

            return $countries;
        });
    }

    /**
     * Top browsers
     */
    public function getTopBrowsers(string $startDate = '30daysAgo', string $endDate = 'today', int $limit = 6): array
    {
        return Cache::remember("ga_browsers_{$startDate}_{$endDate}_{$limit}", 300, function () use ($startDate, $endDate, $limit) {
            $request = (new RunReportRequest())
                ->setProperty("properties/{$this->propertyId}")
                ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])])
                ->setDimensions([new Dimension(['name' => 'browser'])])
                ->setMetrics([new Metric(['name' => 'totalUsers'])])
                ->setOrderBys([
                    (new OrderBy())
                        ->setMetric(new MetricOrderBy(['metric_name' => 'totalUsers']))
                        ->setDesc(true),
                ])
                ->setLimit($limit);

            $response = $this->client->runReport($request);
            $browsers = [];

            foreach ($response->getRows() as $row) {
                $browsers[] = [
                    'browser' => $row->getDimensionValues()->offsetGet(0)->getValue(),
                    'users'   => (int) $row->getMetricValues()->offsetGet(0)->getValue(),
                ];
            }

            return $browsers;
        });
    }

    /**
     * New vs returning users
     */
    public function getNewVsReturning(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        return Cache::remember("ga_new_returning_{$startDate}_{$endDate}", 300, function () use ($startDate, $endDate) {
            $request = (new RunReportRequest())
                ->setProperty("properties/{$this->propertyId}")
                ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])])
                ->setDimensions([new Dimension(['name' => 'newVsReturning'])])
                ->setMetrics([
                    new Metric(['name' => 'totalUsers']),
                    new Metric(['name' => 'sessions']),
                ]);

            $response = $this->client->runReport($request);
            $result   = ['new' => 0, 'returning' => 0];

            foreach ($response->getRows() as $row) {
                $type  = $row->getDimensionValues()->offsetGet(0)->getValue();
                $users = (int) $row->getMetricValues()->offsetGet(0)->getValue();
                if ($type === 'new') {
                    $result['new'] = $users;
                } else {
                    $result['returning'] = $users;
                }
            }

            return $result;
        });
    }

    /**
     * Clear all GA cache
     */
    public function clearCache(): void
    {
        $keys = [
            'ga_overview_*',
            'ga_sessions_time_*',
            'ga_top_pages_*',
            'ga_traffic_sources_*',
            'ga_devices_*',
            'ga_countries_*',
            'ga_browsers_*',
            'ga_new_returning_*',
        ];

        // Hapus semua kombinasi period yang mungkin
        $periods = ['7daysAgo', '14daysAgo', '30daysAgo', '90daysAgo', '365daysAgo'];
        $end     = 'today';
        $limits  = [6, 10];

        foreach ($periods as $start) {
            Cache::forget("ga_overview_{$start}_{$end}");
            Cache::forget("ga_sessions_time_{$start}_{$end}");
            Cache::forget("ga_traffic_sources_{$start}_{$end}");
            Cache::forget("ga_devices_{$start}_{$end}");
            Cache::forget("ga_new_returning_{$start}_{$end}");
            foreach ($limits as $limit) {
                Cache::forget("ga_top_pages_{$start}_{$end}_{$limit}");
                Cache::forget("ga_countries_{$start}_{$end}_{$limit}");
                Cache::forget("ga_browsers_{$start}_{$end}_{$limit}");
            }
        }
    }

    private function formatDuration(float $seconds): string
    {
        $m = (int) floor($seconds / 60);
        $s = (int) ($seconds % 60);

        return "{$m}m {$s}s";
    }

    private function emptyOverview(): array
    {
        return [
            'sessions'             => 0,
            'users'                => 0,
            'pageviews'            => 0,
            'bounce_rate'          => 0,
            'avg_session_duration' => '0m 0s',
            'new_users'            => 0,
        ];
    }
}
