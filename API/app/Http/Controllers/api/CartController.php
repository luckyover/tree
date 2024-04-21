<?php

namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use App\Utility\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Utility\Database\Facades\Dao;
use App\Models\Cart;
class CartController  extends APIController
{
    public function addCart(Request $request){
        try {
            
            $data['email'] = $request->email??'';
            $data['qty'] = $request->qty??'';
            $data['title'] = $request->title??'';
            $data['img'] = $request->img??'';
            $data['price'] = $request->price??'';


            $result = DB::table('cart')
            ->where('email', '=',  $data['email'])
            ->where('title', '=',  $data['title'])
            ->where('price', '=',  $data['price'])
            ->where('img', '=',  $data['img'])
            ->where('del_flg', '=',0)->first();
           
            if($result){
              
                DB::table('cart')->where('email', '=',  $data['email'])
                ->where('title', '=',  $data['title'])
                ->where('price', '=',  $data['price'])
                ->where('del_flg', '=',0)
                ->update(['qty' =>  $result->qty + 1]);
               
            }else{
                $date = date('Y-m-d H:i:s');
                DB::table('cart')->insert([
                    'email'  => $data['email'],
                    'qty'  => $data['qty'],
                    'title'      => $data['title'],
                    'price'      => $data['price'],
                    'img'    => $data['img'],
                    'cre_date' => $date,
                    'upd_date' => $date,
                    'del_date' => $date,
                    'del_flg'  => 0,
    
                ]);
            }
           
            $data = DB::table('cart')->where('del_flg', '=',0)->where('email', '=',$data['email'])->get();


            return $this->handleApiSuccess($data);

        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }
    public function getCart(Request $request){
        try {
            $data['email'] = $request->email??'';
            $data = DB::table('cart')->where('del_flg', '=',0)->where('email', '=',$data['email'])->get();
            return $this->handleApiSuccess($data);

        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }
    public function removeCart(Request $request){
        try {
            $data['email'] = $request->email??'';

            DB::table('cart')
              ->where('cart_id','=',$request->id)
              ->where('del_flg','=',0)
              ->update(['del_flg' => 1]);
            
            $data = DB::table('cart')->where('del_flg', '=',0)->where('email', '=',$data['email'])->get();
            return $this->handleApiSuccess($data);
           
        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }
    public function updateCart(Request $request){
        try {
            
            $data['email'] = $request->email??'';

            DB::table('cart')
              ->where('cart_id','=',$request->id)
              ->where('del_flg','=',0)
              ->update(['qty' => $request->qty]);
            
            $data = DB::table('cart')->where('del_flg', '=',0)->where('email', '=',$data['email'])->get();
            return $this->handleApiSuccess($data);
           

            return $this->handleApiSuccess();

        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }

    public function orderCart(Request $request){
      
        $params['email'] = $request->email ?? '';
        Dao::execute('SPC_CART_ACT1', $params);
        $data = [];
        return $this->handleApiSuccess($data);

    }

    public function hisCart(Request $request){
      
        $data = DB::table('order_manager')
        ->where('account', '=',$request->email ?? '')
        ->where('del_flg', '=',0)->get();
        return $this->handleApiSuccess($data);

    }


}
