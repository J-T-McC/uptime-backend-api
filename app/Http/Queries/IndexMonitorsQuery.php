<?php

namespace App\Http\Queries;

use App\Models\Monitor;
use Spatie\QueryBuilder\QueryBuilder;

class IndexMonitorsQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Monitor::query());

        $this
            ->allowedIncludes(['channels'])
            ->allowedSorts(['uptime_status', 'id']);
    }
}
