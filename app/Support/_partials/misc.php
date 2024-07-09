<?php

use App\Services\Wds\WdsApi;
use App\Utilities\Constants;

if (!function_exists('updateCurrentUrl')) {
    /**
     * @param array $paramsToReplace
     *
     * @return string
     */
    function updateCurrentUrl(array $paramsToReplace)
    {
        $url   = Request::url();
        $query = Request::query();

        /*
        if (isset($paramsToReplace['pp'])) {
            $currentPage = (int)Request::query('page');
            $currentPage = $currentPage > 0 ? $currentPage : 1;

            $currentPP = (int)Request::query('pp');
            $currentPP = $currentPP > 0 ? $currentPP : 25;

            $newPage = round((($currentPage - 1) * $currentPP + 0.5) / $paramsToReplace['pp']);

            $newPage = $newPage > 0 ? $newPage : 1;

            $paramsToReplace['page'] = $newPage;
        }
        */

        return $url . '?' . http_build_query(array_merge($query, $paramsToReplace));
    }
}

if (!function_exists('getSortingClass')) {
    /**
     * @param string $current
     * @param string $target
     * @param bool   $isAscDirection
     *
     * @return string
     */
    function getSortingClass($current, $target, $isAscDirection)
    {
        if ($current !== $target) {
            return 'sorting';
        }

        return $isAscDirection ? 'sorting_asc' : 'sorting_desc';
    }
}

if (!function_exists('isActiveRoute')) {
    /**
     * @param string $route
     * @param string $output
     *
     * @return string
     */
    function isActiveRoute(string $route, string $output = 'active')
    {
        if (Route::currentRouteName() === $route) {
            return $output;
        }

        return '';
    }
}

if (!function_exists('getCountriesArray')) {
    function getCountriesArray(): array
    {
        $countries = array_values(Constants::COUNTRIES);
        return array_combine($countries, $countries);
    }
}

if (!function_exists('getWdsApi')) {
    function getWdsApi(): WdsApi
    {
        static $wdsApi;

        if (!$wdsApi instanceof WdsApi) {
            $wdsApi = app(WdsApi::class);
        }

        return $wdsApi;
    }
}

if (!function_exists('getUserTypeTranslated')) {
    function getUserTypeTranslated(int $userType): string
    {
        switch ($userType) {
            case Constants::USER_ROLE['Admin']:
                return trans('administrators.types.admin');
            case Constants::USER_ROLE['USER']:
                return trans('administrators.types.user');
            default:
                return (string) $userType;
        }
    }
}
