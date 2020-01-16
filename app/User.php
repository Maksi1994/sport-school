<?php

namespace App;

use App\Notifications\UserRegistration;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function savedImages()
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function favorites() {
        return $this->hasMany(Favorite::class);
    }

    public static function createOne(Request $request)
    {
        $avatarPath = null;

        if ($request->hasFile('avatar') && in_array($request->file('avatar')->getMimeType(), Image::MIME_TYPES)) {
            $avatarPath = Storage::disk('space')->putFile('users-avatars', $request->avatar);
        }

        $user = User::create(array_merge(
                [
                    'avatar' => $avatarPath,
                    'password' => bcrypt($request->password),
                ],
                $request->only(['first_name', 'last_name', 'email'])
            )
        );

        $user->notify(new UserRegistration());
    }
}
