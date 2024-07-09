<?php

use App\Models\FileType;
use Illuminate\Database\Eloquent\Builder;

if (!function_exists('getDeviceFiles')) {
    /**
     * @param int      $distributionId
     * @param int|null $deviceTypeId
     * @param int|null $deviceTypeCategoryId
     * @param int|null $group
     *
     * @return array
     */
    function getDeviceFiles(int $distributionId, ?int $deviceTypeId = null, ?int $deviceTypeCategoryId = null, int $group = FileType::GROUP_GENERAL)
    {
        /** @var \App\Models\File[] $files */
        $files = \App\Models\File::query()
            ->where(static function (Builder $query) use ($distributionId) {
                $query
                    ->whereHas('distributions', static function (Builder $query) use ($distributionId) {
                        $query->where('distributions.id', $distributionId);
                    })
                    ->orWhereHas('distributions', static function (Builder $query) use ($distributionId) {
                        $query->where('distributions.parent_id', $distributionId);
                    });
            })
            ->where(static function (Builder $query) use ($deviceTypeId, $deviceTypeCategoryId) {
                $query
                    ->whereHas('deviceTypes', static function (Builder $query) use ($deviceTypeId) {
                        $query->where('device_types.id', $deviceTypeId);
                    })
                    ->orWhereHas('deviceTypeCategories', static function (Builder $query) use ($deviceTypeCategoryId) {
                        $query->where('device_type_categories.id', $deviceTypeCategoryId);
                    });
            })
            ->whereHas('fileType', static function (Builder $query) use ($group) {
                $query->where('group', $group);
            })
            ->where('is_ac_unit', false)
            ->with('fileType')
            ->get();

        /** @var \App\Models\File[] $files */
        $files2 = \App\Models\File::query()
            ->where(static function (Builder $query) {
                $query
                    ->whereDoesntHave('distributions')
                    ->orWhere('is_default', true);
            })
            ->where(static function (Builder $query) use ($deviceTypeId, $deviceTypeCategoryId) {
                $query
                    ->whereHas('deviceTypes', static function (Builder $query) use ($deviceTypeId) {
                        $query->where('device_types.id', $deviceTypeId);
                    })
                    ->orWhereHas('deviceTypeCategories', static function (Builder $query) use ($deviceTypeCategoryId) {
                        $query->where('device_type_categories.id', $deviceTypeCategoryId);
                    });
            })
            ->whereHas('fileType', static function (Builder $query) use ($group) {
                $query->where('group', $group);
            })
            ->where('is_ac_unit', false)
            ->with('fileType')
            ->get();

        $files = $files->merge($files2);

        $tabFiles = [];
        foreach ($files as $file) {
            if (!array_key_exists($file->fileType->tab_name, $tabFiles)) {
                $tabFiles[$file->fileType->tab_name] = [];
            }

            $tabFiles[$file->fileType->tab_name][$file->id] = $file;
        }

        return $tabFiles;
    }
}

if (!function_exists('getDeviceImage')) {
    /**
     * @param int|null $distributionId
     * @param int|null $deviceTypeId
     * @param int|null $deviceTypeCategoryId
     *
     * @return \App\Models\File|null
     */
    function getDeviceImage(?int $distributionId = null, ?int $deviceTypeId = null, ?int $deviceTypeCategoryId = null)
    {
        $group = FileType::GROUP_GENERAL;

        if ($distributionId) {
            $image = App\Models\File::query()
                ->where(static function (Builder $query) use ($distributionId) {
                    $query
                        ->whereHas('distributions', static function (Builder $query) use ($distributionId) {
                            $query->where('distributions.id', $distributionId);
                        })
                        ->orWhereHas('distributions', static function (Builder $query) use ($distributionId) {
                            $query->where('distributions.parent_id', $distributionId);
                        });
                })
                ->where(static function (Builder $query) use ($deviceTypeId, $deviceTypeCategoryId) {
                    $query
                        ->whereHas('deviceTypes', static function (Builder $query) use ($deviceTypeId) {
                            $query->where('device_types.id', $deviceTypeId);
                        })
                        ->orWhereHas('deviceTypeCategories', static function (Builder $query) use ($deviceTypeCategoryId) {
                            $query->where('device_type_categories.id', $deviceTypeCategoryId);
                        });
                })
                ->whereHas('fileType', static function (Builder $query) use ($group) {
                    $query->where('group', $group);
                })
                ->where('is_ac_unit', true)
                ->first();
        }

        if (!isset($image)) {
            $image = App\Models\File::query()
                ->where(static function (Builder $query) use ($deviceTypeId, $deviceTypeCategoryId) {
                    $query
                        ->whereHas('deviceTypes', static function (Builder $query) use ($deviceTypeId) {
                            $query->where('device_types.id', $deviceTypeId);
                        })
                        ->orWhereHas('deviceTypeCategories', static function (Builder $query) use ($deviceTypeCategoryId) {
                            $query->where('device_type_categories.id', $deviceTypeCategoryId);
                        });
                })
                ->whereHas('fileType', static function (Builder $query) use ($group) {
                    $query->where('group', $group);
                })
                ->where('is_ac_unit', true)
                ->where('is_default', true)
                ->first();
        }

        return $image;
    }
}
