<?php

namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use App\Utility\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Utility\Database\Facades\Dao;
use App\Models\Cart;
class AdminController  extends APIController
{
    public function getAdmin(Request $request){
        try {
            
            $order_manager = DB::table('order_manager')
            ->where('del_flg', '=',0)->get();
            $account = DB::table('account')
            ->where('role', '=', 0)
            ->get();

            $data['order_manager'] =  $order_manager;     
            $data['account'] =  $account;     
            return $this->handleApiSuccess($data);

        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }
    public function orderUpdateAdmin(Request $request){
        try {
            $params['json'] = json_encode($request->all());
            $data = Dao::execute('SPC_ORDER_ACT1', $params);
            return $this->handleApiSuccess($data);
          
        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }
    public function orderDetailAdmin(Request $request){
        try {
            $data = DB::table('order_detail')
            ->where('order_no', '=', $request->order_id ?? '')
            ->where('del_flg', '=',0)->get();
            return $this->handleApiSuccess($data);
          
        } catch (\Throwable $e) {
            return $this->handleApiError($e->getMessage(),$e, 500);
        }
    }
   
 
}
