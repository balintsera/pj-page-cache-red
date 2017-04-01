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
                'server' => $wp->getRedisHost() . ':' . $wp->getRedisPort(),
                'timeout' => 2,
                'database' => $wp->getDB(),
            ]);
        }

        
        $cacheManager = new CacheManager($wp, new GzipCompressor, $redisClient);
        return $cacheManager;
    }
}
