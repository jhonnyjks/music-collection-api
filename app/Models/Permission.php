<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Permission
 * @package App\Models
 * @version February 7, 2019, 5:11 am UTC
 *
 * @property \App\Models\Profile profile
 * @property \Illuminate\Database\Eloquent\Collection Action
 * @property \Illuminate\Database\Eloquent\Collection accessProfiles
 * @property \Illuminate\Database\Eloquent\Collection activityTaxes
 * @property \Illuminate\Database\Eloquent\Collection beneficiaries
 * @property \Illuminate\Database\Eloquent\Collection buildCorrections
 * @property \Illuminate\Database\Eloquent\Collection owners
 * @property \Illuminate\Database\Eloquent\Collection personActivities
 * @property \Illuminate\Database\Eloquent\Collection profileCities
 * @property \Illuminate\Database\Eloquent\Collection personalDetails
 * @property \Illuminate\Database\Eloquent\Collection tributeCovenants
 * @property \Illuminate\Database\Eloquent\Collection serviceActivities
 * @property \Illuminate\Database\Eloquent\Collection accessProfileActions
 * @property \Illuminate\Database\Eloquent\Collection activityAddresses
 * @property \Illuminate\Database\Eloquent\Collection streetBlocks
 * @property \Illuminate\Database\Eloquent\Collection persons
 * @property \Illuminate\Database\Eloquent\Collection streets
 * @property bigInteger profile_id
 * @property bigInteger permission_id
 * @property smallInteger priority
 * @property string cpath
 */
class Permission extends Model
{
    use SoftDeletes;

    public $table = 'permissions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'profile_id',
        'permission_id',
        'priority',
        'cpath'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cpath' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function profile()
    {
        return $this->belongsTo(\App\Models\Profile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function actions()
    {
        return $this->hasMany(\App\Models\Action::class);
    }
}
