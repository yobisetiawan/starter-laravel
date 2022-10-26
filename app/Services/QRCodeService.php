<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{

    public function base64($data, $size = 300)
    {
        return 'data:image/png;base64,' . base64_encode(
            QrCode::format('png')->size($size)->generate($data)
        );
    }
}
