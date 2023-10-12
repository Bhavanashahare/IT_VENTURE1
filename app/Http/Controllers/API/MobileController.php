<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OtpVerification; // Make sure to import your models correctly
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Mail\OtpEmail;
class MobileController extends Controller
{
    public function forgetOtpVerify(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => 'required|exists:users,forgot_password_token',
            'otp' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return $this->result(false, [], $errors, 'Validation error!', 400);
        } else {
            $user = User::where('forgot_password_token', $request->input('token'))->first();

            if ($user) {
                $otpData = OtpVerification::where('user_id', $user->id)->orderBy('id', 'desc')->first();

                if (!empty($otpData) && $otpData->otp == $request->input('otp')) {
                    $otpData->delete();
                    $token = $this->generateUniqueForgotToken();
                    $user->forgot_password_token = $token;
                    $user->save();
                    return $this->result(true, [$token], [], 'OTP verified successfully.');
                } else {
                    return $this->result(false, [], [], 'Invalid OTP.');
                }
            } else {
                return $this->result(false, [], [], 'Token does not exist.');
            }
        }
    }

    private function generateUniqueForgotToken() {
        // Generate a unique token as per your requirements
        do{
            $str = md5(uniqid(rand(),true));

        }while(User::where('forgot_password_token', '=', $str)->count() > 0);

        return $str;
    }
    public function sendEmailVerification($data){
        Mail::to($data['email'])->send(new OtpEmail($data));
        return true;
    }
    public function signup(Request $request) {
        $request_data = $request->all();
        $validator_data = Validator::make($request_data, [
            'email'   => 'required|email',
        ]);
        if ($validator_data->fails()) {
            // return response()->json(false, [], $validator_data->errors()->messages(), 'validation error!', 400);

            // return $this->result(false, [], $validator_data->errors()->messages(), 'validation error!', 400);
        } else {
            $user = User::where('email',$request_data['email'])->first();
        //    if(!empty($user) && !empty($user->email_verified_at)) {
                // return response()->json(false, [], [], 'User already register with this email');

                // return $this->result(false, [], [], 'User already register with this email');
          //  } else

            if(!empty($user) && empty($user->email_verified_at)) {
                $token = $this->generateUniqueForgotToken();
            } else {
                $user = new User;
                $user->email    = trim($request_data['email']);
                $user->status   = 1;
                // dd($user);

                $token = $this->generateUniqueForgotToken();
            }
                            $token = $this->generateUniqueForgotToken();

            $user->forgot_password_token = $token;
            $user->save();

            // send otp
            $otpData = OtpVerification::create([
                'user_id'=>$user->id,
                'attribute_type'=>'email',
                'attribute_value'=>$request->email,
                'otp'=>6
            ]);
            // if($otpData['attribute_type'] == 'email') {
                $data['name'] = $otpData['otp_user']['name'];
                $data['email'] = $otpData['attribute_value'];
                $data['otp'] = $otpData['otp'];
                $data['message'] = trans('sms.sendOTP', ['OTP' => $otpData['otp']]);
                $this->sendEmailVerification($data);
                // $responseMg = 'OTP to set password has sent to you email id';
                return response()->json( 'OTP to set password has sent to you email id');
            // }
            // return response()->json(true, [$token], [], $responseMg);
            // return $this->result(true, [$token], [], $responseMg);
        }
    }




}
