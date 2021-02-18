<?php

namespace App\Modules\authService;


use App\Models\Admin;
use App\Models\Role;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class JsonToModel
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    /**
     * @param $responseBody
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function convert($responseBody)
    {
        $body = (array)json_decode($responseBody);
        $model = $this->fillModel($body);
        if ($model instanceof Admin)
            $model->roles = $this->convertRoles($body['roles']);
        return $model;
    }


    /**
     * @param $roles
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $Roles
     */
    private function convertRoles($roles): Collection
    {
        return Role::hydrate($roles);
    }

    /**
     * @param $jsonAdmin
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private function fillModel($jsonAdmin)
    {


        return $this->model->forceFill($jsonAdmin);


    }

}
