<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'attribute_type',
        'attribute_value',
        'otp'
    ];
    /* set otp value */
    public function setOtpAttribute($otp = null): void
    {
        //$exp = strlen($otp);
        $exp = $otp;
        $this->attributes['otp'] = (string)random_int(10 ** ($exp - 1), (10 ** $exp) - 1);
    }
    /* opt user relation */
    public function otp_user()
    {
       return $this->belongsTo(User::class, 'user_id');
    }
}
