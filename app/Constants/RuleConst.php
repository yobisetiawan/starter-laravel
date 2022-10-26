<?php

namespace App\Constants;

class RuleConst
{

    const MAX_STRING = 'max:120';
    const MAX_TEXT = 'max:1000';
    const MAX_FILE = 'max:5000';
    const MIME_FILE = 'mimes:jpeg,jpg,png';
    const GENDER = 'in:1,2';
    const BASE_USERNAME = 'min:6|max:30';
    const BASE_PHONE = 'numeric|digits_between:8,25';
    const DATE_FORMAT = 'date_format:Y-m-d';
}
