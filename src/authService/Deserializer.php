<?php
/**
 * Created by PhpStorm.
 * User: amin
 * Date: 2/18/21
 * Time: 3:05 PM
 */

namespace App\Modules\authService;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Deserializer implements DeserializerInterface
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Model
     */
    private $model;


    /**
     * @param $responseBody
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function convert($responseBody)
    {
        $body = (array)json_decode($responseBody);
        $model = $this->fillModel($body);
        return $model;
    }


    /**
     * @param $jsonAdmin
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function fillModel($jsonAdmin)
    {


        return $this->model->forceFill($jsonAdmin);


    }

    /**
     * @param $list
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    protected function convertList($list): Collection
    {
        return $this->model->hydrate($list);
    }

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|Model $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

}
