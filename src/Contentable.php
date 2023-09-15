<?php

namespace Plank\Contentable;

use Illuminate\Support\Facades\Cache;

class Contentable
{
    public function clearCache($key)
    {
        if (Cache::has("contentable.html.{$key}")) {
            Cache::delete("contentable.html.{$key}");
        }

        if (Cache::has("contentable.json.{$key}")) {
            Cache::delete("contentable.json.{$key}");
        }
    }
}
