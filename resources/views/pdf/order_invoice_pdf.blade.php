<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>order_pdf</title>
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
        <h2>Invoice</h2>
    </center>
    @foreach($all_data as $data)
    <div style="height: 130px;">
        <span style="position: absolute;width:400px;">
            <p style="font-size: 15px;">
                <strong>{{$data->customer_name}}</strong><br>
                {{$data->house_no}}, {{$data->village_name}}, {{$data->area}}<br>
                Landmark: {{$data->landmark}},<br>
                {{$data->city}}, {{$data->state}} - {{$data->pincode}}, {{$data->country}}<br>
                Mobile no. - {{$data->customer_mobile_number}}<br>
                Email - {{$data->customer_email}}
            </p>
        </span>
        <?php date_default_timezone_set("Asia/Calcutta");
        $timestamp = $data->ordered_on;
        $final_date_time = date("d-m-Y h:i  A", $timestamp);
        ?>
        <span style="float:right;">
            <p style="font-size: 15px;">
                Order No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;OD{{ date("mdY", $timestamp).$data->orders_id }}<br>
                Invoice No. &nbsp;&nbsp;&nbsp;:&nbsp;{{ $data->invoice_number }}<br>
                Date & Time &nbsp;:{{ $final_date_time }}
            </p>
        </span>
    </div><br>
    <table style="max-width:100% ;border-collapse: collapse;">
        <tr>
            <th style="text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Sl No.</th>
            <th style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;width:360px;">Product name</th>
            <th style="text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Qty.</th>
            <th style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">MRP</th>
            <th style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Discount</th>
            <th style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;width:90px;">Selling Price</th>
            <th style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:center;">Total</th>
        </tr>
        {{$sl_no=1 }};
        {{$net_amount=0}};
        @foreach($data->item_details as $item)
        <tr>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{ $sl_no }}</td>
            <?php if(strlen($item->item_name)<=50)
            {
                $product_name=$item->item_name;
            }
            else{
                $product_name=substr($item->item_name,0,50)."...";
            }
            ?>


            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$product_name}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse;">{{$item->item_quantity}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:right">{{$item->per_qty_mrp}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:right">{{$item->per_qty_discount}}</td>
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:right">{{$item->per_qty_sell_price}}</td>
            {{$per_item_total=$item->per_qty_sell_price*$item->item_quantity}};
            <td style="padding: 2;text-align: left;border: 1px solid #131212A8;border-collapse: collapse; text-align:right">{{ number_format($per_item_total, 2)}}</td>
        </tr>
        {{$net_amount+=$per_item_total}};
        {{$sl_no++ }};
        @endforeach
        <tr>
            <td colspan="7"><br></td>
        </tr>
        <tr>
            <td style="" colspan="2">*Once accepted, cannot be returned<br>*Please check your product at the time of delivery</td>
            <td colspan="4" style="padding: 2;text-align: left;"> Net Amount <br> Additional Discount <br> Delivery charge</td>
            <td style="padding: 2;text-align: right;"> {{ number_format($net_amount, 2)}} <br> - {{number_format($data->applied_coupon_amount,2)}} <br> {{number_format($data->delivery_charge,2)}} <br></td>
        </tr>
        <tr>
            <td style="" colspan="2"></td>
            <td colspan="4" style="padding: 2;text-align: left; border-top: 1px solid #131212A8;border-bottom: 1px solid #131212A8;"><strong>Net Payable</strong></td>
            <td style="padding: 2;text-align: right;border-top: 1px solid #131212A8;border-bottom: 1px solid #131212A8;"><strong>{{number_format($data->total_payable_amount,2)}}</strong></td>
        </tr>

        }
    </table><br>

    @endforeach
    <footer style="  color: #777777;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;">
        This is an autogenerated invoice and requires no signature or seal.
    </footer>
</body>
</html>
