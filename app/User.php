<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\UserProfileAction;
use Illuminate\Support\Facades\Route;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'email', 'password', 'user_type_id', 'user_situation_id', 'person_id', 'name', 'last_acess'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getProfile()
    {
        return Profile::find($this->token()->profile_id);
    }

    public function setProfile($profileId)
    {
        $token = $this->token();
        $scopes = array();
        $permissions = Permission::where(['profile_id' => $profileId])->get();
        $userProfile = $this->userProfiles()->where(['profile_id' => $profileId])->first();

        if (empty($userProfile)) return [
            'success' => false,
            'message' => 'Acesso não autorizado.'
        ];

        $userPActions = $userProfile->userProfileActions->pluck('code', 'action_id')->toArray();
        $routes = $this->getRouteModelArray();

        foreach ($permissions as $permission) {

            $path = $permission->cpath;
            $model = null;
            $metadata = [];
            $rules = [];

            if (isset($routes['api/' . $path]) && class_exists($routes['api/' . $path])) {
                $model = $routes['api/' . $path];
                $rules = $model::$rules;
                $model = new $model;

            }

            $actions = [];
            foreach ($permission->actions()->get() as $action) {
                $actions[$action->noun] = isset($userPActions[$action->id])
                    ? $userPActions[$action->id] : $action->code;
                if (!empty($model)) {
                    if (empty($metadata['rules'])) $metadata['rules'] = [];

                    $metadata['rules'][$action->noun] = !empty($rules[$action->noun])
                        ? $rules[$action->noun] : '';
                }
            }

            if (!empty($actions)) {
                $parentPermission = $permission;

                while (!empty($parentPermission->permission_id)) {
                    $parentPermission = $permission->permission;
                    $path = $parentPermission->cpath . '/' . $path;
                }

                $scopes[$path] = [
                    'actions' => $actions,
                    'rules' => !empty($metadata['rules']) ? $metadata['rules'] : []
                ];
            }
        }

        $token->update([
            'profile_id' => $profileId,
            'scopes' => $scopes
        ]);

        return [
            'success' => true,
            'message' => 'Perfil definido com sucesso!',
            'scopes' => $scopes
        ];
    }

    /**
     * Retorna uma array onde os índices são rotas e o valor é o caminho da classe
     * @return Array
     */
    public static function getRouteModelArray()
    {
        $arr = [];

        //Operações que iriam repetir a rota principal
        $operations = [
            'create', 'store', 'show', 'edit', 'update', 'destroy'
        ];

        foreach (Route::getRoutes()->getIterator() as $route) {
            if ($route->getPrefix() == 'api' && !in_array(
                substr($route->getName(), stripos($route->getName(), '.') + 1),
                $operations
            )) {
                $arr[$route->uri] = '\\App\\Models\\' . substr(
                    $route->getActionName(),
                    stripos($route->getActionName(), '\API\\') + 5,
                    stripos($route->getActionName(), 'APIController') - stripos($route->getActionName(), '\API\\') - 5
                );
            }
        }

        return $arr;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function userProfiles()
    {
        return $this->hasMany(\App\Models\UserProfile::class);
    }
}

/* Estrutura da sessão
session [
    'token1' : {
        access_id : 99,
        profile_id : 99,
        expires_at : 0000-00-00 00:00:00,
        scopes [
            'permission_cpath1' : {
                actions : [
                    'action_noun1' : 7,
                    'action_noun2' : 5
                ]
            },
            'permission_cpath1/cpath3' : {
                actions : [
                    'action_noun1' : 7,
                    'action_noun2' : 5
                ]
            },
            'permission_cpath2' : {
                'action_noun1' : 7,
                'action_noun2' : 5
            }
        ]
    }
]
*/
