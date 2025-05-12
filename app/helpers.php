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

if(!function_exists('generateUrl'))  {
    function generateUrl($url, $title): array|string
    {
        return $url ? str_replace(' ', '-', $url) : str_replace(' ', '-', $title);
    }
}

if(!function_exists('generateSlug')) {
    function generateSlug(string $title): string
    {
        return Str::slug($title);
    }
}
