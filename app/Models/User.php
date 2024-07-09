<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Utilities\Constants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\Translation\TranslatorInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public const SETTINGS_AC_DATA_LAST_DATE = 'ac_data_last_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'created_by',
        'updated_by',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

//    protected $guard_name = 'api';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function boot()
    {
        parent::boot();
    }


    /**
     * @return HasMany|Builder
     */
    public function devices()
    {
        return $this->hasMany(Device::class, 'user_id');
    }

    /**
     * @return HasMany|Builder
     */
    public function customerDevices()
    {
        return $this->hasMany(Device::class, 'customer_id');
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (int)$this->status === (int)Constants::USERSTATUSES['Active'];
    }

    /**
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return (int)$this->user_type === (int)Constants::USER_TYPE_ADMIN;
    }

    /**
     * @return bool
     */
    public function isActiveSuperAdminCanReceiveEmails(): bool
    {
        return $this->isSuperAdmin() && $this->isActiveCanReceiveEmails();
    }

    /**
     * @return bool
     */
    public function isActiveCanReceiveEmails(): bool
    {
        return $this->isActive() && $this->can_receive_emails;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array(Auth::user()->user_type, Constants::USER_ADMINS, false);
    }

    /**
     * @return bool
     */
    public function isHotLine(): bool
    {
        return (int)$this->user_type === (int)Constants::USER_TYPE_HOT_LINE;
    }

    /**
     * @return bool
     */
    public function isDistributionManager(): bool
    {
        return (int)$this->user_type === (int)Constants::USER_TYPE_DISTRIBUTION_MANAGER;
    }

    /**
     * @return bool
     */
    public function isTad(): bool
    {
        return (int)$this->user_type === (int)Constants::USER_TYPE_TAD;
    }

    /**
     * @return bool
     */
    public function isWOW(): bool
    {
        return (int)$this->user_type === (int)Constants::USER_TYPES['WOW'];
    }

    /**
     * @return bool
     */
    public function isCustomer(): bool
    {
        return (int)$this->user_type === (int)Constants::USER_TYPE_CUSTOMER;
    }

    /**
     * @param QueryBuilder $query
     *
     * @return QueryBuilder|\App\User
     */
    public function scopeCustomer($query)
    {
        return $query->where('user_type', Constants::USER_TYPE_CUSTOMER);
    }

    /**
     * @param QueryBuilder $query
     *
     * @return QueryBuilder|User
     */
    public function scopeWOW($query)
    {
        return $query->where('user_type', Constants::USER_TYPES['WOW']);
    }

    /**
     * @param QueryBuilder $query
     *
     * @return QueryBuilder|User
     */
    public function scopeSuperAdmin($query)
    {
        return $query->where('user_type', Constants::USER_TYPE_ADMIN);
    }

    /**
     * @param QueryBuilder $query
     *
     * @return QueryBuilder|User
     */
    public function scopeActiveSuperAdminCanReceiveEmails($query)
    {
        $query = $this->scopeSuperAdmin($query);
        $query = $this->scopeActiveCanReceiveEmails($query);

        return $query;
    }

    /**
     * @param QueryBuilder $query
     *
     * @return QueryBuilder|User
     */
    public function scopeActiveCanReceiveEmails($query)
    {
        $query
            ->where('status', Constants::USERSTATUSES['Active'])
            ->where('can_receive_emails', true);

        return $query;
    }

    /**
     * @param QueryBuilder $query
     *
     * @return QueryBuilder|User
     */
    public function scopeAdmin($query)
    {
        return $query->whereIn('user_type', Constants::USER_ADMINS);
    }

    /**
     * @return BelongsTo|Builder
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'languages_id');
    }

    public function getUserTypeTranslatedAttribute(): string
    {
        return getUserTypeTranslated((int)$this->user_type);
    }

    /**
     * @return HasMany|Builder
     */
    public function activationRequests(): HasMany
    {
        return $this->hasMany(ActivationRequest::class, 'user_id');
    }

    /**
     * @return string|TranslatorInterface
     */
    public function getStatusTranslatedAttribute()
    {
        if ($this->trashed()) {
            return trans('customer.admin.titles.deleted');
        }
        if ($this->status) {
            return trans('customer.admin.titles.active');
        }

        return trans('customer.admin.titles.not-active');
    }

    /**
     * @return BelongsToMany|Builder
     */
    public function distributions(): BelongsToMany
    {
        return $this->belongsToMany(
            Distribution::class,
            'admin_distributions',
            'user_id',
            'distribution_id'
        )->withTimestamps();
    }

    /**
     * @param string                $key
     * @param string|int|float|null $value
     */
    public function setSetting(string $key, $value): void
    {
        $settings       = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;
    }

    /**
     * @param string $key
     *
     * @return string|int|float|null
     */
    public function getSetting(string $key)
    {
        if (!$this->settings || !isset($this->settings[$key])) {
            return null;
        }

        return $this->settings[$key];
    }

    /**
     * @return bool
     */
    public function hasActiveBuses(): bool
    {
        return (bool)$this
            ->customerDevices()
            ->where('status', Device::STATUS_ACTIVE)
            ->whereHas(
                'type',
                static function (Builder $query) {
                    $query->where('is_bus', 1);
                }
            )
            ->count();
    }

    public function isInDoorService(): bool
    {
        return (int)$this->user_type === (int)Constants::USER_TYPE_INDOOR_SERVICE;
    }

    public function tasksAssigned()
    {
        return $this->hasMany(Task::class, 'assign_to');
    }

    public function tasksCreated()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
}
