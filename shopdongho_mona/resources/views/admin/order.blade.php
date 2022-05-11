@extends('admin_layout')
@section('admin_content')
 <div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin Nhận hàng
    </div>
    <div class="row w3-res-tb">
     
     
    
    </div>
    <div class="table-responsive">
                      <?php
                            $message = Session::get('message');
                            if($message){
                                echo '<span class="text-alert">'.$message.'</span>';
                                Session::put('message',null);
                            }
                            ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
           
            <th>Thứ tự</th>
            <th>khách hàng </th>
            <th>Địa chỉ</th>
            <th>Số ĐT</th>
            <th>Email</th>
            <th>Ghi chú</th>
            <th>Phướng thức thanh toán</th>

            <th style="width:30px;"></th>
          </tr>
        </thead>
         <tbody>
          @php 
          $i = 0;
          @endphp
          @foreach($order as $key => $ord)
            @php 
            $i++;
            @endphp
          <tr>
            <td><i>{{$i}}</i></label></td>
           
           <td>{{$ord->shipping_name}}</td>
           <td>{{$ord->shipping_address}}</td>
           <td>{{$ord->shipping_phone}}</td>
           <td>{{$ord->shipping_email}}</td>
          <td>{{$ord->shipping_notes}}</td>
           <td>@if($ord->shipping_method==1)
                tiền mặt
                @else
                Thẻ ATM
                @endif
           </td>
          </tr>
          @endforeach
        </tbody>
      </table>


    </div>
   <div class="panel-heading">
      Liệt kê đơn hàng
    </div>
    <div class="row w3-res-tb">
     
     
    
    </div>
    <div class="table-responsive">
                      <?php
                            $message = Session::get('message');
                            if($message){
                                echo '<span class="text-alert">'.$message.'</span>';
                                Session::put('message',null);
                            }
                            ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
           
            <th>Thứ tự</th>
            <th></th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>mã giảm giá</th>
            <th>Phí vận chuyển</th>
            <th>Tổng tiền</th>

            <th style="width:30px;"></th>
          </tr>
        </thead>
         <tbody>
          @php 
          $i = 0;
          $total=0;
          @endphp
          @foreach($order as $key => $ord)
            @php 
            $i++;
            $ratotal=$ord->product_price*$ord->product_sales_quantity;
            $total+=$ratotal;
            @endphp
          <tr>
            <td><i>{{$i}}</i></label></td>
           <td></td>
           <td>{{$ord->product_name}}</td>
           <td>{{$ord->product_price}}</td>
           <td>{{$ord->product_sales_quantity}}</td>
           <td>{{$ord->product_coupon}}</td>
           <td>{{$ord->product_feeship}}</td>
           <td>{{$ratotal}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
     <P>Tổng tiền: {{$total}} VNĐ</P>
   
      <form method="post" action="{{URL::to('/save-xl')}}">
        {{ csrf_field() }} 
         @foreach($order as $key => $ord)
        <input type="hidden" name="code" value="{{$ord->order_code}}">
        @endforeach
         <br>
        <select name="xl">
          <option value="1">Chưa xử lý</option>
          <option value="2">Đã xử lý</option>

        </select>

        <input type="submit" value="Cập nhật" >
        </form>
    </div>
  </div>
</div>


@endsection
