<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Http\Modules\BaseCrud;
use App\Http\Requests\Api\V1\Profile\ApiChangeTimezoneRequest;
use App\Http\Resources\V1\Profile\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeTimezoneController extends BaseCrud
{
    public $model = User::class;

    public $resource = UserResource::class;

    public $updateValidator = ApiChangeTimezoneRequest::class;

    public function store(Request $request)
    {
        $user = Auth::user();

        return $this->update($request, $user->uuid);
    }
}
