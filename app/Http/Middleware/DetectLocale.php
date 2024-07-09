<?php

namespace App\Http\Middleware;

use App\Models\CountryLanguage;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class DetectLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            if (!session()->has('locale')) {
                //App::setLocale(Auth::user()->language->name);
                if (Auth::user()->language) {
                    session()->put('locale', Auth::user()->language->name);
                }
            }
        } else {
            if (!session()->has('locale')) {
                $country = geoip()->getLocation()->iso_code;
                $language = CountryLanguage::where('country', $country)->first();//
                if (isset($language->language)) {
                    $localed = $language->language->name;
                    session()->put('locale', $localed);
                }
                //->language->name;
            }
        }
        app()->setLocale(strtolower(session()->get('locale')));
        return $next($request);
    }
}
