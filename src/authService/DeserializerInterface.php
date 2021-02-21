<?php

namespace Amin3536\LaravelApiUserProvider\authService;

use Illuminate\Database\Eloquent\Model;

interface DeserializerInterface
{
    /**
     * @param $responseBody
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Collection|null
     */
    public function convert($responseBody);

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|Model $model
     */
    public function setModel($model);
}
