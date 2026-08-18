<?php

namespace App\Traits;

use App\Models\Statistics;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasStatistics
{
    /**
     * Catat statistik baru untuk model ini
     *
     * @param  string  $metricName
     * @param  mixed   $value
     * @param  array|null  $additionalData
     * @return \App\Models\Statistics
     */
    public function recordStatistic(string $metricName, $value, array $additionalData = null)
    {
        return $this->statistics()->create([
            'metric_name' => $metricName,
            'metric_value' => $value,
            'date_recorded' => now(),
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Relasi morphMany ke model Statistics
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function statistics(): MorphMany
    {
        return $this->morphMany(Statistics::class, 'statisticable');
    }

    /**
     * Ambil statistik terbaru untuk model ini
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function latestStatistics(int $limit = 5)
    {
        return $this->statistics()
            ->orderByDesc('date_recorded')
            ->limit($limit)
            ->get();
    }
}
