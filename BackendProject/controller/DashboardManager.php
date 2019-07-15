<?php


namespace eTorn\Controller;

use eTorn\Models\Store;
use Illuminate\Database\Capsule\Manager as DB;

class DashboardManager
{
    public function turnsNumber($startDate, $endDate, $storeName)
    {
        $store = Store::query()->where('name', '=', $storeName)->first();

        if ($store && $store instanceof Store) {
            return $store->queue()
                ->buckets()
                ->join('turns', 'turns.id_bucket', '=', 'buckets.id')
                ->where('turns.created_at', '>', $startDate)
                ->where('turns.created_at', '<', $endDate)
                ->first([
                    DB::raw('count(*) as total')
                ]);
        }
        return [
            'done' => false,
            'err' => 'Store not found'
        ];
    }

    public function turnsPerType($startDate, $endDate, $storeName)
    {
        $store = Store::query()->where('name', '=', $storeName)->first();

        if ($store && $store instanceof Store) {
            return $store->queue()
                ->buckets()
                ->join('turns', 'turns.id_bucket', '=', 'buckets.id')
                ->where('turns.created_at', '>', $startDate)
                ->where('turns.created_at', '<', $endDate)
                ->groupBy('type')
                ->select([
                    'turns.type as type',
                    DB::raw('count(*) as total')
                ])
                ->get();
        }
        return [
            'done' => false,
            'err' => 'Store not found'
        ];
    }

    public function turnsByDay($startDate, $endDate, $storeName)
    {
        $store = Store::query()->where('name', '=', $storeName)->first();

        if ($store && $store instanceof Store) {
            return $store->queue()
                ->buckets()
                ->join('turns', 'turns.id_bucket', '=', 'buckets.id')
                ->where('turns.created_at', '>', $startDate)
                ->where('turns.created_at', '<', $endDate)
                ->groupBy('day')
                ->select([
                    DB::raw('DAY(turns.created_at) as day'),
                    DB::raw('count(*) as total')
                ])
                ->get();
        }
        return [
            'done' => false,
            'err' => 'Store not found'
        ];
    }
}