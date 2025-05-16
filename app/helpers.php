<?php

use Illuminate\Support\Str;

if(!function_exists('generatePaginateData')) {
    function generatePaginateData($paginationData): array
    {
        return [
            'current_page' => $paginationData->currentPage(),
            'per_page' => $paginationData->perPage(),
            'total' => $paginationData->total(),
            'last_page' => $paginationData->lastPage(),
        ];
    }
}
