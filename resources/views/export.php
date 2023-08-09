<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Import</title>
	<style>
		#content {
			padding: 24px;
		}

		@font-face {
			font-family: 'Firefly';
			font-style: normal;
			font-weight: normal;
			src: url(http://example.com/fonts/firefly.ttf) format('truetype');
		}

		.header {
			font-size: xx-large;
			text-align: center;
		}

		.box-text {
			display: flex;
		}

		.text-item {
			flex: 1 1 0%;
		}

		.container-information {
			display: flex;
			flex-direction: column;
		}

		th {
			border: 1px solid;
			text-align: left;
			padding: 4px;

		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		.content-table-header {
			font-weight: 700;
			color: white;
			text-transform: uppercase;
		}

		.table-header {
			background-color: #23304D;

		}

		.content-table-body {
			font-weight: 400;
		}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body id="content">

	<h1 class="header" style="font-family: firefly, DejaVu Sans, sans-serif;">Phiếu xuất hàng</h1>

	<div class="container-information">
		<h3 class="text-item" style="font-family: firefly, DejaVu Sans, sans-serif;">Thông tin nhân viên lập phiếu xuất
		</h3>
		<div class="box-text text-item">
			<div class="text-item">
				<span style="font-family: firefly, DejaVu Sans, sans-serif;">Mã nhân viên: {{ $staff['id'] }}</span>
			</div>
			<div class="text-item">
				<span style="font-family: firefly, DejaVu Sans, sans-serif;">Họ và tên: {{ $staff['name'] }}</span>
			</div>
			<div class="text-item">
				<span style="font-family: firefly, DejaVu Sans, sans-serif;">Ngày sinh:
					{{ $staff_dob }}</span>
			</div>
			<div class="text-item">
				<span style="font-family: firefly, DejaVu Sans, sans-serif;">Chức vụ: {{ $staff_position }}</span>
			</div>
		</div>
	</div>
	<br />
	<hr />
	<div class="container-information">
		<h3 class="text-item" style="font-family: firefly, DejaVu Sans, sans-serif;">Thông tin chi nhánh kho tiếp nhận
		</h3>
		<div class="box-text text-item">
			<div class="text-item">
				<span style="font-family: firefly, DejaVu Sans, sans-serif;">
					Mã nhà cung cấp: {{ $warehouse_branch['id'] }}
				</span>
			</div>
			<div class="text-item">
				<span style="font-family: firefly, DejaVu Sans, sans-serif;">
					Tên nhà cung cấp: {{ $warehouse_branch['name'] }}
				</span>
			</div>
			<div class="text-item">
				<span style="font-family: firefly, DejaVu Sans, sans-serif;">
					Số điện thoại: {{ $warehouse_branch['phone_number'] }}
				</span>
			</div>
			<div class="text-item">
				<span style="font-family: firefly, DejaVu Sans, sans-serif;">
					Địa chỉ: {{ $warehouse_branch['address'] }}
				</span>
			</div>
		</div>
	</div>

	<br />
	<hr />
	<div class="box-text">
		<div class="text-item">
			<span style="font-family: firefly, DejaVu Sans, sans-serif;">Thời gian lập phiếu: {{ $created_at }}</span>
		</div>

		<div class="text-item">
			<span style="font-family: firefly, DejaVu Sans, sans-serif;">Tình trạng: {{ $status }}</span>
		</div>
	</div>
	<br />
	<hr />
	<h3 class="text-item" style="font-family: firefly, DejaVu Sans, sans-serif;">Chi tiết phiếu xuất hàng</h3>

	<table>
		<thead class=table-header>
			<tr>
				<th style="border-color:antiquewhite!important;">
					<p class="content-table-header" style="font-family: firefly, DejaVu Sans, sans-serif;">Mã loại hàng
					</p>
				</th>
				<th style="border-color:antiquewhite!important;">
					<p class="content-table-header" style="font-family: firefly, DejaVu Sans, sans-serif;">Tên hàng hóa
					</p>
				</th>
				<th style="border-color:antiquewhite!important;">
					<p class="content-table-header" style="font-family: firefly, DejaVu Sans, sans-serif;">Đơn vị tính
					</p>
				</th>
				<th style="border-color:antiquewhite!important;">
					<p class="content-table-header" style="font-family: firefly, DejaVu Sans, sans-serif;">Số lượng</p>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($categories as $category)
			<tr>
				<th>
					<span class="content-table-body" style="font-family: firefly, DejaVu Sans, sans-serif;">{{ $category['id'] }}</span>
				</th>
				<th>
					<span class="content-table-body" style="font-family: firefly, DejaVu Sans, sans-serif;">{{ $category['name'] }}</span>
				</th>
				<th>
					<span class="content-table-body" style="font-family: firefly, DejaVu Sans, sans-serif;">{{ $category['unit'] }}</span>
				</th>
				<th>
					<span class="content-table-body" style="font-family: firefly, DejaVu Sans, sans-serif;">{{ $category['amount'] }}</span>
				</th>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>

</html>