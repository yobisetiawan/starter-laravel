<?php

namespace App\Repositories\Auth;


use App\Models\Base\Token;
use App\Notifications\SuccessResetPassword;
use App\Repositories\Base\TokenRepository;
use App\Repositories\BaseRepository;


class ResetPasswordRepository extends BaseRepository
{
    public function notifyAfterResetPassword($user)
    {
        $tokenRepo = new TokenRepository;

        $tokenRepo->revokeTokens($user, Token::VERIFY_RESET_PASSWORD);

        $user->notify(new SuccessResetPassword());
    }
}
