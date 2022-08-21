<?php

namespace Amin3536\LaravelApiUserProvider\authService;

use Illuminate\Database\Eloquent\Model;

interface DeserializerInterface
{
    /**
     * Convert function
     *
     * @param $responseBody
     * @return \Illuminate\Contracts\Auth\Authenticatable|Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function convert($responseBody);

    /**
     * Set model function
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|Model  $model
     */
    public function setModel($model);
}
