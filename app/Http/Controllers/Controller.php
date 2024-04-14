<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Get previous search terms from Redis cache.
     *
     * @return array
     */
    protected function getPreviousSearchTerms()
    {
        $keys = Redis::keys('search:*');
        $searchTerms = [];

        foreach ($keys as $key) {
            $searchTerm = str_replace('search:', '', $key);
            $searchTerms[] = $searchTerm;
        }

        return $searchTerms;
    }
}
