<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Constants\FileUploadConst;
use App\Http\Modules\BaseCrud;
use App\Http\Requests\Api\V1\Profile\ApiChangeProfileEmployeeRequest;
use App\Http\Resources\V1\Employee\EmployeeResource;
use App\Http\Resources\V1\Profile\UserResource;
use App\Models\Account\Area;
use App\Models\Account\Identity;
use App\Models\Account\Information;
use App\Models\Account\Regency;
use App\Models\Account\Suburb;
use App\Models\Base\Address;
use App\Models\Base\File;
use App\Models\Ecommerce\Customer;
use App\Models\Employee\Employee;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeProfileEmployeeController extends BaseCrud
{

    public $model = Employee::class;
    public $resource = EmployeeResource::class;

    public $updateValidator = ApiChangeProfileEmployeeRequest::class;


    public function store(Request $request)
    {
        $user = Auth::user();

        return $this->update($request, $user->employee->uuid);
    }

    public function __afterUpdate()
    {
        $em = $this->row;
        $user = $this->row->user;

        if (empty($em->number)) {
            $em->update([
                'number' => Employee::generateNumber($em->id)
            ]);
        }

        $user->update([
            'name' => $this->requestData->input('name'),
        ]);

        if (!empty($user->customer)) {
            $user->customer->update([
                'name' => $em->name,
                'gender' => $em->gender,
                'pob' => $em->pob,
                'dob' => $em->dob,
            ]);
        }

        $data = $this->requestData->input('address');
        $regency = Regency::getFirst($this->requestData->input('address.regency_id'));
        $suburb = Suburb::getFirst($this->requestData->input('address.suburb_id'));
        $area = Area::getFirst($this->requestData->input('address.area_id'));
        $ad_obj =  $area ?? $suburb ?? $regency ?? null;
        if ($ad_obj) {
            $data = array_merge($data, Address::prepareBaseLocation($ad_obj));
        }

        $data['zip'] = $this->requestData->input('address.zip');
        $data = array_merge($data, Address::prepareInsert(
            $em,
            Address::SLUG_ADDRESS_EMPLOYEE,
            Address::ADAPTER_BASE,
            true
        ));

        if (!empty($em->defaultAddress)) {
            $em->defaultAddress->update($data);
        } else {
            $em->defaultAddress()->save(new Address($data));
        }

        //data identity
        $data = Identity::prepareInsertKTP($em);
        $data['number'] = $this->requestData->input('identity.ktp');
        if (!empty($em->defaultAddress)) {
            $em->defaultAddress->update($data);
        } else {
            $em->defaultAddress()->save(new Identity($data));
        }

        if ($file = $this->requestData->file('avatar')) {
            $upload = new UploadService(
                $file,
                FileUploadConst::USER_AVATAR_PATH,
                $user->uuid
            );

            $upload->uploadResize(300);
            $upload->saveFileInfo($this->row->user->avatar(), ['slug' =>  File::SLUG_FILE_PROFILE, 'ref_table' => $this->row->user->getTable()]);
        }

        $em->informations()->whereIn(
            'slug',
            [
                Information::SLUG_INFO_CERTIFICATE,
                Information::SLUG_INFO_SEMINAR,
            ]
        )->delete();

        foreach ($this->requestData->input('certification') ?? [] as $v) {
            Information::create(Information::prepareInsert($em) + [
                'slug' => Information::SLUG_INFO_CERTIFICATE,
                'title' => $v['title'],
                'from' => $v['date'],
                'to' => $v['date'],
            ]);
        }

        foreach ($this->requestData->input('seminar') ?? [] as $v) {
            Information::create(Information::prepareInsert($em) + [
                'slug' => Information::SLUG_INFO_SEMINAR,
                'title' => $v['title'],
                'from' => $v['date'],
                'to' => $v['date'],
            ]);
        }
    }
}
