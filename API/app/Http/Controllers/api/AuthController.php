<?php

namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use App\Utility\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Utility\Database\Facades\Dao;
class AuthController  extends APIController
{
    public function register(Request $request){
        try {
            $data['user_nm'] = $request->user_nm??'';
            $data['address'] = $request->address??'';
            $data['tel']    = $request->tel??'';
            $data['email'] = $request->email??'';
            $data['password'] = $request->password??'';
            $data['role'] = $request->role??'';


            $message_error  = [
                'required' => Constants::REQUIRED,
                'email' => Constants::EMAIL,
            ];
            $validator = Validator::make($data, [
                'email' => ['required', 'email:rfc,dns'],
                'password' => ['required'],
                'user_nm' => ['required'],
                'address' => ['required'],
                'tel' => ['required'],
            ],$message_error);
            // if has errors
            if ($validator->fails()) {
                $errors = [];
                if (!empty($validator->messages()->get('*'))) {
                    foreach ($validator->messages()->get('*') as $key => $value) {
                        $errors[$key] = $value[0] ?? '';
                    }
                }
                return $this->handleApiError('Handling failure',$errors,422);
            }
            $credentials['email'] = $data['email'];
            $credentials['password'] = $data['password'];

            $users = DB::table('account')->where('email', '=',  $data['email'])->where('del_flg', '=',0)->get();

            if(!$users->isEmpty()){
                $errors['alter'] = Constants::DUPLICATE;
                return $this->handleApiError('Handling failure',$errors,501);
            }

            $date = date('Y-m-d H:i:s');
            DB::table('account')->insert([
                'user_nm'  => $data['user_nm'],
                'address'  => $data['address'],
                'tel'      => $data['tel'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
                'role'     => $data['role'],
                'cre_date' => $date,
                'upd_date' => $date,
                'del_date' => $date,
                'del_flg'  => 0,

            ]);

            // return $this->handleApiSuccess($data);
            return $this->handleApiSuccess();

        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }

    public function login (Request $request){
        try {
            $data['email'] = $request->email??'';
            $data['password'] = $request->password??'';
            $data['role'] = $request->role??'';
    
            $message_error  = [
                'required' => Constants::REQUIRED,
                'email' => Constants::EMAIL,
            ];
            $validator = Validator::make($data, [
                'email' => ['required'],
                'password' => ['required'],

            ],$message_error);

            // if has errors
            if ($validator->fails()) {
                $errors = [];
                if (!empty($validator->messages()->get('*'))) {
                    foreach ($validator->messages()->get('*') as $key => $value) {
                        $errors[$key] = $value[0] ?? '';
                    }
                }
                return $this->handleApiError('Handling failure',$errors,422);
            }

            if(Auth::attempt($data)){
                $user = Auth::user();
                if($user->del_flg == 0){
                    $tokenResult = $user->createToken('authToken')->plainTextToken;
                    $data['access_token']   =  $tokenResult;
                    $data['token_type']     = 'Bearer';
                    $data['user_nm']        =  $user->user_nm;
                    return $this->handleApiSuccess($data);
                }else{
                    $errors['alter'] = Constants::PASS_ERROR;
                    return $this->handleApiError('Handling failure',$errors,501);
                }
               
            }
            $errors['alter'] = Constants::PASS_ERROR;
            return $this->handleApiError('Handling failure',$errors,501);

        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->handleApiSuccess([]);
        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }

    public function getAuth()
    {
            try {
                $data = DB::table('account')->where('del_flg', '=',0)->where('role', '=',0)->get();
                return $this->handleApiSuccess($data);
            } catch (\Throwable $e) {
                return $this->handleApiError($e->getMessage(),$e, 500);
            }
    }

    public function delAuth(Request $request)
    {
            try {

                $params['json'] = json_encode($request->all());
                $data = Dao::execute('SPC_AUTH_ACT1', $params);
                return $this->handleApiSuccess();
            } catch (\Throwable $e) {
                return $this->handleApiError($e->getMessage(),$e, 500);
            }
    }

}
