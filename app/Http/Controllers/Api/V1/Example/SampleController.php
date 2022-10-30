<?php

namespace App\Http\Controllers\Api\V1\Example;

use App\Http\Modules\BaseCrud;
use App\Http\Requests\Api\V1\Example\ApiSampleRequest;
use App\Http\Resources\V1\Example\SampleResource;
use App\Models\Example\Sample;
use Illuminate\Support\Facades\Auth;

class SampleController extends BaseCrud
{
    public $model = Sample::class;
    public $resource = SampleResource::class;

    public $storeValidator = ApiSampleRequest::class;
    public $updateValidator = ApiSampleRequest::class;
}
