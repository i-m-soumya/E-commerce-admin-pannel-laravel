@extends('layouts.web_view_template')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <img src="assets/dist/img/full_logo.png" height="200px" width="300px" class="image-responsive">
            <p class="" style="font-size: 15px;"><strong>Grocerbee</strong><br>Dhaniakhali , Hooghly <br>West Bengal - 712302<br>
                Contact no. : +91 7076757816<br>
                Email : support@grocerbee.co.in
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <P class="h1 mt-2 mb-4"><strong> About us</strong></P>
            <p>
            Welcome to Grocerbee,<br/>&emsp;&emsp; your number one source for all your grocery needs. We're, dedicated to giving you the very best product, with a focus, on the lowest price.</br>
            &emsp;&emsp;Founded in 2021, Grocerbee started its journey from Dhaniakhali.  Grocerbee can offer you the lowest price and fastest delivery. We are thrilled that we're able to turn our passion into this platform, by which users can fulfill their daily needs.</br>
            &emsp;&emsp;We hope you enjoy our products as much as we enjoy offering them to you. If you have any questions or comments, please don't hesitate to contact us.</br>
         Sincerely,<br/>
   Team Grocerbee
            </p>
            <P class="h1 mt-2 mb-4"><strong> Contact us</strong></P>
            <form class="" id="contact_us_form" methode="post">
                @csrf
                <div class="form-row mt-2">
                    <div class="form-group col-md-12">
                        <label for="contact_us_name">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contact_us_name" placeholder="Enter name" autocomplete="off">
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="form-group col-md-12">
                        <label for="contact_us_email">Email<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contact_us_email" placeholder="Enter email" autocomplete="off">
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="form-group col-md-12">
                        <label for="contact_us_subject">Subject<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contact_us_subject" placeholder="Enter subject" autocomplete="off">
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="form-group col-md-12">
                        <label for="contact_us_details">Details<span class="text-danger">*</span></label>
                        <textarea class="form-control" aria-label="With textarea" id="contact_us_details" placeholder="Enter details" autocomplete="off"></textarea>
                    </div>
                </div>
                {{-- =========================================================================== --}}
                <div class="form-row">
                    <div class="form-group col-md-12 d-flex">
                        <span class="mt-2">NOTE:(<span class="text-danger">*</span>)Marked Items Are mandatory To fill</span>
                    </div>

                    <div class="form-group col-md-6 d-flex ">
                        <div class="ui buttons mt-2">
                            <button type="submit" class="ui button" id="contact_us_reset">Reset</button>
                            <div class="or"></div>
                            <button type="submit" class="ui positive button" id="contact_us_send">Send</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
    contact_us();
    });

</script>
@endsection
