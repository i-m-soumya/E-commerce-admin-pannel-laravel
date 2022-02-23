<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order by product Report</title>
</head>
<style>
    .clearfix:after {
        content: "
display: table;
        clear: both;
    }

</style>
<body>
    <div class="clearfix" style="border-bottom: 1px solid #AAAAAA;">
        <div style="float: left;">
            <img src="{{$logo_link}}assets/dist/img/full_logo.png" height="100px" width="250px">
        </div>
        <div style="float: right;">
            <p style="font-size: 15px;"><strong>Grocerbee</strong><br>Dhaniakhali , Hooghly <br>West Bengal - 712302<br>
                Contact no. : +91 7076757816<br>
                Email : support@grocerbee.co.in
            </p>
        </div>
    </div>
    <center>
        <h2>Order By products Report</h2>
    </center>
    <p>
        Print date: {{ $print_date}} <br>
        Data From {{$start_date}} To {{$end_date}}
    </p>
    <table style="width:100% ;border-collapse: collapse;">
        <tr>
            <th scope="col" style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;width:40px;">Sl no.</th>
            {{-- <th scope="col" style="text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Product id</th> --}}
            <th scope="col" style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Product name</th>
            <th scope="col" style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Unit type</th>
            <th scope="col" style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Unit qty</th>
            <th scope="col" style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Total order</th>
            <th scope="col" style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Total unit quantity</th>
        </tr>
        {{$sl_no=1}};
        @foreach($all_data as $data)
        <tr>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{ $sl_no}}</td>
            {{-- <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$data->product_id}}</td> --}}
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$data->name}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$data->unit_type_name}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$data->quantity}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$data->total_order}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$data->total_quantity_amount . $data->unit_type_name}}</td>
        </tr>
        {{$sl_no++}};
        @endforeach
    </table><br>
</body>
</html>
