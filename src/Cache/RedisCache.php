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
        $content=json_decode($content);
        if(!isset($content->token_expire_at)){
            throw new \Exception("dont set token_expire_at in user data");
        }
        $token_expire_at =Carbon::make( $content->token_expire_at);
        $now=Carbon::make( now());
        return $token_expire_at->timestamp-$now->timestamp;
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