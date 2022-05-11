@extends('admin_layout')
@section('admin_content')
<h3 style="text-align: center;font-weight: bold; color: white;">Chào mừng bạn đến với trang Admin. Trang quản lý</h3>

<div class="row">
		<p class="title_thongke">Thống kê đơn hàng doanh số</p>

		<form autocomplete="off">
			@csrf

			<div class="col-md-2">
				<p>Từ ngày: <input type="text" id="datepicker" class="form-control"><br>

				<input type="button" id="btn-dashboard-filter" class="btn btn-primary btn-sm" value="Lọc kết quả"></p>

			</div>

			<div class="col-md-2">
				<p>Đến ngày: <input type="text" id="datepicker2" class="form-control"></p>
			
			</div>

			<div class="col-md-2">
				<p>
					Lọc theo: 
					<select class="dashboard-filter form-control" >
						<option>--Chọn--</option>
						<option value="thang9">Trong tháng 9</option>
						<option value="7ngay">7 ngày qua</option>
						<option value="thangtruoc">tháng trước</option>
						<option value="thangnay">tháng này</option>
						<option value="365ngayqua">365 ngày qua</option>
					</select>
				</p>
			</div>

		</form>

		<div class="col-md-12">
			<div id="chart" style="height: 250px;"></div>
		</div>

</div>
@endsection