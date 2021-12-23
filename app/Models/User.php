<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'lastname',
        'username',
        'status',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public static function getUsers($type)
    {

        $users = (new static)::with([
            'company',
            'rols'
        ])->where('status', '!=', 0)->get();
        return $users;
    }
    public static function createUser($request)
    {
        DB::beginTransaction();
        try {
            $name = $request->name;
            $lastname = $request->lastname;
            $username = $request->username;
            $email = $request->email;
            $password = $request->password;
            $company_id= $request->company_id;
            $rols = $request->rols;

            $userFind = (new static)::where('email', $email)->first();
            if ($userFind != null) {
                DB::rollback();
                return [
                    'ok' => false,
                    'message' => 'Duplicate Email',
                    'value' => 0
                ];
            }

            $userCreate = (new static)::create([
                'name' => $name,
                'lastname' => $lastname,
                'username' => $username,
                'company_id' => $company_id,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            //Add rols

            $userCreate->rols()->attach($rols);

            $userCreate->save();
            $user= (new static)::with(['company','rols'])->where('username', $username)->first();
            DB::commit();

            return [
                'ok' => true,
                'message' => 'User was added successfully',
                'value' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' => 0
            ];
        }
    }


    public static function updateUser($request)
    {
        DB::beginTransaction();
        try {
            $userUpdate = (new static)::find($request->id);
            if($userUpdate == null){
                DB::rollBack();
                return [
                    'ok' => false,
                    'message' =>'User not found',
                    'value' => 0
                ];
            }
            $userUpdate->name = $request->name;
            $userUpdate->lastname = $request->lastname;
            $userUpdate->email = $request->email;
            $userUpdate->company_id = $request->company_id;
            $userUpdate->save();
            $userUpdate->rols()->sync($request->rols);
            $user= (new static)::with(['company','rols'])->where('username', $request->username)->first();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'User was updated successfully',
                'value' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' => 0
            ];
        }
    }
    public static function deleteUser($id)
    {
        DB::beginTransaction();
        try {
            $user = (new static)::find($id);
            DB::rollBack();
            if ($user == null) {
                return [
                    'ok' => false,
                    'message' => 'User not Found',
                    'value' => 0
                ];
            }
            $user->status = 0;
            $user->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'User was deleted successfully',
                'value' => $user,
            ];
        } catch (\Exception $e) {

            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' => 0
            ];
        }
    }


    public static function resetPassword($request)
    {
        DB::beginTransaction();
        try {

            $user = (new static)::where('username', $request->username)->first();
            if ($user == null) {
                DB::rollBack();
                return [
                    'ok' => false,
                    'message' => 'User not found',
                    'value' => 0
                ];
            }
            $user->forceFill(['password' => Hash::make($request['password']),])->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Password changed successfully',
                'value' => $user,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' => 0
            ];
        }
    }
    // ----------------RELATIONS
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function rols()
    {
        return $this->belongsToMany(Rol::class);
    }
}
