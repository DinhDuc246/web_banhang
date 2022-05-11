<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Social;
use Socialite;
use App\Login;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Validator;
use App\Rules\Captcha; 
use App\Statistic;
use Carbon\Carbon;
class AdminController extends Controller
{

    
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function index(){
    	return view('admin_login');
    }
    public function show_dashboard(){
        $this->AuthLogin();
    	return view('admin.dashboard');
    }
    public function dashboard(Request $request){
        //$data = $request->all();
        $data = $request->validate([
            //validation laravel 
            'admin_email' => 'required',
            'admin_password' => 'required',
           'g-recaptcha-response' => new Captcha(),    //dòng kiểm tra Captcha
        ]);


        $admin_email = $data['admin_email'];
        $admin_password = md5($data['admin_password']);
        $login = Login::where('admin_email',$admin_email)->where('admin_password',$admin_password)->first();
        if($login){
            $login_count = $login->count();
            if($login_count>0){
                Session::put('admin_name',$login->admin_name);
                Session::put('admin_id',$login->admin_id);
                return Redirect::to('/dashboard');
            }
        }else{
                Session::put('message','Mật khẩu hoặc tài khoản bị sai.Hãy nhập lại');
                return Redirect::to('/admin');
        }
    }
    public function logout(){
        $this->AuthLogin();
        Session::put('admin_name',null);
        Session::put('admin_id',null);
        return Redirect::to('/admin');
    }




    //thông kê
    public function days_order(){

    $sub60days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(60)->toDateString();

    $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

    $get = Statistic::whereBetween('order_date',[$sub60days,$now])->orderBy('order_date','ASC')->get();


    foreach($get as $key => $val){

       $chart_data[] = array(
        'period' => $val->order_date,
        'order' => $val->total_order,
        'sales' => $val->sales,
        'profit' => $val->profit,
        'quantity' => $val->quantity
    );

   }

   echo $data = json_encode($chart_data);
}

public function dashboard_filter(Request $request){

    $data = $request->all();

        // $today = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
       // $tomorrow = Carbon::now('Asia/Ho_Chi_Minh')->addDay()->format('d-m-Y H:i:s');
       // $lastWeek = Carbon::now('Asia/Ho_Chi_Minh')->subWeek()->format('d-m-Y H:i:s');
       // $sub15days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(15)->format('d-m-Y H:i:s');
       // $sub30days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(30)->format('d-m-Y H:i:s');

    $dauthangnay = Carbon::now('Asia/Ho_Chi_Minh')->startOfMonth()->toDateString();
    $dau_thangtruoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->startOfMonth()->toDateString();
    $cuoi_thangtruoc = Carbon::now('Asia/Ho_Chi_Minh')->subMonth()->endOfMonth()->toDateString();



    $sub7days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(7)->toDateString();
    $sub365days = Carbon::now('Asia/Ho_Chi_Minh')->subdays(365)->toDateString();

    $dauthang9 = Carbon::now('Asia/Ho_Chi_Minh')->subMonth(2)->startOfMonth()->toDateString();
    $cuoithang9 = Carbon::now('Asia/Ho_Chi_Minh')->subMonth(2)->endOfMonth()->toDateString();


    $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

    if($data['dashboard_value']=='7ngay'){

        $get = Statistic::whereBetween('order_date',[$sub7days,$now])->orderBy('order_date','ASC')->get();

    }elseif($data['dashboard_value']=='thangtruoc'){

        $get = Statistic::whereBetween('order_date',[$dau_thangtruoc,$cuoi_thangtruoc])->orderBy('order_date','ASC')->get();

    }elseif($data['dashboard_value']=='thangnay'){

        $get = Statistic::whereBetween('order_date',[$dauthangnay,$now])->orderBy('order_date','ASC')->get();

    }elseif ($data['dashboard_value']=='thang9') {

        $get = Statistic::whereBetween('order_date',[$dauthang9,$cuoithang9])->orderBy('order_date','ASC')->get();

    }else{
        $get = Statistic::whereBetween('order_date',[$sub365days,$now])->orderBy('order_date','ASC')->get();
    }


    foreach($get as $key => $val){

        $chart_data[] = array(
            'period' => $val->order_date,
            'order' => $val->total_order,
            'sales' => $val->sales,
            'profit' => $val->profit,
            'quantity' => $val->quantity
        );
    }

    echo $data = json_encode($chart_data);

}
public function filter_by_date(Request $request){

    $data = $request->all();

    $from_date = $data['from_date'];
    $to_date = $data['to_date'];

    $get = Statistic::whereBetween('order_date',[$from_date,$to_date])->orderBy('order_date','ASC')->get();


    foreach($get as $key => $val){

        $chart_data[] = array(

            'period' => $val->order_date,
            'order' => $val->total_order,
            'sales' => $val->sales,
            'profit' => $val->profit,
            'quantity' => $val->quantity
        );
    }

    echo $data = json_encode($chart_data);  

}
}
