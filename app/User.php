<?php

namespace App;

use App\Observers\UserObserver;
use App\Scopes\CompanyScope;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Trebol\Entrust\Traits\EntrustUserTrait;
use Illuminate\Auth\Authenticatable;

use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, JWTSubject
{
    use Notifiable, EntrustUserTrait, Authenticatable; //, CanResetPassword
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'login', 'status', 'image', 'provider', 'provider_id', 'gender', 'locale', 'onesignal_player_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }
    public $dates = ['created_at', 'updated_at'];


    public $appends = ['unreadNotifications', 'image_url', 'modules','user_other_role',];

    protected static function boot()
    {
        parent::boot();

        static::observe(UserObserver::class);

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('users.status', '=', 'active');
        });

        static::addGlobalScope(new CompanyScope);
    }

    public function scopedeActive($query)
    {
        return $query->where('users.status', 'deactive');
    }
    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        $slack = SlackSetting::first();
        return $slack->slack_webhook;
    }

    public function getUnreadNotificationsAttribute()
    {
        return $this->unreadNotifications()->where('type', '=', 'App\Notifications\NewNotice')->get();
    }

    public function routeNotificationForOneSignal()
    {
        return $this->onesignal_player_id;
    }

    public function client()
    {
        return $this->hasMany(ClientDetails::class, 'user_id');
    }

    public function lead_agent()
    {
        return $this->hasMany(LeadAgent::class, 'user_id');
    }

    public function client_detail()
    {
        return $this->hasOne(ClientDetails::class, 'user_id')
            ->where('client_details.company_id', company()->id);
    }

    public function client_details()
    {
        return $this->hasOne(ClientDetails::class, 'user_id')
            ->where('client_details.company_id', company()->id);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasMany(EmployeeDetails::class, 'user_id');
    }


    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetails::class, 'user_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function member()
    {
        return $this->hasMany(ProjectMember::class, 'user_id');
    }

    public function role()
    {
        return $this->hasMany(RoleUser::class, 'user_id');
    }

    public function attendee()
    {
        return $this->hasMany(EventAttendee::class, 'user_id');
    }

    public function agent()
    {
        return $this->hasMany(TicketAgentGroups::class, 'agent_id');
    }

    public function group()
    {
        return $this->hasMany(EmployeeTeam::class, 'user_id');
    }

    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }

    public function skills()
    {
        return EmployeeSkill::select('skills.name')->join('skills', 'skills.id', 'employee_skills.skill_id')->where('user_id', $this->id)->pluck('name')->toArray();
    }

    public function skillIds()
    {
        return EmployeeSkill::select('skills.id')->join('skills', 'skills.id', 'employee_skills.skill_id')->where('user_id', $this->id)->pluck('id')->toArray();
    }

    public static function allClients($per_page = null)
    {
        $query = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->select('users.id', 'client_details.name', 'client_details.email', 'client_details.created_at', 'client_details.company_name');
        if($per_page){
            $clients = $query->paginate($per_page);
        }else{
            $clients = $query->get();
        }


        return $clients;
    }

    public static function allActiveClients($per_page = null)
    {
        $query = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->select('users.id', 'client_details.name', 'client_details.email', 'client_details.created_at', 'client_details.company_name')
            ->where('users.status', 'active');;
        if($per_page){
            $clients = $query->paginate($per_page);
        }else{
            $clients = $query->get();
        }


        return $clients;
    }

    public static function allOperators($exceptId = null)
    {
        $operators = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '=', 'vehicle_operator');

        if (!is_null($exceptId)) {
            $users->where('users.id', '<>', $exceptId);
        }

        $operators->groupBy('users.id');
        return $operators->get();
    }

    public static function deActiveClients()
    {
        $clients = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->select('users.id', 'client_details.name', 'client_details.email', 'client_details.created_at', 'client_details.company_name')
            ->where('users.status', 'deactive')
            ->get();

        return $clients;
    }

    public static function activeClients($per_page = null)
    {
        $query = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->select('users.id', 'client_details.name', 'client_details.email', 'client_details.created_at', 'client_details.company_name')
            ->where('users.status', 'active');
           if($per_page){
               $clients = $query->paginate($per_page);
           }else{
               $clients = $query->get();
           }

        return $clients;
    }

    public static function allSuperAdmin()
    {
        return User::withoutGlobalScope('active')
            ->where('super_admin', '1')
            ->get();
    }

    public static function allEmployees($exceptId = null)
    {
        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->where('roles.name', '<>', 'vehicle_operator');

        if (!is_null($exceptId)) {
            $users->where('users.id', '<>', $exceptId);
        }

        $users->groupBy('users.id');
        return $users->get();
    }

    public static function employeVehicles($exceptId = null)
    {
        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('vehicles', 'vehicles.operator_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->where('roles.name', '<>', 'vehicle_operator');

        if (!is_null($exceptId)) {
            $users->where('users.id', '<>', $exceptId);
        }

        $users->groupBy('users.id');
        return $users->get();
    }

    public static function allEmployeesByPage($per_page,$exceptId = null)
    {
        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->where('roles.name', '<>', 'vehicle_operator');

        if (!is_null($exceptId)) {
            $users->where('users.id', '<>', $exceptId);
        }

        $users->groupBy('users.id');
        return $users->paginate($per_page);
    }


    public static function allAdmins($exceptId = null)
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'admin');

        if (!is_null($exceptId)) {
            $users->where('users.id', '<>', $exceptId);
        }

        return $users->get();
    }

    public static function firstAdmin()
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'admin')
            ->orderBy('users.id', 'asc');
        return $users->first();
    }

    public static function teamUsers($teamId)
    {
        $users = User::join('employee_teams', 'employee_teams.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('employee_teams.team_id', $teamId);

        return $users->get();
    }

    public static function userListLatest($userID, $term)
    {

        if ($term) {
            $termCnd = "and users.name like '%$term%'";
        } else {
            $termCnd = '';
        }

        $messageSetting = MessageSetting::first();

        if (auth()->user()->hasRole('admin')) {
            if ($messageSetting->allow_client_admin == 'no') {
                $termCnd .= "and roles.name != 'client'";
            }
        } elseif (auth()->user()->hasRole('employee')) {
            if ($messageSetting->allow_client_employee == 'no') {
                $termCnd .= "and roles.name != 'client'";
            }
        } elseif (auth()->user()->hasRole('client')) {
            if ($messageSetting->allow_client_admin == 'no') {
                $termCnd .= "and roles.name != 'admin'";
            }
            if ($messageSetting->allow_client_employee == 'no') {
                $termCnd .= "and roles.name != 'employee'";
            }
        }

        $query = DB::select("SELECT * FROM ( SELECT * FROM (
                    SELECT users.id,'0' AS groupId, users.name,  users.image, users.chat_status, users_chat.created_at as last_message, users_chat.message, users_chat.message_seen, users_chat.user_one, users_chat.from, designations.name as designation_name
                    FROM users
                    INNER JOIN users_chat ON users_chat.from = users.id
                    LEFT JOIN role_user ON role_user.user_id = users.id
                    LEFT JOIN roles ON roles.id = role_user.role_id
                    LEFT JOIN employee_details ON employee_details.user_id = users.id
                    LEFT JOIN designations ON designations.id = employee_details.designation_id
                    WHERE users_chat.to = $userID $termCnd
                    UNION
                    SELECT users.id,'0' AS groupId, users.name,users.image, users.chat_status, users_chat.created_at  as last_message, users_chat.message, users_chat.message_seen, users_chat.user_one, users_chat.from, designations.name as designation_name
                    FROM users
                    INNER JOIN users_chat ON users_chat.to = users.id
                    LEFT JOIN role_user ON role_user.user_id = users.id
                    LEFT JOIN roles ON roles.id = role_user.role_id
                    LEFT JOIN employee_details ON employee_details.user_id = users.id
                    LEFT JOIN designations ON designations.id = employee_details.designation_id
                    WHERE users_chat.from = $userID  $termCnd
                    ) AS allUsers
                    ORDER BY  last_message DESC
                    ) AS allUsersSorted
                    GROUP BY id
                    ORDER BY  last_message DESC");

        return $query;
    }

    public static function isAdmin($userId)
    {
        $user = User::find($userId);
        if($user){
            return $user->hasRole('admin') ? true : false;
        }
        return false;
    }

    public static function isClient($userId)
    {
        $user = User::withoutGlobalScope(CompanyScope::class)->find($userId);
        if($user){
            return $user->hasRole('client') ? true : false;
        }
       return false;
    }

    public static function isEmployee($userId)
    {
        $user = User::find($userId);
        if($user){
            return $user->hasRole('employee') ? true : false;
        }
        return false;
    }

    public function getModulesAttribute()
    {
        $user = auth()->user();

        if($user){

            $module = new ModuleSetting();

            if ($user->hasRole('admin')) {
                $module = $module->where('type', 'admin');

            } elseif ($user->hasRole('client')) {
                $module = $module->where('type', 'client');

            } elseif ($user->hasRole('employee')) {
                $module = $module->where('type', 'employee');
            }

            // $module = $module->where('status', 'active');
            $module->select('module_name');

            $module = $module->get();
            $moduleArray = [];
            foreach ($module->toArray() as $item) {
                array_push($moduleArray, array_values($item)[0]);
            }

            return $moduleArray;
        }

        return [];
    }

    public function getNameAttribute($value)
    {
        if (!is_null($this->id) && $this->isClient($this->id)) {
            $client = ClientDetails::select('id', 'company_id', 'name')
                ->where(
                    'user_id', $this->id
                )
                ->first();

                if(!empty($client)){
                    return $client['name'];
                }   
            
        }

        return $value;
    }

    public function getEmailAttribute($value)
    {
        if (!is_null($this->id) && $this->isClient($this->id)) {
            $client = ClientDetails::select('id', 'company_id', 'email')
                ->where(
                    'user_id', $this->id
                )
                ->first();

                if(!empty($client))
                    return $client['email'];
                else
                    return $value;
        }

        return $value;
    }

    public function getImageAttribute($value)
    {
        if (!is_null($this->id) && $this->isClient($this->id)) {
            $client = ClientDetails::select('id', 'company_id', 'image')
                ->where(
                    'user_id', $this->id
                )
                ->first();

            if(!empty($client))
                return $client['image'];
            else
                return $value;
        }

        return $value;
    }

    public function getImageUrlAttribute()
    {
        return ($this->image) ? asset_url('avatar/' . $this->image) : asset('img/default-profile-3.png');
    }

    public function getMobileAttribute($value)
    {
        if (!is_null($this->id) && $this->isClient($this->id)) {
            $client = ClientDetails::select('id', 'company_id', 'mobile')
                ->where(
                    'user_id', $this->id
                )
                ->first();
            if(!empty($client))
                return $client['mobile'];
            else
                return $value;
        }

        return $value;
    }

    public function getUserOtherRoleAttribute()
    {
        $userRole = null;
        $roles = Role::where('name', '<>', 'client')
            ->orderBy('id', 'asc')->get();
        foreach ($roles as $role) {
            foreach ($this->role as $urole) {
                if ($role->id == $urole->role_id) {
                    $userRole = $role->name;
                }
                if ($userRole == 'admin') {
                    break;
                }
            }
        }
        return $userRole;
    }



}
