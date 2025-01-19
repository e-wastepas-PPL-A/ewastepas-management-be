<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\OtpMail;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * 
     */
    protected $table = 'management';
    protected $primaryKey = 'management_id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'date_of_birth',
        'address',
        'phone',
        'photo',
        'is_verified',
        'is_admin',
        'otp_code',
        'otp_expiry',
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
        ];
    }

    public function generateOtp()
    {

        $otp = random_int(100000, 999999);
        $this->otp_code = $otp;
        $this->otp_expiry = Carbon::now('Asia/Jakarta')->addMinutes(2)->format('Y-m-d H:i:s');
        $this->save();
        Mail::to($this->email)->send(new OtpMail($this->name, $otp));
    }

    public function isOtpValid(string $otpCode): bool
    {
        if ($this->otp_expiry < Carbon::now() || $this->otp_code !== $otpCode) {
            $this->update([
                'otp_code' => null,
                'otp_expiry' => null,
            ]);
            return false;
        }

        return true;
    }

    public function resendOtp()
    {
        $this->generateOtp();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
