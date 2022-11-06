<?php

namespace App\Repositories\Base;

use App\Models\Base\Token;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class TokenRepository extends BaseRepository
{
    public function revokeTokens(User $user, string $purpose)
    {
        Token::where('user_id', $user->id)
            ->where('active', true)
            ->where('purpose', $purpose)->update([
                'active' => false,
            ]);
    }


    public function setTokenActivatedAt(Token $token)
    {
        return $token->update([
            'active' => false,
            'activated_at' => Carbon::now(),
        ]);
    }

    public function getToken(User $user, string $token, string $purpose, string $key = '')
    {
        $token = Token::where('user_id', $user->id)
            ->where('token', $token)
            ->where('active', true)
            ->where('purpose', $purpose);

        if (!empty($key)) {
            $token->where('key', $key);
        }

        $token = $token->first();

        if ($token && Carbon::parse($token->expired_at) > Carbon::now()) {
            return $token;
        }

        abort(404, "Invalid code token, code token may expired or not found");
    }
}
