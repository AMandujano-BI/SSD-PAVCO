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
        'name',
        'email',
        'password',
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

    public function getUsers($type){

        $users = (new static)::with([
            'company'
        ])->where('status','!=',0)->get();
        return $users;
    }
    public function createUser($request)
    {
        DB::beginTransaction();
        try {
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;
            $userFind = (new static)::where('email', $email)->first();
            if ($userFind != null) {
                DB::rollback();
                return [
                    'ok' => false,
                    'message' => 'Duplicate Email',
                    'value' => 0
                ];
            }
            $user = (new static)::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            $user->save();
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
                'message' => $e,
                'value' => 0
            ];
        }
    }
    // ----------------RELATIONS
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
