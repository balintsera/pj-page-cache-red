<?php

use RedisPageCache\CacheManager;
use RedisPageCache\CacheManagerFactory;
use RedisPageCache\Redis\Flags;

use RedisClient\ClientFactory;


describe('Flags', function () {
    it('should create an expired flag', function () {

        if ($this->mocking) {
            $this->redisMock = $this->getProphet()->prophesize('RedisClient\Client\Version\RedisClient3x2');
            $this->redisClient = $this->redisMock->reveal();
        } else {
            $this->redisClient = ClientFactory::create([
                'server' => '127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
                'timeout' => 2,
            ]);
        }
        $expireFlag = new Flags('pjc-expired-flags', $this->redisClient);
        
        // flag: url:/about
        $expireFlag->add('url:/about');
        $expireFlag->add('url:/luca');
        if ($this->mocking) {
            $this->redisMock
                ->zrangebyscore('pjc-expired-flags', 0, '+inf', [ 'withscores' => true ])
                ->willReturn(['url:/about' => time(), 'url:/luca' => time()]);
        } else {
            $expireFlag->update();
        }

        $expireds = $expireFlag->getFromWithScores(0);
        
        assert($expireds == ['url:/about' => time(), 'url:/luca' => time()], 'expired flags failed');
    });
});
