<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Config;
use Exception;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, ResponseTraits;

    protected $table = 'users';

    public CONST MANAGER = 'manager';
    public CONST ADMIN   = 'admin';
    public CONST USER    = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'role_id',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation with role
     *
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get admin
     *
     * @return void
     */
    public function getAdmins(){
        try{
            $roleAdmin = Role::where ('name', Config::get('auth.roles.admin'))->first();
            $admins = User::where([
                ['role_id', $roleAdmin->id],
                ['is_deleted', false]])
                ->orderBy('id', 'DESC')
                ->get();
            $status = true;
            $message = null;
            $data = $admins;
        } catch(Exception $e){
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add brand
     *
     * @param  mixed $request
     * @return void
     */
    public function addAccount($request){
        try{
            if (User::where('email', $request->email)->first()){
                $message = 'Exist email !!!';
                $status = false;
                return $this->responseData($status, $message);
            }
            if (User::where('username', $request->username)->first()){
                $message = 'Exist user name !!!';
                $status = false;
                return $this->responseData($status, $message);
            }

            $role = Role::where('name', Config::get('auth.roles.admin'))->first();
            $account = new User();
            $account->name = $request->name;
            $account->email = $request->email;
            $account->username = $request->username;
            $account->phone = $request->phone;
            $account->password = Hash::make('123456');
            $account->role_id = $role->id;
            $account->save();
            $status = true;
            $message = 'Add account successful !';
        }catch(Exception $e){
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Delete product
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteAdmin($id){
        try{
            $status = false;
            $message = "Can't delete account";
            $admin = User::find($id);
            if($admin){
                $admin->is_deleted = true;
                $admin->save();
                $status = true;
                $message = "Delete account successful !";
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }
}
