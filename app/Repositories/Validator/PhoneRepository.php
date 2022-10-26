<?php

namespace App\Repositories\Validator;

use App\Repositories\BaseRepository;

class PhoneRepository extends BaseRepository
{
    public function preparePhoneValidation($phone)
    {
        if (empty($phone)) {
            return null;
        }

        $phone =  str_replace(
            ['+', '-', ' ', '.', ','],
            ['', '', '', '', ''],
            $phone
        );

        if (!empty($phone) && substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }


}
