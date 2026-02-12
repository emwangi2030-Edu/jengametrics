<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'parent_user_id',
        'user_type',
        'project_id',
        'has_project',
        'can_manage_boq',
        'can_manage_materials',
        'can_manage_labour',
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'can_manage_boq' => 'boolean',
            'can_manage_materials' => 'boolean',
            'can_manage_labour' => 'boolean',
        ];
    }


    public function get_gravatar( $s = 40, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {

        $email = $this->email;
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";

        if( ! empty($this->photo)) {
            $url = avatar_img_url($this->photo, $this->photo_storage);
        }

        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }

        return $url;
    }


    public function is_admin(){
        if ($this->user_type == 'admin'){
            return true;
        }
        return false;
    }

    public function is_client(){
        if ($this->user_type == 'user'){
            return true;
        }
        return false;
    }

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    public function parentUser()
    {
        return $this->belongsTo(self::class, 'parent_user_id');
    }

    public function subAccounts()
    {
        return $this->hasMany(self::class, 'parent_user_id');
    }

    public function isSubAccount(): bool
    {
        return !is_null($this->parent_user_id);
    }

    public function hasRoleAccess(string $role): bool
    {
        if (!$this->isSubAccount()) {
            return true;
        }

        switch ($role) {
            case 'boq':
                return (bool) $this->can_manage_boq;
            case 'materials':
                return (bool) $this->can_manage_materials;
            case 'labour':
                return (bool) $this->can_manage_labour;
            default:
                return false;
        }
    }
}
