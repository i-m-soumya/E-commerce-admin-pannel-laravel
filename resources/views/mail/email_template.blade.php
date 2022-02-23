{{-- <h1>Hi, {{ $USER_NAME }}</h1>
<p>{{$MAIL_BODY}}.</p>
<p>Thank you.</p>
<p>Grocerbee.</p>
<p>+91 7076757816</p> --}}
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

    </style>
</head>
<body>
    <div class="row" style="margin: 0 -5px;">
        <div style="width: 100%;padding: 0 10px;">
            <div style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);padding: 16px;">
                <div style="padding:2px;background-color: #f8204a;color: white;text-align: center;">
                    <p>
                        <h3>{{ $MAIL_SUBJECT }}</h3>
                    </p>
                </div>
                <h3><strong>{{ $USER_NAME }}</strong></h3>
                <p>{{$MAIL_BODY}}</p>
                <p>Regards,</p>
                <p>Grocerbee</p>
                <p>+91 7076757816</p>
            </div>
        </div>
    </div><br><br>
    <footer style=" color: #777777;
  width: 100%;
  height: 30px;
  bottom: 0;
  text-align: center;">
        <strong>Grocerbee</strong> Dhaniakhali , Hooghly, West Bengal-712302<br><br>
        Powered by <a href="https://Fourous.com" style="text-decoration:none;">Fourous.com</a>
    </footer>
</body>
</html>
