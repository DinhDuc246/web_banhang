<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Requests;
use Mail;
use App\CatePost;
use App\Slider;
use Illuminate\Support\Facades\Redirect;
session_start();


class HomeController extends Controller
{
    // public function send_mail(){
    //      //send mail
    //             $to_name = "Dinh Duc";
    //             $to_email = "dinhduc2406@gmail.com";//send to this email
               
             
    //             $data = array("name"=>"Mail từ tài khoản Khách hàng","body"=>'Mail gửi về vấn về hàng hóa'); //body of mail.blade.php
                
    //             Mail::send('pages.send_mail',$data,function($message) use ($to_name,$to_email){

    //                 $message->to($to_email)->subject('Test thử gửi mail google');//send this mail with subject
    //                 $message->from($to_email,$to_name);//send from this mail
    //             });
    //             // return redirect('/')->with('message','');
    //             //--send mail
    // }

    public function index(Request $request){
        //category post
        

        //slide
        $slider = Slider::orderBy('slider_id','DESC')->where('slider_status','1')->take(4)->get();
        //seo 
        $meta_desc = "Chuyên bán đồng hồ chính hãng"; 
        $meta_keywords = "Nơi bán những đồng hồ sang trọng và quý phái";
        $meta_title = "Shop đồng hồ Mona";
        $url_canonical = $request->url();
        //--seo
        
    	$cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get(); 

        // $all_product = DB::table('tbl_product')
        // ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        // ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        // ->orderby('tbl_product.product_id','desc')->get();
        
        $all_product = DB::table('tbl_product')->where('product_status','0')->orderby(DB::raw('RAND()'))->paginate(9); 

    	return view('pages.home')->with('category',$cate_product)->with('brand',$brand_product)->with('all_product',$all_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('slider',$slider); //1
        // return view('pages.home')->with(compact('cate_product','brand_product','all_product')); //2
    }
    public function search(Request $request){
         //slide
        $slider = Slider::orderBy('slider_id','DESC')->where('slider_status','1')->take(4)->get();

        //seo 
        $meta_desc = "Tìm kiếm sản phẩm"; 
        $meta_keywords = "Tìm kiếm sản phẩm";
        $meta_title = "Tìm kiếm sản phẩm";
        $url_canonical = $request->url();
        //--seo
        $keywords = $request->keywords_submit;

        $cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get(); 

        $search_product = DB::table('tbl_product')->where('product_name','like','%'.$keywords.'%')->get(); 


        return view('pages.sanpham.search')->with('category',$cate_product)->with('brand',$brand_product)->with('search_product',$search_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('slider',$slider);

    }
   public function order($order_code){
    $order = DB::table('tbl_order')->where('tbl_order.order_code',$order_code)
     ->join('tbl_shipping','tbl_shipping.shipping_id','=','tbl_order.shipping_id') 
     ->join('tbl_order_details','tbl_order_details.order_code','=','tbl_order.order_code') 
     ->get();
    return view('admin.order')->with('order',$order);
   }

   public function email($order_code){
         $order = DB::table('tbl_order')->where('tbl_order.order_code',$order_code)
        ->join('tbl_shipping','tbl_shipping.shipping_id','=','tbl_order.shipping_id') ->get();

        $data = array("name"=>"Mail từ tài khoản Khách hàng","body"=>'Mail gửi về vấn về hàng hóa');   
         foreach ($order as $key => $value) {
             $c = $value->shipping_email;
         }
         $to_name = 'Đình Đức';
       

     Mail::send('pages.email',$data,function($message) use ($to_name,$c){

                    $message->to($c)->subject('Xác nhận đơn hàng  ');//send this mail with subject
                    $message->from($c,'Đình Đức');//send from this mail
                });
    return Redirect::to('manage-order');
   }
   public function delete_order($order_code){

        DB::table('tbl_order')->where('order_code',$order_code)->delete();
        Session::put('message','Xóa đơn hàng thành công');
        return Redirect::to('manage-order');
    }
   

} 
