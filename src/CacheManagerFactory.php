<?php

namespace RedisPageCache;

use RedisClient\ClientFactory;
use RedisPageCache\Service\GzipCompressor;
use RedisPageCache\Service\WPCompat;

class CacheManagerFactory
{
    public static function getManager($redisClient = null)
    {
        $wp = new WPCompat();
        if (!$redisClient) {
            $redisClient = ClientFactory::create([
                'server' => '127.0.0.1:6379',
                'timeout' => 2,
                'database' => $wp->getDB(),
            ]);
        }

        
        $cacheManager = new CacheManager(new WPCompat, new GzipCompressor, $redisClient);
        return $cacheManager;
    }
}
