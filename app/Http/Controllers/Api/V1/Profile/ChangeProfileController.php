<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Constants\FileUploadConst;
use App\Constants\RoleConst;
use App\Http\Modules\BaseCrud;
use App\Http\Requests\Api\V1\Profile\ApiChangeProfileRequest;
use App\Http\Resources\V1\Profile\UserResource;
use App\Models\Account\Area;
use App\Models\Account\Identity;
use App\Models\Account\MetaData;
use App\Models\Account\Regency;
use App\Models\Account\Suburb;
use App\Models\Base\Address;
use App\Models\Base\File;
use App\Models\Employee\Branch;
use App\Models\Medical\MedicalRecord;
use App\Models\Medical\Patient;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeProfileController extends BaseCrud
{

    public $model = User::class;

    public $resource = UserResource::class;

    public $updateValidator = ApiChangeProfileRequest::class;

    private string $profile;

    public function store(Request $request)
    {
        $user = Auth::user();

        return $this->update($request, $user->uuid);
    }

    public function __afterUpdate()
    {
        $this->profile = request('profile_as');

        $data = [
            'job_id' => MetaData::getId($this->requestData->input('patient.job_id')),
            'religion_id' => MetaData::getId($this->requestData->input('patient.religion_id')),
            'marriage_status_id' => MetaData::getId($this->requestData->input('patient.marriage_status_id')),
        ];

        if ($this->profile == 'patient') {
            $this->updatePatient($data);
            $this->row->patient->medicalRecord->update($this->requestData->only(['dob', 'pob', 'gender', 'name']));
            $this->updateCustomer($data);
        }

        if ($this->profile == 'customer') {
            $this->updateCustomer($data);
        }

        if ($this->profile == 'patient-data-clinic') {
            $this->updatePatient([]);
        }

        if ($file = $this->requestData->file('avatar')) {
            $upload = new UploadService(
                $file,
                FileUploadConst::USER_AVATAR_PATH,
                $this->row->uuid,
            );

            $upload->uploadResize(300);
            $upload->saveFileInfo($this->row->avatar(), ['slug' =>  File::SLUG_FILE_PROFILE, 'ref_table' => $this->row->getTable()]);
        }

        if ($this->profile == 'employee') {
            $this->row->employee->update(
                $this->requestData->only(['dob', 'pob', 'gender', 'title'])
            );
        }

        $this->row->refresh();
    }

    public function updatePatient($data)
    {
        if (empty($this->row->patient)) {
            $medical_record = MedicalRecord::create(['name' => $this->row->name]);
            $medical_record->update(['number' => MedicalRecord::generateNumber($medical_record->id)]);
            Patient::create(['user_id' => $this->row->id, 'medical_record_id' => $medical_record->id]);

            $this->row->assignRole(RoleConst::PATIENT);
            $this->row->refresh();
        }

        if ($this->profile == 'patient-data-clinic') {
            $data = array_merge($data, [
                'data' => [
                    'drug_allergy' => $this->requestData->input('drug_allergy', []),
                    'skin_problem' =>  $this->requestData->input('skin_problem', []),
                ],
                'note' => $this->requestData->input('patient.note'),
            ]);

            if (empty($this->row->patient->branch)) {
                $data['branch_id'] = Branch::getId($this->requestData->input('branch_id'));
            }

            $this->row->patient->update($data);
        } else {

            $this->row->patient->update($data);

            if (!empty($this->row->patient->defaultIdentity)) {
                $this->row->patient->defaultIdentity->update([
                    'number' => $this->requestData->input('identity.ktp')
                ]);
            } else {
                $dt_idntty = Identity::prepareInsertKTP($this->row->patient);
                if ($dt_idntty['number'] = $this->requestData->input('identity.ktp')) {
                    $this->row->patient->defaultIdentity()->save(new Identity($dt_idntty));
                }
            }

            $data_address = [
                'address' => $this->requestData->input('address.address'),
                'zip' => $this->requestData->input('address.zip'),
                'phone' => $this->requestData->input('address.phone'),
                'mobile' => $this->requestData->input('address.whatsapp'),
                'whatsapp' => $this->requestData->input('address.whatsapp')
            ];

            $regency = Regency::getFirst($this->requestData->input('address.regency_id'));
            $suburb = Suburb::getFirst($this->requestData->input('address.suburb_id'));
            $area = Area::getFirst($this->requestData->input('address.area_id'));

            if ($area) {
                $data_address = array_merge($data_address, Address::prepareBaseLocation($area));
            }elseif ($suburb) {
                $data_address = array_merge($data_address, Address::prepareBaseLocation($suburb));
            }elseif ($regency) {
                $data_address = array_merge($data_address, Address::prepareBaseLocation($regency));
            }

            if (!empty($this->row->patient->defaultAddress)) {
                $this->row->patient->defaultAddress->update($data_address);
            } else {
                Address::create(Address::prepareAddressPatient($this->row->patient) + $data_address);
            }
        }
    }

    public function updateCustomer($data)
    {
        if (!empty($this->row->customer)) {
            $this->row->customer->update(array_merge($data, $this->requestData->only(['dob', 'pob', 'gender', 'name'])));
        }
    }
}
