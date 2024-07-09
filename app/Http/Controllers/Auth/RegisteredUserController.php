<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DeviceHistory;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\DistributionAutoAssign;
use App\Utilities\CodeGenerator;
use App\Utilities\Constants;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function confirmPost($token, DistributionAutoAssign $distributionAutoAssign)
    {
        $user = User::whereToken($token)->firstOrFail();

        $user->status = (int)Constants::USERSTATUSES['Active'];
        $user->save();

        $activationRequest = $user->activationRequests->first();

        if (!isset($activationRequest->device)) {
            \Auth::loginUsingId($user->id, true);

            return redirect(url('customer/serials'));
        }

        /** @var Device $device */
        $device = $activationRequest->device;

        if (isset($device->customer_id)) {
            \Auth::loginUsingId($user->id, true);

            return redirect(url('customer/serials'));
        }

        $activationRequest->code   = CodeGenerator::createAlgorithm($device->type->code_generator)->generate($activationRequest->device->serial);
        $activationRequest->status = Constants::REQUEST_STATUS_ACCEPTED;
        $activationRequest->save();

        $device->code        = CodeGenerator::createAlgorithm($device->type->code_generator)->generate($activationRequest->device->serial);
        $device->customer_id = $user->id;

        // resolve distribution_id
        if ((!$device->distribution_id || $device->distribution_id === Constants::DISTRIBUTION_UNASSIGNED_ID) &&
            $distributionId = $distributionAutoAssign->findDistributionIdByUserId($user->id)
        ) {
            $device->distribution_id = $distributionId;
        }

        // save device
        $device->save();

        // save device warranty started in the history
        $warranty = DeviceHistory::create([
            'device_id'   => $device->id,
            'task'        => Constants::TASKS['Activation_Key'],
            'code'        => $device->serial,
            'key'         => $device->code,
            'customer_id' => $user->id,
        ]);

        // save device warranty started in the device
        $device->warranty_started_at = $warranty->created_at->format('Y-m-d H:i:s');
        $device->save();

        // send customer code notification
        if ((int)$user->contact_method === (int)Constants::CONTACT_METHODS['Email']) {
            sendCustomerCodeNotification($user, $device->code);
        }

        // login and redirect
        \Auth::loginUsingId($user->id, true);

        return redirect(url('customer/serials'));
    }
}
