<?php

namespace App\Repositories\Auth;

use App\Constants\TokenConstant;
use App\Models\Base\Token;
use App\Repositories\Base\TokenRepository;
use App\Repositories\BaseRepository;


class VerifyPasswordRepository extends BaseRepository
{
    public function verifyResetPassword($user, $code_verify)
    {
        $tokenRepo = new TokenRepository;

        $tokenRepo->getToken($user, $code_verify, Token::VERIFY_RESET_PASSWORD);
    }
}
