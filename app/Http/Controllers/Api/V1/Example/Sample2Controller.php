<?php

namespace App\Http\Controllers\Api\V1\Example;

use App\Http\Modules\BaseCrud;
use App\Http\Requests\Api\V1\Example\ApiSample2Request;
use App\Http\Resources\V1\Example\Sample2Resource;
use App\Models\Example\Sample;
use App\Models\Example\Sample2;

class Sample2Controller extends BaseCrud
{
    public $model = Sample2::class;
    public $resource = Sample2Resource::class;
    public $searchAble = ['title', 'description'];

    public $storeValidator = ApiSample2Request::class;
    public $updateValidator = ApiSample2Request::class;

    public function __prepareDataStore($data)
    {
        $data['sample_id'] = Sample::getId($data['sample_id']);
        return $data;
    }

    public function __prepareDataUpdate($data)
    {
        return $this->__prepareDataStore($data);
    }
}
