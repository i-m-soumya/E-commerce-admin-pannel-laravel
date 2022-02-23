<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Aggregator Report</title>
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
        <h2>Aggregator Report</h2>
    </center>
    <p>
        Print date: {{ $print_date}} <br>
        Data From {{$start_date}} To {{$end_date}}
    </p>
    <table style="width:100% ;border-collapse: collapse;">
        <tr>
            <th style="text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;width:30px;">ID</th>
            <th style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Aggregator Name</th>
            <th style="text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;width:100px;">Total Orders</th>
            <th style="text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;width:150px;">Total Amount</th>
        </tr>
        @foreach($all_data as $data)
        <tr>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{ $data->delivery_partner_id}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$data->name}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$data->total_order}}</td>
            @if($data->total_order_amount=="")
            {
            {{$data->total_order_amount=0}};
            }
            @endif
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:right">{{ number_format($data->total_order_amount)}}</td>
        </tr>

        @endforeach
    </table><br>
</body>
</html>
