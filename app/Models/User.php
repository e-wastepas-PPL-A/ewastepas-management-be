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

class User extends Authenticatable
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
        'management_id',
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
        'google_id',
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
        $this->otp_expiry = Carbon::now()->addMinutes(10);
        $this->save();
        // Kirim OTP ke email (gunakan mailable yang sudah ada jika ada)
        Mail::to($this->email)->send(new OtpMail($this->name, $otp));
        //Mail::to($this->email)->send(new OtpMail($this->otp_code, $this->email));
    }
}
