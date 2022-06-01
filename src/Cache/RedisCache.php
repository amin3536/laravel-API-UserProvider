<?php
namespace   Amin3536\LaravelApiUserProvider\Cache;
use Illuminate\Support\Facades\Cache;

class RedisCache
{

    /**
     * @param $token
     * @return string
     *
     */
    public function generateKeyForCacheingTokenData($token){
        return "user_token_".$token;
    }
    public function getExpierTimeFromAuthServer($token,$content){
        return 100;
    }
    public function getTimeExpierCache($token,$content){
        $ttl=config("laravel-api-user-provider.cache_token.ttl");
        if($ttl=="auto"){
            return $this->getExpierTimeFromAuthServer($token,$content);
        }else{
            return  $ttl;
        }
    }
    public function tryRetrieveFromCache($token){
        $key=$this->generateKeyForCacheingTokenData($token);
        return Cache::get($key)??false;
    }
    public function updateOrStoreTokenInRedisCache($token,$content){
        $expier=$this->getTimeExpierCache($token,$content);
        $key=$this->generateKeyForCacheingTokenData($token);
        return Cache::put($key, $content, $expier);
    }
}