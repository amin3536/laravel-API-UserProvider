<?php

namespace Amin3536\LaravelApiUserProvider\authService;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Deserializer implements DeserializerInterface
{
    /**
     * Model variable.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * Convert function.
     *
     * @param $responseBody
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function convert($responseBody)
    {
        $body = (array) json_decode($responseBody);
        $model = $this->fillModel($body);

        return $model;
    }

    /**
     * Fill model function.
     *
     * @param $jsonAdmin
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function fillModel($jsonAdmin)
    {
        return $this->model->forceFill($jsonAdmin);
    }

    /**
     * Convert list function.
     *
     * @param $list
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    protected function convertList($list): Collection
    {
        return $this->model->hydrate($list);
    }

    /**
     * Set model function.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|Model  $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
}
