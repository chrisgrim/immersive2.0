<?php

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Log;

class LoginResponse implements LoginResponseContract
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function toResponse($request)
    {
        return $request->wantsJson()
                    ? new JsonResponse(['user' => $this->user], 200)
                    : redirect(config('fortify.home'));
    }
}