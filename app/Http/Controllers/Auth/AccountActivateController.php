<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utilities\Constants;
use function date;

class AccountActivateController extends Controller
{
    public function index(?string $token = null)
    {
        if ($user = $token ? User::whereActivationToken($token)->first() : null) {
            $user->status           = Constants::USERSTATUSES['Active'];
            $user->logged_at        = date('Y-m-d H:i:s');
            $user->activation_token = null;
            $user->save();
            return redirect(route('login'))->with('status', trans('register.account_activated'));
        }

        return redirect(route('login'))->with('error', trans('register.account_activated_wrong_url'));
    }
}
