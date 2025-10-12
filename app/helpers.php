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

if(!function_exists("generateShortUniqueCode")) {
    function generateShortUniqueCode(): string
    {
        $uuid = Str::uuid()->toString();
        $numbers = preg_replace('/[^0-9]/', '', $uuid);
        return substr($numbers, 0, 8);
    }
}


