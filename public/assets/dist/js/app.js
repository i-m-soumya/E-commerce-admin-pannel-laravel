function contact_us() {
    $('#contact_us_send').click(function(e) {
        e.preventDefault();
        var contact_us_name = document.getElementById('contact_us_name').value;
        var contact_us_email = document.getElementById('contact_us_email').value;
        var contact_us_subject = document.getElementById('contact_us_subject').value;
        var contact_us_details = document.getElementById('contact_us_details').value;
        var mailformat = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (contact_us_name == "" || contact_us_email == "" || contact_us_subject == "" || contact_us_details == "") {
            toastr.error('Marked Items Are mandatory To fill');
        } else if (!mailformat.test(contact_us_email)) {
            toastr.error('Please enter valid email address');
        } else {
            $("#contact_us_send").prop('disabled', true);
            $('#contact_us_send').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/contact_us",
                method: 'POST',
                data: {
                    contact_us_name: contact_us_name,
                    contact_us_email: contact_us_email,
                    contact_us_subject: contact_us_subject,
                    contact_us_details: contact_us_details,
                },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success(data['message']);
                        reset_field();
                        $("#contact_us_send").prop('disabled', false);
                        $('#contact_us_send').html('Send');

                    } else {
                        toastr.error(data['message']);
                        $("#contact_us_send").prop('disabled', false);
                        $('#contact_us_send').html('Send');
                    }
                }
            });
        }
    });
    $('#contact_us_reset').click(function(e) {
        e.preventDefault();
        $("#contact_us_reset").prop('disabled', true);
        $('#contact_us_reset').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        reset_field();
        $("#contact_us_reset").prop('disabled', false);
        $('#contact_us_reset').html('Reset');
    });

    function reset_field() {
        $('#contact_us_name').val('');
        $('#contact_us_email').val('');
        $('#contact_us_subject').val('');
        $('#contact_us_details').val('');
    }

}

function login() {
    $("#Login").click(function(e) {
        e.preventDefault();
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        if (email == "") {
            toastr.error('Please enter email to continue');
        } else if (password == "") {
            toastr.error("Password shouldn't be null");
        } else {
            $("#Login").prop('disabled', true);
            $('#Login').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/signin",
                method: 'POST',
                data: {
                    email: email,
                    password: password
                },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        if (data['admin_type'] == 1) {
                            toastr.success('Login Successful')
                            setTimeout(function() {
                                // $("#Login").prop('disabled', false);
                                $('#Login').html('Login');
                                window.location = "/dashboard";
                            }, 500);
                        } else {
                            toastr.success('Login Successful')
                            setTimeout(function() {
                                $('#Login').html('Login');
                                window.location = "/order_details";
                            }, 500);
                        }
                    } else {
                        toastr.error(data['message']);
                        $("#Login").prop('disabled', false);
                        $('#Login').html('Login');
                    }
                }
            });
        }
    });
}

function template() {
    var session_user_id = $('#session_user_id').val();
    if (session_user_id == 2) {
        $('#dashboard_menu').hide();
        $('#product_menu').hide();
        $('#report_menu').hide();
        $('#users_menu').hide();
        $('#settings_menu').hide();

    }
    $("#logout_btn").click(function(e) {
        e.preventDefault();
        swal({
                title: "Are you sure?",
                text: "Once rejected, you will not be able to change!",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    window.location.href = "/logout";
                }
            });
    });
    $("#profile").click(function() {
        $.ajax({
            url: "/view_admin_profile",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $("#profile_name").empty();
                $("#profile_email").empty();
                $("#profile_mobile_no").empty();
                $("#profile_admin_type").empty();
                $.each(data, function(index, val) {
                    $("#profile_name").html(val.name);
                    $("#profile_email").html(val.email);
                    $("#profile_mobile_no").html(val.mobile_number);
                    if (val.admin_type_id == 1) {
                        var admin_type = 'Admin';
                    } else if (val.admin_type_id == 2) {
                        var admin_type = 'Salesman';
                    } else {
                        var admin_type = 'Unknown';
                    }
                    $("#profile_admin_type").html(admin_type);
                });
            }
        });

    });

}

function dashboard() {
    fetch_total_sales_and_orders();
    fetch_new_members();
    fetch_sales_chart();
    fetch_orders_status_monthly();
    fetch_orders_status_yearly();
    fetch_pin_wise_order();
    fetch_leatest_order();
    fetch_top_selling_products();
    fetch_customer_chart_data();
    fetch_customer_feedback(0);

    function fetch_total_sales_and_orders() {
        $.ajax({
            url: "dashboards/fetch_total_sales_and_orders",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#total_sales').empty();
                $('#total_sales_yearly').empty();
                $('#total_orders').empty();
                $.each(data['total_sales_and_orders'], function(index, val) {
                    var total_orders = val.total_orders;
                    var total_sales = val.total_sales;
                    if (total_sales == null || total_sales == 0) {
                        total_sales = 0;
                    }
                    if (total_orders == null || total_orders == 0) {
                        total_orders = 0;
                    }
                    $.each(data['previous_total_sales_and_orders'], function(index, val) {
                        var previous_total_orders = val.previous_total_orders;
                        var previous_total_sales = val.previous_total_sales;
                        if (previous_total_sales == null || previous_total_sales == 0) {
                            previous_total_sales = 0;
                        }
                        if (previous_total_orders == null || previous_total_orders == 0) {
                            previous_total_orders = 0;
                        }
                        if (total_sales == previous_total_sales) {
                            var increased_percent_sales_string = '<span class="ml-4 text-info"> 0.00% <i class="fas fa-equals"></i></span>';
                        } else if (total_sales > previous_total_sales) {
                            let increase_sales = parseFloat(total_sales) - parseFloat(previous_total_sales);
                            let increased_percent = parseFloat(increase_sales) / parseFloat(previous_total_sales) * 100;
                            if (increased_percent == Infinity) {
                                var increased_percent_sales_string = '<i class="fas fa-infinity text-success ml-4"></i><i class="fas fa-arrow-up ml-2 text-success"></i>';
                            } else {
                                var increased_percent_sales_string = '<span class="ml-4 text-success">' + increased_percent.toFixed(2) + '% <i class="fas fa-arrow-up"></i></span>';
                            }
                        } else {
                            let decrease_sales = parseFloat(previous_total_sales) - parseFloat(total_sales);
                            let decrease_percent = parseFloat(decrease_sales) / parseFloat(total_sales) * 100;
                            if (decrease_percent == Infinity) {
                                var increased_percent_sales_string = '<i class="fas fa-infinity text-danger ml-4"></i><i class="fas fa-arrow-down ml-2 text-danger"></i>';
                            } else {
                                var increased_percent_sales_string = '<span class="ml-4 text-danger">' + decrease_percent.toFixed(2) + '% <i class="fas fa-arrow-down"></i></span>';
                            }
                        }
                        if (total_orders == previous_total_orders) {
                            var increased_percent_orders_string = '<span class="ml-4 text-info"> 0.00% <i class="fas fa-equals"></i></span>';
                        } else if (total_orders > previous_total_orders) {
                            let increase_orders = parseFloat(total_orders) - parseFloat(previous_total_orders);
                            let increased_percent = parseInt(increase_orders) / parseInt(previous_total_orders) * 100;
                            if (increased_percent == Infinity) {
                                var increased_percent_orders_string = '<i class="fas fa-infinity text-success ml-4"></i><i class="fas fa-arrow-up ml-2 text-success"></i>';
                            } else {
                                var increased_percent_orders_string = '<span class="ml-4 text-success">' + increased_percent.toFixed(2) + '% <i class="fas fa-arrow-up"></i></span>';
                            }
                        } else {
                            let decrease_orders = parseFloat(previous_total_orders) - parseFloat(total_orders);
                            let decrease_percent = parseInt(decrease_orders) / parseInt(total_orders) * 100;
                            if (decrease_percent == Infinity) {
                                var increased_percent_orders_string = '<i class="fas fa-infinity text-danger ml-4"></i><i class="fas fa-arrow-down ml-2 text-danger"></i>';
                            } else {
                                var increased_percent_orders_string = '<span class="ml-4 text-danger">' + decrease_percent.toFixed(2) + '% <i class="fas fa-arrow-down"></i></span>';
                            }
                        }
                        let total_sales_string = '';
                        let total_orders_string = '';
                        total_sales_string += '₹' + total_sales.toFixed(2) + increased_percent_sales_string;
                        total_orders_string += total_orders + increased_percent_orders_string;
                        $('#total_sales').append(total_sales_string);
                        $('#total_orders').append(total_orders_string);
                    });
                });
                $.each(data['fetch_total_sales_and_orders_yearly'], function(index, val) {
                    var total_sales_yearly = val.year_total_sales;
                    if (total_sales_yearly == null || total_sales_yearly == 0) {
                        total_sales_yearly = 0;
                    }
                    $.each(data['fetch_previous_total_sales_and_orders_yearly'], function(index, val) {
                        var previous_total_sales_yearly = val.previous_year_total_sales;
                        if (previous_total_sales_yearly == null || previous_total_sales_yearly == 0) {
                            previous_total_sales_yearly = 0;
                        }
                        if (total_sales_yearly == previous_total_sales_yearly) {
                            var increased_percent_sales_string = '<span class="ml-4 text-info"> 0.00% <i class="fas fa-equals"></i></span>';
                        } else if (total_sales_yearly > previous_total_sales_yearly) {
                            increase_sales = parseFloat(total_sales_yearly) - parseFloat(previous_total_sales_yearly);
                            let increased_percent = parseFloat(increase_sales) / parseFloat(previous_total_sales_yearly) * 100;
                            if (increased_percent == Infinity) {
                                var increased_percent_sales_string = '<i class="fas fa-infinity text-success ml-4"></i><i class="fas fa-arrow-up ml-2 text-success"></i>';
                            } else {
                                var increased_percent_sales_string = '<span class="ml-4 text-success">' + increased_percent.toFixed(2) + '% <i class="fas fa-arrow-up"></i></span>';
                            }
                        } else {
                            decrease_sales = parseFloat(previous_total_sales_yearly) - parseFloat(total_sales_yearly);
                            let decrease_percent = parseFloat(decrease_sales) / parseFloat(total_sales_yearly) * 100;
                            if (decrease_percent == Infinity) {
                                var increased_percent_sales_string = '<i class="fas fa-infinity text-danger ml-4"></i><i class="fas fa-arrow-down ml-2 text-danger"></i>';
                            } else {
                                var increased_percent_sales_string = '<span class="ml-4 text-danger">' + decrease_percent.toFixed(2) + '% <i class="fas fa-arrow-down"></i></span>';
                            }
                        }
                        let total_sales_yearly_string = '';
                        total_sales_yearly_string += '₹' + total_sales_yearly.toFixed(2) + increased_percent_sales_string;
                        $('#total_sales_yearly').append(total_sales_yearly_string);
                    });
                });

            }
        });
    }

    //fetch_new_members
    function fetch_new_members() {
        $.ajax({
            url: "dashboards/fetch_new_members",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#total_customers').empty();
                $.each(data['total_customers'], function(index, val) {
                    var total_customers = val.total_customers;
                    if (total_customers == null || total_customers == 0) {
                        total_customers = 0;
                    }
                    $.each(data['previous_total_customers'], function(index, val) {
                        var previous_total_customers = val.previous_total_customers;
                        if (previous_total_customers == null || previous_total_customers == 0) {
                            previous_total_customers = 0;
                        }
                        if (total_customers == previous_total_customers) {
                            var increased_percent_customers_string = '<span class="ml-4 text-info"> 0.00% <i class="fas fa-equals"></i></span>';
                        } else if (total_customers > previous_total_customers) {
                            let increase_customers = parseFloat(total_customers) - parseFloat(previous_total_customers);
                            var increased_percent = parseFloat(increase_customers) / parseFloat(previous_total_customers) * 100;
                            if (increased_percent == Infinity) {
                                var increased_percent_customers_string = '<i class="fas fa-infinity text-success ml-4"></i><i class="fas fa-arrow-up ml-2 text-success"></i>';
                            } else {
                                var increased_percent_customers_string = '<span class="ml-4 text-success">' + increased_percent.toFixed(2) + '% <i class="fas fa-arrow-up"></i></span>';
                            }
                        } else {
                            let decrease_customers = parseFloat(previous_total_customers) - parseFloat(total_customers);
                            var decrease_percent = parseFloat(decrease_customers) / parseFloat(total_customers) * 100;
                            if (decrease_percent == Infinity) {
                                var increased_percent_customers_string = '<i class="fas fa-infinity text-danger ml-4"></i><i class="fas fa-arrow-down ml-2 text-danger"></i>';
                            } else {
                                var increased_percent_customers_string = '<span class="ml-4 text-danger">' + decrease_percent.toFixed(2) + '% <i class="fas fa-arrow-down"></i></span>';
                            }
                        }
                        let total_customers_string = '';
                        total_customers_string += total_customers + increased_percent_customers_string;
                        $('#total_customers').append(total_customers_string);
                    });
                });

            }
        });
    }

    //fetch_sales_chart
    function fetch_sales_chart() {
        $.ajax({
            url: "dashboards/fetch_sales_chart_data",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#sales_chart_table_tbody').empty();
                var sales_list = '';
                $.each(data, function(index, val) {
                    if (val.month.substring(5, 7) == 01) {
                        var month_name = 'Jan';
                    } else if (val.month.substring(5, 7) == 02) {
                        var month_name = 'Feb';
                    } else if (val.month.substring(5, 7) == 03) {
                        var month_name = 'Mar';
                    } else if (val.month.substring(5, 7) == 04) {
                        var month_name = 'Apr';
                    } else if (val.month.substring(5, 7) == 05) {
                        var month_name = 'May';
                    } else if (val.month.substring(5, 7) == 06) {
                        var month_name = 'Jun';
                    } else if (val.month.substring(5, 7) == 07) {
                        var month_name = 'Jul';
                    } else if (val.month.substring(5, 7) == 08) {
                        var month_name = 'Aug';
                    } else if (val.month.substring(5, 7) == 09) {
                        var month_name = 'Sep';
                    } else if (val.month.substring(5, 7) == 10) {
                        var month_name = 'Oct';
                    } else if (val.month.substring(5, 7) == 11) {
                        var month_name = 'Nov';
                    } else if (val.month.substring(5, 7) == 12) {
                        var month_name = 'Dec';
                    }
                    if (val.total_order_amount == null) {
                        val.total_order_amount = 0;
                    }
                    sales_list += '<tr>';
                    sales_list += '<th>' + month_name + '</th>';
                    sales_list += '<td>' + val.total_order_amount.toFixed(2) + '</td>';
                    sales_list += '</tr>';
                });
                $('#sales_chart_table_tbody').append(sales_list);
                $('#sales_chart_table').dataTable({
                    "ordering": false,
                    initComplete: function() {
                        $('#sales_chart_table').parents('div.dataTables_wrapper').first().hide();
                    }
                });
                Highcharts.chart('sales_chart', {
                    data: {
                        table: 'sales_chart_table'
                    },
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '<b>Sales per month</b>'
                    },
                    xAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Months'
                        }
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Sales'
                        }
                    },
                    tooltip: {
                        formatter: function() {
                            return '<b>' + this.point.name + '</b><br/>' +
                            this.series.name + ' ₹' + this.point.y;
                        },

                    },
                    plotOptions: {
                        series: {
                            colorByPoint: true,
                        },
                        column: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: true,
                        },

                    },
                });
            }
        });

    }
    //fetch_customer_chart_data
    function fetch_customer_chart_data() {
        $.ajax({
            url: "dashboards/fetch_customer_chart_data",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                var total_customers = [];
                $.each(data, function(index, val) {
                    total_customers.push(val.total_customers);
                });
                var colors = Highcharts.getOptions().colors;
                Highcharts.chart('customers_chart', {
                    chart: {
                        type: 'area'
                    },
                    title: {
                        text: '<b>New customer per month</b>'
                    },
                    xAxis: {
                        allowDecimals: false,
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: '<b>Total Numbers</b> '
                        }
                    },
                    plotOptions: {
                        area: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: true,
                        },
                        series: {
                            // colorByPoint: true,
                            color: '#f8204a',
                        },
                    },
                    series: [{
                        fillColor: 'white',
                        name: 'New Customers',
                        data: total_customers,
                    }]
                });
            }
        });

    }

    //fetch_leatest_order
    function fetch_leatest_order() {
        $.ajax({
            url: "dashboards/fetch_leatest_order",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                let latest_orders_list = '';
                $('#latest_orders_table_tbody').empty();
                $.each(data, function(index, val) {
                    latest_orders_list += '<tr>';
                    latest_orders_list += '<td>' + val.id + '</td>';
                    latest_orders_list += '<td>' + val.pincode + '</td>';
                    latest_orders_list += '<td>' + val.village_name + '</td>';
                    latest_orders_list += '<td>' + val.total_payable_amount + '</td>';
                    latest_orders_list += '</tr>';
                });
                $('#latest_orders_table_tbody').append(latest_orders_list);
                $('#latest_orders_table').DataTable({
                    "dom": "tr",
                    "order": [0, 'desc'],
                });
            }
        });

    }

    //fetch_orders_status
    function fetch_orders_status_yearly() {
        $.ajax({
            url: "dashboards/fetch_orders_status_today",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                let orders_status_list = '';
                $('#order_status_today').empty();
                $.each(data, function(index, val) {
                    // if (val.active == null) {
                    //     val.active = 0;
                    // }
                    let active_percentage = (val.active * 100) / val.total_orders;
                    if (val.active == null) {
                        val.active = 0;
                        active_percentage = 0;
                    }
                    let ready_to_deliver_percentage = (val.ready_to_deliver * 100) / val.total_orders;
                    if (val.ready_to_deliver == null) {
                        val.ready_to_deliver = 0;
                        ready_to_deliver_percentage = 0;
                    }
                    let out_for_delivery_percentage = (val.out_for_delivery * 100) / val.total_orders;
                    if (val.out_for_delivery == null) {
                        val.out_for_delivery = 0;
                        out_for_delivery_percentage = 0;
                    }
                    let delivered_percentage = (val.delivered * 100) / val.total_orders;
                    if (val.delivered == null) {
                        val.delivered = 0;
                        delivered_percentage = 0;
                    }
                    let canceled_percentage = (val.canceled * 100) / val.total_orders;
                    if (val.canceled == null) {
                        val.canceled = 0;
                        canceled_percentage = 0;
                    }
                    let rejected_percentage = (val.rejected * 100) / val.total_orders;
                    if (val.rejected == null) {
                        val.rejected = 0;
                        rejected_percentage = 0;
                    }
                    orders_status_list += '<h4>Total: ' + val.total_orders + '</h4>';
                    orders_status_list += '<label class="">Pending</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: ' + active_percentage + '%;" aria-valuemin="0" aria-valuemax="' + val.total_orders + '">' + val.active + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2">Ready to deliver</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary" role="progressbar" style="width: ' + ready_to_deliver_percentage + '%;" aria-valuemin="0" aria-valuemax="' + val.total_orders + '">' + val.ready_to_deliver + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2">Out for delivery</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: ' + out_for_delivery_percentage + '%;" aria-valuemin="0" aria-valuemax="' + val.total_orders + '">' + val.out_for_delivery + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2">Delivered</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="' + val.total_orders + '" style="width: ' + delivered_percentage + '%">' + val.delivered + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2">Cancelled</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuemin="0" aria-valuemax="' + val.total_orders + '" style="width: ' + canceled_percentage + '%">' + val.canceled + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2 ">Rejected</label>';
                    orders_status_list += '<div class="progress mb-2">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuemin="0" aria-valuemax="' + val.total_orders + '" style="width:' + rejected_percentage + '%">' + val.rejected + '</div>';
                    orders_status_list += '</div>';
                });
                $('#order_status_today').append(orders_status_list);
            }
        });

    }

    //fetch_orders_status_monthly
    function fetch_orders_status_monthly() {
        $.ajax({
            url: "dashboards/fetch_orders_status_monthly",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                let orders_status_list = '';
                $('#fetch_orders_monthly').empty();
                $.each(data, function(index, val) {
                    let active_percentage = (val.active * 100) / val.total_orders;
                    if (val.active == null) {
                        val.active = 0;
                        active_percentage = 0;
                    }
                    let ready_to_deliver_percentage = (val.ready_to_deliver * 100) / val.total_orders;
                    if (val.ready_to_deliver == null) {
                        val.ready_to_deliver = 0;
                        ready_to_deliver_percentage = 0;
                    }
                    let out_for_delivery_percentage = (val.out_for_delivery * 100) / val.total_orders;
                    if (val.out_for_delivery == null) {
                        val.out_for_delivery = 0;
                        out_for_delivery_percentage = 0;
                    }
                    let delivered_percentage = (val.delivered * 100) / val.total_orders;
                    if (val.delivered == null) {
                        val.delivered = 0;
                        delivered_percentage = 0;
                    }
                    let canceled_percentage = (val.canceled * 100) / val.total_orders;
                    if (val.canceled == null) {
                        val.canceled = 0;
                        canceled_percentage = 0;
                    }
                    let rejected_percentage = (val.rejected * 100) / val.total_orders;
                    if (val.rejected == null) {
                        val.rejected = 0;
                        rejected_percentage = 0;
                    }
                    orders_status_list += '<h4>Total: ' + val.total_orders + '</h4>';
                    orders_status_list += '<label class="">Pending</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: ' + active_percentage + '%;" aria-valuemin="0" aria-valuemax="' + val.total_orders + '">' + val.active + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2">Ready to deliver</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary" role="progressbar" style="width: ' + ready_to_deliver_percentage + '%;" aria-valuemin="0" aria-valuemax="' + val.total_orders + '">' + val.ready_to_deliver + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2">Out for delivery</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: ' + out_for_delivery_percentage + '%;" aria-valuemin="0" aria-valuemax="' + val.total_orders + '">' + val.out_for_delivery + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2">Delivered</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="' + val.total_orders + '" style="width: ' + delivered_percentage + '%">' + val.delivered + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2">Cancelled</label>';
                    orders_status_list += '<div class="progress">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuemin="0" aria-valuemax="' + val.total_orders + '" style="width: ' + canceled_percentage + '%">' + val.canceled + '</div>';
                    orders_status_list += '</div>';
                    orders_status_list += '<label class="mt-2 ">Rejected</label>';
                    orders_status_list += '<div class="progress mb-2">';
                    orders_status_list += '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuemin="0" aria-valuemax="' + val.total_orders + '" style="width:' + rejected_percentage + '%">' + val.rejected + '</div>';
                    orders_status_list += '</div>';
                });
                $('#order_status_monthly').append(orders_status_list);
            }
        });

    }

    //fetch_top_selling_products
    function fetch_top_selling_products() {
        $.ajax({
            url: "dashboards/fetch_top_selling_products",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                let top_selling_product_list = '';
                $('#top_selling_products_table_tbody').empty();
                $.each(data, function(index, val) {
                    top_selling_product_list += '<tr>';
                    top_selling_product_list += '<td>' + val.product_id + '</td>';
                    top_selling_product_list += '<td>' + val.products_name + '</td>';
                    top_selling_product_list += '<td>' + val.total_qty + '</td>';
                    top_selling_product_list += '<td>' + val.total_amount + '</td>';
                    top_selling_product_list += '</tr>';
                });
                $('#top_selling_products_table_tbody').append(top_selling_product_list);
                $('#top_selling_products_table').DataTable({
                    "dom": "tr",
                    "order": [2, 'desc'],
                });
            }
        });

    }

    //fetch_pin_wise_orders
    function fetch_pin_wise_order() {
        $.ajax({
            url: "dashboards/fetch_pin_wise_order",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                var ajaxData = [];
                var total_orders = 0;
                $.each(data, function(index, val) {

                    total_orders += parseInt(val.pin_wise_total_orders);
                    ajaxData.push({ name: val.pincode, y: val.pin_wise_total_orders, amount: val.order_amount });
                });
                Highcharts.chart('pin_wise_pie', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: '<b>Pin code wise orders</b>'
                    },
                    subtitle: {
                        text: 'Total orders : ' + total_orders
                    },
                    tooltip: {
                        headerFormat: '{series.name}: <b>{point.y} </b><br>',
                        pointFormat: 'Amount: <b>{point.amount} </b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'Orders',
                        colorByPoint: true,
                        data: ajaxData
                    }]
                });
            }
        });
    }

    //fetch_customer_feedback

    function fetch_customer_feedback(refresh) {
        if (refresh == 1) {
            customer_feedbacks_table.destroy();
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        customer_feedbacks_table = $('#customer_feedbacks_table').DataTable({

            "processing": true,
            "serverSide": true,
            "dom": 'trip',
            "order": [2, 'desc'],
            "ajax": {
                "url": "dashboards/fetch_customer_feedback",
                "dataType": "json",
                "type": "POST",
            },
            "columns": [{
                "data": "customer"
            }, {
                "data": "subject"
            }, {
                "data": "date"
            }, {
                "data": "status"
            }, {
                "data": "action"
            }],
            'columnDefs': [{

                'targets': [4],

                'orderable': false,

            }]
        });
    }
    //reload__feedback_table
    $('#btn_refresh_feedback_table').click(function() {
        customer_feedbacks_table.ajax.reload();
    });
    //view_feedback_details
    window.view_feedback_details = function(feedback_id) {
        $('#feedback_modal').empty();
        let modal_str = '';
        modal_str += '<div class="modal fade" id="view_feedback_details_modal' + feedback_id + '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        modal_str += '<div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">';
        modal_str += '<div class="modal-content">';
        modal_str += '<div class="modal-header">';
        modal_str += '<h5 class="modal-title" id="exampleModalLabel">Feedback</h5>';
        modal_str += '<button type="button" id="btn_modal_close' + feedback_id + '" class="close" data-dismiss="modal" aria-label="Close">';
        modal_str += '  <span aria-hidden="true">&times;</span>';
        modal_str += '</button>';
        modal_str += '</div>';
        modal_str += '<div class="modal-body">';
        modal_str += '<p class="border-bottom border-dark"><strong class="h3">Feedback Details</strong></p>';
        modal_str += '<table class="table table-sm table-borderless">';
        modal_str += '<colgroup>';
        modal_str += '<col style="width:120px;">';
        modal_str += '<col style="width:10px;">';
        modal_str += '<col>';
        modal_str += '</colgroup>';
        modal_str += '<tr>';
        modal_str += '<td><strong>Customer name</strong></td>';
        modal_str += '<td>:</td>';
        modal_str += '<td><span id="customer_name' + feedback_id + '"></span></td>';
        modal_str += '</tr>';
        modal_str += '<tr>';
        modal_str += '<td><strong>Subject</strong></td>';
        modal_str += '<td>:</td>';
        modal_str += '<td><span id="feedback_subject' + feedback_id + '"></span></td>';
        modal_str += '</tr>';
        modal_str += '<tr>';
        modal_str += '<td><strong>Details</strong></td>';
        modal_str += '<td>:</td>';
        modal_str += ' <td><span id="feedback_details' + feedback_id + '"></span></td>';
        modal_str += '</tr>';
        modal_str += '<tr>';
        modal_str += '<td><strong>Submitted on</strong></td>';
        modal_str += '<td>:</td>';
        modal_str += '<td><span id="feedback_submitted_on' + feedback_id + '"></span></td>';
        modal_str += '</tr>';
        modal_str += '<tr id="reply_status' + feedback_id + '">';
        modal_str += '</tr>';
        modal_str += '<tr id="reply_msg' + feedback_id + '">';
        modal_str += '</tr>';
        modal_str += '</table>';
        modal_str += '</div>';
        modal_str += '</div>';
        modal_str += '</div>';
        modal_str += '</div>';
        $('#feedback_modal').append(modal_str);
        $.ajax({
            url: "dashboards/fetch_feedback_details",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { feedback_id: feedback_id },
            dataType: 'json',
            success: function(data) {
                $('#customer_name').empty();
                $('#feedback_subject').empty();
                $('#feedback_details').empty();
                $('#feedback_submitted_on').empty();

                $.each(data, function(index, val) {
                    let date = new Date((val.submitted_on) * 1000);
                    $('#customer_name' + feedback_id).html(val.customer_name);
                    $('#feedback_subject' + feedback_id).html(val.subject);
                    $('#feedback_details' + feedback_id).html(val.details);
                    $('#feedback_submitted_on' + feedback_id).html(date.toLocaleString('en-IN'));
                    if (val.is_replied == 0) {
                        $('#reply_msg' + feedback_id).empty();
                        let reply = '';
                        reply += '<td><strong>Reply</strong></td>';
                        reply += '<td>:</td>';
                        reply += '<td><textarea class="form-control" id="reply_msg_box' + feedback_id + '" placeholder="Enter Message"></textarea></td>';
                        reply += '<td><button class="btn btn-info" id="reply_msg_btn' + feedback_id + '"><i class="fas fa-reply"></i> Reply</button>';
                        reply += '</td>';
                        $('#reply_msg' + feedback_id).append(reply);
                        $('#reply_msg_btn' + feedback_id).click(function() {
                            let replied_msg = $('#reply_msg_box' + feedback_id).val();
                            if (replied_msg == "") {
                                toastr.error("Please Enter something");
                            } else {
                                $('#btn_modal_close' + feedback_id).click();
                                $('#btn_view_feedback_deails' + feedback_id).html('<span class="spinner-border spinner-border-sm"></span>');
                                $('#btn_view_feedback_deails' + feedback_id).prop('disabled', true);
                                $.ajax({
                                    url: "dashboards/update_reply_msg",
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: { feedback_id: feedback_id, replied_msg: replied_msg, customer_email: val.customer_email, customer_name: val.customer_name },
                                    dataType: 'json',
                                    success: function(data) {
                                        if (data['success'] == 1) {
                                            toastr.success(data['message']);
                                            fetch_customer_feedback(1)
                                        } else {
                                            toastr.error(data['message']);
                                        }
                                    }
                                });
                            }
                        });
                    } else {
                        $('#reply_status' + feedback_id).empty();
                        let reply_status = '';
                        reply_status += '<td><strong>Status</strong></td>';
                        reply_status += '<td>:</td>';
                        reply_status += '<td><span class="spinner-grow spinner-grow-sm text-success" style="width: 18px; height: 18px;" role="status"></span><span class="ml-2 text-success h5">Replied</span></td>';
                        $('#reply_status' + feedback_id).append(reply_status);
                        $('#reply_msg' + feedback_id).empty();
                        let reply = '';
                        reply += '<td><strong>Reply</strong></td>';
                        reply += '<td>:</td>';
                        reply += '<td><span>' + val.reply_message + '</span></td>';
                        $('#reply_msg' + feedback_id).append(reply);
                    }
                });

            }
        });

    }
}

function product() {
    var product_table = '';
    $("#product_menu").addClass("menu-open");
    fetch_product_list(0);

    fetch_category();
    fetch_unit_type();
    //===============on image change====================
    $("#image1").change(function() {
        let image = "1";
        readURL(this, image);
    });
    $("#image2").change(function() {
        let image = "2";
        readURL(this, image);
    });
    $("#image3").change(function() {
        let image = "3";
        readURL(this, image);
    });

    //===================fetched_products_list======================
    function fetch_product_list(refresh, category_id) {
        if (refresh == 1) {
            product_table.destroy();
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        product_table = $('#product_list_table').DataTable({

            "processing": true,
            "serverSide": true,
            "dom": 'trip',
            "order": [0, 'desc'],
            "ajax": {
                "url": "products/fetch_product_list",
                "data": { filter_category: category_id },
                "dataType": "json",
                "type": "POST",
            },
            "columns": [{
                "data": "id"
            }, {
                "data": "product_image"
            }, {
                "data": "name"
            }, {
                "data": "category"
            }, {
                "data": "sub_category"
            }, {
                "data": "is_in_stock"
            }, {
                "data": "brand"
            }, {
                "data": "mrp"
            }, {
                "data": "discount"
            }, {
                "data": "selling_price"
            }, {
                "data": "action"
            }],
            'columnDefs': [{

                'targets': [1, 10],

                'orderable': false,

            }]
        });
    }
    //===================fetch_category======================
    function fetch_category() {
        let category_list = '';
        let filter_category_list = '';
        $.ajax({
            url: "products/fetch_category",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(fetched_category) {
                category_list += '<option value="" id="select_category">Select Category</option>';
                filter_category_list += '<option value="" id="select_product_filter_category">Select Category</option>';
                $.each(fetched_category, function(index, val) {
                    let id = val.id;
                    let name = val.name;
                    category_list += '<option value="' + id + '">' + name + '</option>';
                    filter_category_list += '<option value="' + name + '">' + name + '</option>';
                });
                $('#category').append(category_list);
                $('#product_filter_category').append(filter_category_list);
            }
        });
    }
    //====================fetch unit type================================
    function fetch_unit_type() {
        let unit_type_list = '';
        $.ajax({
            url: "products/fetch_unit_type",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(fetched_unit) {
                unit_type_list += '<option value="" id="select_unit_type">Select unit type</option>';
                $.each(fetched_unit, function(index, val) {
                    let id = val.id;
                    let name = val.name;
                    unit_type_list += '<option value="' + id + '">' + name + '</option>';
                });
                $('#unit_type').append(unit_type_list);
            }
        });
    }
    //===================fetch sub category============================
    $("#category").change(function() {
        fetch_sub_category();
    });

    function fetch_sub_category() {
        let category_id = $('#category').val();
        let sub_category_list = '';
        if (category_id != "") {
            $.ajax({
                url: "products/fetch_sub_category",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function(fetched_sub_category) {
                    $('#sub_category').empty();
                    sub_category_list += '<option value="">Select sub category</option>';
                    $.each(fetched_sub_category, function(index, val) {
                        let id = val.id;
                        let name = val.name;
                        sub_category_list += '<option value="' + id + '">' + name + '</option>';
                    });
                    $('#sub_category').append(sub_category_list);
                }
            });
        } else {
            $('#sub_category').empty();
        }
    }
    //===================search brand===========================
    $('#brand_search').keyup(function() {
        $('#selected_brand_id').val('');
        let search_brand_text = $(this).val();
        let num_of_letters = $("#brand_search").val().length;
        if (num_of_letters >= 2) {
            $('#brand_search_loader').addClass("loading");
            $.ajax({
                url: "products/fetch_brand",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    search_brand_text: search_brand_text
                },
                dataType: 'json',
                success: function(fetched_brand) {
                    if (fetched_brand != '') {
                        $('#show_brand_list').empty();
                        let brand_list = '';
                        $.each(fetched_brand, function(index, val) {
                            let id = val.id;
                            let name = val.name;
                            brand_list += '<a href="' + id + '" class="list-group-item list-group-item-action">' + name + '</a>';
                        });
                        $('#show_brand_list').show();
                        $('#show_brand_list').append(brand_list);
                        $('#brand_search_loader').removeClass("loading");
                    } else {
                        $('#show_brand_list').empty();
                        $('#show_brand_list').append('<a href="#" class="list-group-item list-group-item-action disabled">No data found</a>');
                        $('#brand_search_loader').removeClass("loading");
                    }

                }
            });
        } else {
            $('#show_brand_list').empty();
        }
    })
    $('#show_brand_list').on('click', 'a', function(e) {
        e.preventDefault();
        let list_text = $(this).text();
        let selected_brand = $(this).attr('href');
        $('#selected_brand_id').val(selected_brand);
        $('#brand_search').val(list_text);
        $('#show_brand_list').hide();
    });
    //====================Discount calculation===============================
    document.getElementById("mrp").onkeyup = function() {
        discount_calculation();
    };
    document.getElementById("discount_percentage").onkeyup = function() {
        discount_calculation();
    };

    function discount_calculation() {

        let mrp = $('#mrp').val();
        let discount_percentage = $('#discount_percentage').val();
        let discount_amount = (parseFloat(mrp) * parseFloat(discount_percentage)) / 100;
        let final_amount = parseFloat(mrp) - parseFloat(discount_amount);
        if (!isNaN(final_amount)) {
            $('#selling_price').val(final_amount.toFixed(2));
        } else {
            $('#selling_price').val(0);
        }
    }
    //=====================image Preview ==================================
    function readURL(input, img) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                if (img == "1") {
                    $('#imagePreview1').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview1').hide();
                    $('#imagePreview1').fadeIn(650);
                } else if (img == "2") {
                    $('#imagePreview2').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview2').hide();
                    $('#imagePreview2').fadeIn(650);
                } else {
                    $('#imagePreview3').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview3').hide();
                    $('#imagePreview3').fadeIn(650);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    //=====================Add Product===================
    $('#add_product_form').submit(function(e) {
        e.preventDefault();
        let product_name = document.getElementById("product_name").value;
        let category = document.getElementById("category").value;
        let category_name = $("#category option:selected").html();
        let sub_category = document.getElementById("sub_category").value;
        let sub_category_name = $("#sub_category option:selected").html();
        let brand_id = document.getElementById("selected_brand_id").value;
        let brand_name = document.getElementById("brand_search").value;
        let mrp = document.getElementById("mrp").value;
        let discount_percentage = document.getElementById("discount_percentage").value;
        let selling_price = document.getElementById("selling_price").value;
        let minimum_order = document.getElementById("minimum_order").value;
        let maximum_order = document.getElementById("maximum_order").value;
        let product_description = document.getElementById("product_description").value;
        let unit_type = document.getElementById("unit_type").value;
        let quantity = document.getElementById("quantity").value;
        let product_tags = document.getElementById("product_tags").value;
        let image_data1 = $('#image1')[0].files[0];
        let image_data2 = $('#image2')[0].files[0];
        let image_data3 = $('#image3')[0].files[0];
        let form_data = new FormData();
        if (image_data1 != undefined) {
            form_data.append('image1', image_data1);
        }
        if (image_data2 != undefined) {
            form_data.append('image2', image_data2);
        }
        if (image_data3 != undefined) {
            form_data.append('image3', image_data3);
        }
        if (product_name == '' || category == '' || sub_category == '' || mrp == '' || discount_percentage == '' || selling_price == '' || maximum_order == '' || minimum_order == '' || product_description == '' || unit_type == '' || quantity == '' || product_tags == '') {
            toastr.error(" * Marked Items Are mandatory To fill");
        } else if (product_description.length > 250) {
            toastr.error("Product description should not exceed 250 words.");
        } else if (brand_id == '') {
            toastr.error(" Please select a brand name");
        } else if (parseInt(minimum_order) > parseInt(maximum_order)) {
            toastr.error(" Minimum order quantity should be less than Maximum order quantity.");
        } else if (parseInt(minimum_order) == 0) {
            toastr.error(" The minimum order quantity should not be 0.");
        } else if (parseInt(maximum_order == 0)) {
            toastr.error(" The Maximum order quantity should not be 0.");
        } else if (quantity == 0) {
            toastr.error(" Unit quantity should not be 0");
        } else if (mrp < 0 || discount_percentage < 0 || selling_price < 0 || minimum_order < 0 || maximum_order < 0 || quantity < 0) {
            toastr.error("All value have to be positive(+)");
        } else if (image_data1 == undefined) {
            toastr.error("Please select atleast one image");
        } else {
            form_data.append('product_name', product_name);
            form_data.append('category', category);
            form_data.append('sub_category', sub_category);
            form_data.append('brand_id', brand_id);
            form_data.append('mrp', mrp);
            form_data.append('discount_percentage', discount_percentage);
            form_data.append('selling_price', selling_price);
            form_data.append('minimum_order', minimum_order);
            form_data.append('maximum_order', maximum_order);
            form_data.append('product_description', product_description);
            form_data.append('unit_type', unit_type);
            form_data.append('quantity', quantity);
            form_data.append('product_tags', product_tags);
            form_data.append('category_name', category_name);
            form_data.append('sub_category_name', sub_category_name);
            form_data.append('brand_name', brand_name);
            $('#add_product').prop('disabled', true);
            $('#add_product').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: "products/insert_product_details",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        $('#add_product').prop('disabled', false);
                        $('#add_product').html('Add');
                        toastr.success((data['message']));
                        reset_all_feild();
                        fetch_product_list(1);
                    } else {
                        $('#add_product').html('Add');
                        $('#add_product').prop('disabled', false);
                        toastr.error(data['message']);
                    }
                }
            });
        }

    });
    //===============reset=============
    function reset_all_feild() {
        $("#product_name").val('');
        $("#sub_category").empty();
        $("#brand_search").val('');
        $("#mrp").val('');
        $("#discount_percentage").val('');
        $("#selling_price").val('');
        $("#minimum_order").val('');
        $("#maximum_order").val('');
        $("#product_description").val('');
        $("#product_tags").val('');
        $("#quantity").val('');
        $("#image1").val('');
        $("#image2").val('');
        $("#image3").val('');
        $('#imagePreview1').css('background-image', ' url(assets/dist/img/product_default_image.png)');
        $('#imagePreview2').css('background-image', ' url(assets/dist/img/product_default_image.png)');
        $('#imagePreview3').css('background-image', ' url(assets/dist/img/product_default_image.png)');
        $('#select_category').attr('selected', 'selected');
        $('#select_unit_type').attr('selected', 'selected');

    }
    //==========table_filter=============
    $('#search_product_filter').keyup(function() {
        product_table.search(this.value).draw();
    });
    $("#product_filter_category").change(function() {
        let filter_category_id = $("#product_filter_category").val();
        fetch_product_list(1, filter_category_id);
    });
    $("#btn_refresh_product_table").click(function() {
        product_table.ajax.reload();
    });
    //==================view-product========
    window.view_product_details = function(product_id) {
            $('#view_product_image').empty();
            $('#product_images_carousel_indicators').empty();
            $("#v_avalibility").empty();
            $.ajax({
                url: "products/product_details_list",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { product_id: product_id },
                dataType: 'json',
                success: function(fetched_products) {

                    $.each(fetched_products, function(index, val) {
                        let total_order = val.order_details.total_order;
                        let total_amount = val.order_details.total_order_amount;
                        if (total_order == null) {
                            total_order = 0;
                        }
                        if (total_amount == null) {
                            total_amount = 0;
                        }
                        $('#v_unit_type').html(val.product_unit_type);
                        $('#v_unit_quantity').html(val.quantity);
                        $("#v_product_name").html(val.name);
                        $("#v_category").html(val.product_category_name);
                        $("#v_sub_category").html(val.product_sub_category_name);
                        $("#v_brand_name").html(val.product_brand_name);
                        $("#v_mrp").html(val.mrp);
                        $("#v_discount_percentage").html(val.discount + '%');
                        $("#v_selling_price").html(val.sell_price);
                        $("#v_min_order_qty").html(val.min_qty);
                        $("#v_max_order_qty").html(val.max_qty);
                        $("#v_product_description").html(val.desc);
                        $("#v_total_orders").html(total_order);
                        $("#v_total_amount").html(total_amount);
                        let date = new Date((val.created_on) * 1000);
                        $("#v_created_on").html(date.toLocaleString('en-IN'));
                        if (val.is_in_stock == "1") {
                            $("#v_avalibility").append('<span class="spinner-grow spinner-grow-sm text-success" style="width: 18px; height: 18px;" role="status"></span><span class="ml-2 text-success h5">IN STOCK</span>');
                        } else {
                            $("#v_avalibility").append('<span class="spinner-grow spinner-grow-sm text-danger" style="width: 18px; height: 18px;" role="status"></span><span class="ml-2 text-danger h5">OUT OF STOCK</span>');
                        }
                        let carousel_images = '';
                        let carousel_indicators = '';
                        $.each(val.images, function(index, value) {
                            carousel_images += '<div class="carousel-item" id="carousel_first_image' + index + '">';
                            carousel_images += '<img src="' + value.url + '" class="img-thumbnail" height="100px" width="300px" alt="...">';
                            carousel_images += '</div>';
                            carousel_indicators += '<li data-target="#product_images_carousel" data-slide-to="' + index + '" id="carousel_first_indicator' + index + '"></li>';
                        });
                        $('#view_product_image').append(carousel_images);
                        $('#carousel_first_image0').addClass("active");
                        $('#product_images_carousel_indicators').append(carousel_indicators);
                        $('#carousel_first_indicator0').addClass("active");
                    });
                }
            });
        }
        //==================edit-product========
    window.edit_product_details = function(product_id) {
        $('#edit_product_modal').empty();
        var create_edit_modal = "";
        create_edit_modal = '<div class="modal fade" id="edit_product_modal' + product_id + '" tabindex="-1" data-backdrop="static" data-keyboard="false">';
        create_edit_modal += '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">';
        create_edit_modal += '<div class="modal-content">';
        create_edit_modal += '<div class="modal-header">';
        create_edit_modal += '<h5 class="modal-title">EDIT PRODUCT</h5>';
        create_edit_modal += '</div>';
        //===============body========================
        create_edit_modal += '<div class="modal-body">';
        create_edit_modal += '<form class="" id="edit_product_image_form" methode="post" enctype="multipart/form-data">';
        create_edit_modal += '<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#edit_images_area" aria-expanded="false" aria-controls="collapseExample">';
        create_edit_modal += 'Edit image';
        create_edit_modal += '</button>';
        create_edit_modal += '<div class="collapse mt-2" id="edit_images_area">';
        create_edit_modal += '<div class="row">';
        create_edit_modal += '<div class="col-md-12 d-flex justify-content-center">';
        create_edit_modal += '<div class="">';
        create_edit_modal += '<div class="avatar-upload">';
        create_edit_modal += '<div class="avatar-edit">';
        create_edit_modal += '<input type="file" id="e_image1" name="e_image1" accept=".png, .jpg, .jpeg" />';
        create_edit_modal += '<label for="e_image1"><i class="fas fa-plus ml-1"></i></label>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="avatar-preview">';
        create_edit_modal += '<div id="e_imagePreview1" style="background-image: url(assets/dist/img/product_default_image.png);">';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '<span class="ml-4 mb-2">Image 1<span class="text-danger">*</span></span>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="ml-3">';
        create_edit_modal += '<div class="avatar-upload">';
        create_edit_modal += '<div class="avatar-edit">';
        create_edit_modal += '<input type="file" id="e_image2" name="e_image2" accept=".png, .jpg, .jpeg" />';
        create_edit_modal += '<label for="e_image2"><i class="fas fa-plus ml-1"></i></label>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="avatar-preview">';
        create_edit_modal += '<div id="e_imagePreview2" style="background-image: url(assets/dist/img/product_default_image.png);">';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '<span class="ml-4 mb-2">Image 2</span>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="ml-3">';
        create_edit_modal += '<div class="avatar-upload">';
        create_edit_modal += '<div class="avatar-edit">';
        create_edit_modal += '<input type="file" id="e_image3" name="e_image3" accept=".png, .jpg, .jpeg" />';
        create_edit_modal += '<label for="e_image3"><i class="fas fa-plus ml-1"></i></label>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="avatar-preview">';
        create_edit_modal += '<div id="e_imagePreview3" style="background-image: url(assets/dist/img/product_default_image.png);">';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '<span class="ml-4 mb-2">Image 3</span>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="d-flex ml-auto">';
        // create_edit_modal += '<button class="ui button" data-dismiss="modal" id="edit_modal_cancel' + product_id + '">Cancel</button>';
        // create_edit_modal += '<div class="or"></div>';
        create_edit_modal += '<button class="ui positive button mt-4" id="update_image_btn' + product_id + '">Update image</button>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</form>';
        create_edit_modal += '<form class="" id="edit_product_form" methode="post" enctype="multipart/form-data">';
        //==================================================================================
        create_edit_modal += '<div class="form-row mt-2">';
        create_edit_modal += '<div class="form-group col-md-6">';
        create_edit_modal += '<label for="e_product_name">Name<span class="text-danger">*</span></label>';
        create_edit_modal += '<input type="text" class="form-control" id="e_product_name" placeholder="Enter Name" autocomplete="off">';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="form-group col-md-6">';
        create_edit_modal += '<label for="e_category">Category<span class="text-danger">*</span></label>';
        create_edit_modal += '<select class="custom-select" id="e_category">';
        create_edit_modal += '</select>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //=========================================================
        create_edit_modal += '<div class="form-row">';
        create_edit_modal += '<div class="form-group col-md-6">';
        create_edit_modal += '<label for="e_sub_category">Sub category<span class="text-danger">*</span></label>';
        create_edit_modal += '<select class="custom-select" id="e_sub_category">';
        create_edit_modal += '</select>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="form-group col-md-6">';
        create_edit_modal += '<label for="e_brand_search">Brand<span class="text-danger">*</span></label>';
        create_edit_modal += '<input type="hidden" id="e_selected_brand_id" name="e_selected_brand_id" value="">';
        create_edit_modal += '<div id="e_brand_search_loader" class="ui left icon  input purple double fluid">';
        create_edit_modal += '<input type="text" class="form-control" id="e_brand_search" placeholder="Search & select brand" autocomplete="off">';
        create_edit_modal += '<i class="search icon"></i>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="list-group" id="e_show_brand_list">';

        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //============================================================
        create_edit_modal += '<div class="form-row">';
        create_edit_modal += '<div class="form-group col-md-6">';
        create_edit_modal += '<label for="e_minimum_order">Minimum order<span class="text-danger">*</span></label>';
        create_edit_modal += '<input type="number" class="form-control" id="e_minimum_order" placeholder="Enter minimum order" autocomplete="off">';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="form-group col-md-6">';
        create_edit_modal += '<label for="e_maximum_order">Maximum order<span class="text-danger">*</span></label>';
        create_edit_modal += '<input type="number" class="form-control" id="e_maximum_order" placeholder="Enter maximum order" autocomplete="off">';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //============================================================
        create_edit_modal += '<div class="form-row">';
        create_edit_modal += '<div class="form-group col-md-6">';
        create_edit_modal += '<label for="e_unit_type">Unit type<span class="text-danger">*</span></label>';
        create_edit_modal += '<select class="custom-select" id="e_unit_type">';
        create_edit_modal += '</select>';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="form-group col-md-6">';
        create_edit_modal += '<label for="e_quantity">Quantity<span class="text-danger">*</span></label>';
        create_edit_modal += '<input type="number" class="form-control" id="e_quantity" placeholder="Enter quantity" autocomplete="off">';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //============================================================
        create_edit_modal += '<div class="form-row">';
        create_edit_modal += '<div class="form-group col-md-4">';
        create_edit_modal += '<label for="e_mrp">MRP(₹)<span class="text-danger">*</span></label>';
        create_edit_modal += '<input type="number" class="form-control" id="e_mrp" placeholder="Enter MRP" autocomplete="off">';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="form-group col-md-4">';
        create_edit_modal += '<label for="e_discount_percentage">Discount(%)<span class="text-danger">*</span></label>';
        create_edit_modal += '<input type="number" class="form-control" id="e_discount_percentage" placeholder="Enter discount percentage" autocomplete="off">';
        create_edit_modal += '</div>';
        create_edit_modal += '<div class="form-group col-md-4">';
        create_edit_modal += '<label for="e_selling_price">Selling price(₹)</label>';
        create_edit_modal += '<input type="number" class="form-control" id="e_selling_price" disabled>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //=======================================================================
        create_edit_modal += '<div class="form-row">';
        create_edit_modal += '<div class="form-group col-md-12">';
        create_edit_modal += '<label for="e_product_description">Product description<span class="text-danger">*</span></label>';
        create_edit_modal += '<textarea class="form-control" aria-label="With textarea" placeholder="Enter product description" id="e_product_description"></textarea>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //=======================================================================
        create_edit_modal += '<div class="form-row">';
        create_edit_modal += '<div class="form-group col-md-12">';
        create_edit_modal += '<label for="e_product_tag">Product tags(<small><i class="fas fa-tags"></i></small>)<span class="text-danger">*</span></label>';
        create_edit_modal += '<textarea class="form-control" aria-label="With textarea" placeholder="Enter product tags" id="e_product_tag"></textarea>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //=======================================================================
        create_edit_modal += '<div class="form-row">';
        create_edit_modal += '<div class="form-group col-md-12">';
        create_edit_modal += '<div class="ui ">';
        create_edit_modal += '<div class="custom-control custom-switch custom-switch-lg ml-2" id="stock_switch' + product_id + '">';

        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //======================================
        create_edit_modal += '</form>';
        create_edit_modal += '</div>';
        //end of body==========================
        create_edit_modal += '<div class="modal-footer">';
        create_edit_modal += '<div class="ui buttons">';
        create_edit_modal += '<button class="ui button" data-dismiss="modal" id="edit_modal_cancel' + product_id + '">Cancel</button>';
        create_edit_modal += '<div class="or"></div>';
        create_edit_modal += '<button class="ui positive button" id="edit_save_changes_btn' + product_id + '">Save changes</button>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        //end of footer========================
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        create_edit_modal += '</div>';
        $('#edit_product_modal').append(create_edit_modal);
        var old_image_data = [];
        var product_name = '';
        var old_tag_array = [];
        //fetch_category
        $.ajax({
            url: "products/product_details_list",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { product_id: product_id },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, val) {
                    var count = 1;
                    $.each(val.images, function(index, val) {
                        $('#e_imagePreview' + count).css('background-image', ' url(' + val.url + ')');
                        old_image_data.push({ id: val.id });
                        count++;
                    });
                    let tag_array = [];
                    $.each(val.product_tags, function(index, val) {
                        tag_array.push(val.tag_name)
                    });
                    $('#e_product_tag').val(tag_array.join());
                    old_tag_array = tag_array.join();
                    var previous_category = val.category_id;
                    var previous_unit_type = val.unit_type_id;
                    $.ajax({
                        url: "products/fetch_category",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function(fetched_category) {
                            var category_list = '<option value="" id="select_category">Select Category</option>';
                            $.each(fetched_category, function(index, val) {
                                let id = val.id;
                                let name = val.name;
                                if (previous_category == id) {
                                    category_list += '<option value="' + id + '" selected>' + name + '</option>';
                                } else {
                                    category_list += '<option value="' + id + '">' + name + '</option>';
                                }
                            });
                            $('#e_category').append(category_list);
                        }
                    });
                    //====================fetch unit type================================
                    $.ajax({
                        url: "products/fetch_unit_type",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function(fetched_unit) {
                            let unit_type_list = '<option value="" id="select_unit_type">Select unit type</option>';
                            $.each(fetched_unit, function(index, val) {
                                let id = val.id;
                                let name = val.name;
                                if (previous_unit_type == id) {
                                    unit_type_list += '<option value="' + id + '" selected>' + name + '</option>';
                                } else {
                                    unit_type_list += '<option value="' + id + '">' + name + '</option>';
                                }
                            });
                            $('#e_unit_type').append(unit_type_list);
                        }
                    });
                    $('#e_quantity').val(val.quantity);
                    $('#e_selected_brand_id').val(val.brand_id);
                    $('#e_brand_search').val(val.product_brand_name);

                    $("#e_sub_category").append('<option value="' + val.subcategory_id + '">' + val.product_sub_category_name + '</option>');
                    $("#e_product_name").val(val.name);
                    product_name = val.name;
                    $("#e_mrp").val(val.mrp);
                    $("#e_discount_percentage").val(val.discount);
                    $("#e_selling_price").val(val.sell_price);
                    $("#e_minimum_order").val(val.min_qty);
                    $("#e_maximum_order").val(val.max_qty);
                    $("#e_product_description").val(val.desc);
                    $("#stock_switch" + product_id).empty();
                    let stock_switch = '';
                    if (val.is_in_stock == 1) {
                        stock_switch += '<input type="checkbox" class="custom-control-input " id="stock_change' + product_id + '" checked>';
                        stock_switch += '<label class="custom-control-label" for="stock_change' + product_id + '" id="stock_change_level' + product_id + '"><h2 id="stock_text" class="text-success">IN STOCK</h2></label>';
                    } else {
                        stock_switch += '<input type="checkbox" class="custom-control-input " id="stock_change' + product_id + '">';
                        stock_switch += '<label class="custom-control-label " for="stock_change' + product_id + '" id="stock_change_level' + product_id + '"><h2 id="stock_text" class="text-deepred">OUT OF STOCK</h2></label>';
                    }
                    $("#stock_switch" + product_id).append(stock_switch);
                    $('#stock_change' + product_id).click(function() {
                        $('#stock_change_level' + product_id).empty();
                        if ($(this).is(":checked")) {
                            $('#stock_change_level' + product_id).html('<h2 id="stock_text" class="text-success">IN STOCK</h2>');
                        } else {
                            $('#stock_change_level' + product_id).html('<h2 id="stock_text" class="text-deepred">OUT OF STOCK</h2>');
                        }
                    });
                });
            }
        });
        $('#update_image_btn' + product_id).click(function(e) {
            e.preventDefault();
            var e_image_form_data = new FormData();
            e_image_form_data.append('old_data', JSON.stringify(old_image_data));
            e_image_form_data.append('product_id', product_id);
            e_image_form_data.append('product_name', product_name);
            let e_image_data1 = $('#e_image1')[0].files[0];
            let e_image_data2 = $('#e_image2')[0].files[0];
            let e_image_data3 = $('#e_image3')[0].files[0];
            if (e_image_data1 == undefined && e_image_data2 == undefined && e_image_data3 == undefined) {
                toastr.error("Please change at least one image to update");
            } else {
                if (e_image_data1 != undefined) {
                    e_image_form_data.append('image1', e_image_data1);
                }
                if (e_image_data2 != undefined) {
                    e_image_form_data.append('image2', e_image_data2);
                }
                if (e_image_data3 != undefined) {
                    e_image_form_data.append('image3', e_image_data3);
                }

                $('#update_image_btn' + product_id).prop('disabled', true);
                $('#update_image_btn' + product_id).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                $.ajax({
                    url: "products/update_product_image",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: e_image_form_data,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data['success'] == 1) {
                            toastr.success((data['message']));

                            $.ajax({
                                url: "products/product_details_list",
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: { product_id: product_id },
                                dataType: 'json',
                                success: function(data) {
                                    $.each(data, function(index, val) {
                                        var count = 1;
                                        $('#e_imagePreview1').css('background-image', ' url(assets/dist/img/product_default_image.png)');
                                        $('#e_imagePreview2').css('background-image', ' url(assets/dist/img/product_default_image.png)');
                                        $('#e_imagePreview3').css('background-image', ' url(assets/dist/img/product_default_image.png)');
                                        $.each(val.images, function(index, val) {
                                            $('#e_imagePreview' + count).css('background-image', ' url(' + val.url + ')');
                                            count++;
                                        });
                                    });
                                    $('#update_image_btn' + product_id).prop('disabled', false);
                                    $('#update_image_btn' + product_id).html('Update image');
                                }
                            });
                        } else {
                            toastr.error((data['message']));
                            $('#update_image_btn' + product_id).prop('disabled', false);
                            $('#update_image_btn' + product_id).html('Update image');
                        }
                    }
                });
            }

        });
        //===============edit on image change====================
        $("#e_image1").change(function() {
            let e_image = "1";
            e_readURL(this, e_image);
        });
        $("#e_image2").change(function() {
            let e_image = "2";
            e_readURL(this, e_image);
        });
        $("#e_image3").change(function() {
            let e_image = "3";
            e_readURL(this, e_image);
        });
        //=====================edit image Preview ==================================
        function e_readURL(input, img) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    if (img == "1") {
                        $('#e_imagePreview1').css('background-image', 'url(' + e.target.result + ')');
                        $('#e_imagePreview1').hide();
                        $('#e_imagePreview1').fadeIn(650);
                    } else if (img == "2") {
                        $('#e_imagePreview2').css('background-image', 'url(' + e.target.result + ')');
                        $('#e_imagePreview2').hide();
                        $('#e_imagePreview2').fadeIn(650);
                    } else {
                        $('#e_imagePreview3').css('background-image', 'url(' + e.target.result + ')');
                        $('#e_imagePreview3').hide();
                        $('#e_imagePreview3').fadeIn(650);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        //===================search brand===========================
        $('#e_brand_search').keyup(function() {
            $('#e_selected_brand_id').val('');
            let search_brand_text = $(this).val();
            let num_of_letters = $("#e_brand_search").val().length;
            if (num_of_letters >= 2) {
                $('#e_brand_search_loader').addClass("loading");
                $.ajax({
                    url: "products/fetch_brand",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        search_brand_text: search_brand_text
                    },
                    dataType: 'json',
                    success: function(fetched_brand) {
                        if (fetched_brand != '') {
                            $('#e_show_brand_list').empty();
                            let brand_list = '';
                            $.each(fetched_brand, function(index, val) {
                                let id = val.id;
                                let name = val.name;
                                brand_list += '<a href="' + id + '" class="list-group-item list-group-item-action">' + name + '</a>';
                            });
                            $('#e_show_brand_list').show();
                            $('#e_show_brand_list').append(brand_list);
                            $('#e_brand_search_loader').removeClass("loading");
                        } else {
                            $('#e_show_brand_list').empty();
                            $('#e_show_brand_list').append('<a href="#" class="list-group-item list-group-item-action disabled">No data found</a>');
                            $('#e_brand_search_loader').removeClass("loading");
                        }

                    }
                });
            } else {
                $('#e_show_brand_list').empty();
            }
        })
        $('#e_show_brand_list').on('click', 'a', function(e) {
            e.preventDefault();
            let list_text = $(this).text();
            let selected_brand = $(this).attr('href');
            $('#e_selected_brand_id').val(selected_brand);
            $('#e_brand_search').val(list_text);
            $('#e_show_brand_list').hide();
        });
        //fetch_e_sub_category
        $("#e_category").change(function() {
            let e_category_id = $('#e_category').val();
            $.ajax({
                url: "products/fetch_sub_category",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    category_id: e_category_id
                },
                dataType: 'json',
                success: function(fetched_sub_category) {
                    $('#e_sub_category').empty();
                    let sub_category_list = '<option value="">Select sub category</option>';
                    $.each(fetched_sub_category, function(index, val) {
                        let id = val.id;
                        let name = val.name;
                        sub_category_list += '<option value="' + id + '">' + name + '</option>';
                    });
                    $('#e_sub_category').append(sub_category_list);
                }
            });
        });

        //=====================edit Product===================
        $('#edit_save_changes_btn' + product_id).click(function() {

            let e_product_name = document.getElementById("e_product_name").value;
            let e_category = document.getElementById("e_category").value;
            let e_category_name = $("#e_category option:selected").html();
            let e_sub_category = document.getElementById("e_sub_category").value;
            let e_sub_category_name = $("#e_sub_category option:selected").html();
            let e_brand_id = document.getElementById("e_selected_brand_id").value;
            let e_brand_name = document.getElementById("e_brand_search").value;
            let e_product_tags = document.getElementById("e_product_tag").value;
            let e_mrp = document.getElementById("e_mrp").value;
            let e_discount_percentage = document.getElementById("e_discount_percentage").value;
            let e_selling_price = document.getElementById("e_selling_price").value;
            let e_minimum_order = document.getElementById("e_minimum_order").value;
            let e_maximum_order = document.getElementById("e_maximum_order").value;
            let e_unit_type = document.getElementById("e_unit_type").value;
            let e_quantity = document.getElementById("e_quantity").value;
            let e_product_description = document.getElementById("e_product_description").value;
            var e_form_data = new FormData();
            e_form_data.append('product_id', product_id);
            if ($('#stock_change' + product_id).is(":checked")) {
                e_form_data.append('is_in_stock', 1);
            } else {
                e_form_data.append('is_in_stock', 0);
            }
            e_form_data.append('product_name', e_product_name);
            e_form_data.append('category', e_category);
            e_form_data.append('sub_category', e_sub_category);
            e_form_data.append('brand_id', e_brand_id);
            e_form_data.append('mrp', e_mrp);
            e_form_data.append('discount_percentage', e_discount_percentage);
            e_form_data.append('selling_price', e_selling_price);
            e_form_data.append('minimum_order', e_minimum_order);
            e_form_data.append('maximum_order', e_maximum_order);
            e_form_data.append('product_description', e_product_description);
            e_form_data.append('unit_type', e_unit_type);
            e_form_data.append('quantity', e_quantity);
            e_form_data.append('product_tags', e_product_tags);
            e_form_data.append('category_name', e_category_name);
            e_form_data.append('sub_category_name', e_sub_category_name);
            e_form_data.append('old_tag_array', old_tag_array);
            e_form_data.append('brand_name', e_brand_name);
            if (e_product_name == '' || e_category == '' || e_sub_category == '' || e_mrp == '' || e_discount_percentage == '' || e_selling_price == '' || e_maximum_order == '' || e_minimum_order == '' || e_product_description == '' || e_unit_type == '' || e_quantity == '' || e_product_tags == '') {
                toastr.error(" * Marked Items Are mandatory To fill");
            } else if (e_product_description.length > 250) {
                toastr.error("Product description should not exceed 250 words.");
            } else if (e_brand_id == '') {
                toastr.error(" Please select a brand name");
            } else if (parseInt(e_minimum_order) > parseInt(e_maximum_order)) {
                toastr.error(" Minimum order quantity should be less than Maximum order quantity.");
            } else if (parseInt(e_minimum_order) == 0) {
                toastr.error(" The minimum order quantity should not be 0.");
            } else if (parseInt(e_maximum_order == 0)) {
                toastr.error(" The Maximum order quantity should not be 0.");
            } else if (e_quantity == 0) {
                toastr.error(" Unit quantity should not be 0");
            } else if (e_mrp < 0 || e_discount_percentage < 0 || e_selling_price < 0 || e_minimum_order < 0 || e_maximum_order < 0 || e_quantity < 0) {
                toastr.error("All value have to be positive(+)");
            } else {
                $('#edit_modal_cancel' + product_id).click();
                $('#btn_edit_product' + product_id).prop('disabled', true);
                $('#btn_edit_product' + product_id).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                $.ajax({
                    url: "products/edit_product_details",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: e_form_data,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data['success'] == 1) {
                            toastr.success((data['message']));
                            fetch_product_list(1);
                            $('#btn_edit_product' + product_id).prop('disabled', false);
                            $('#btn_edit_product' + product_id).html('<i class="pen icon"></i>');
                        } else {
                            toastr.error((data['message']));
                            $('#btn_edit_product' + product_id).prop('disabled', false);
                            $('#btn_edit_product' + product_id).html('<i class="pen icon"></i>');
                        }
                    }
                });
            }

        });
        //====================e_Discount calculation===============================
        document.getElementById("e_mrp").onkeyup = function() {
            e_discount_calculation();
        };
        document.getElementById("e_discount_percentage").onkeyup = function() {
            e_discount_calculation();
        };

        function e_discount_calculation() {

            let e_mrp = $('#e_mrp').val();
            let e_discount_percentage = $('#e_discount_percentage').val();
            let e_discount_amount = (parseFloat(e_mrp) * parseFloat(e_discount_percentage)) / 100;
            let e_final_amount = parseFloat(e_mrp) - parseFloat(e_discount_amount);
            if (!isNaN(e_final_amount)) {
                $('#e_selling_price').val(e_final_amount.toFixed(2));
            } else {
                $('#e_selling_price').val(0);
            }
        }
    }

    //delete_product
    window.delete_product = function(product_id) {
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "products/delete_product",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { product_id: product_id },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                fetch_product_list(1);
                                toastr.success(data['message']);
                            } else {
                                toastr.error(data['message']);
                            }
                        }
                    });
                }
            });
    }
}

function settings() {
    $("#settings_menu").addClass("menu-open");
    fetch_categories();
    fetch_pincode(0);
    fetch_brands(0);
    fetch_unit_type(0);
    fetch_cancellation_reason(0);
    fetch_village(0);
    fetch_offers(0);
    //===================tab-click-button-changes=========================
    $('#category_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn').html(' <a class="nav-link" data-toggle="modal" data-target="#add_category_modal"><small><i class="fas fa-plus"></i></small> Add category</a>');
    });
    $('#sub_category_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn').html('<a class="nav-link" data-toggle="modal" data-target="#add_sub_category_modal"><small><i class="fas fa-plus"></i></small> Add sub category</a>');
        fetch_sub_categories();
    });
    $('#brands_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn').html('<a class="nav-link" data-toggle="modal" data-target="#add_brand_modal"><small><i class="fas fa-plus"></i></small> Add brand</a>');

    });
    $('#unit_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn').html('<a class="nav-link" data-toggle="modal" data-target="#add_unit_type_modal"><small><i class="fas fa-plus"></i></small> Add unit type</a>');

    });
    $('#cancellation_reason_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn').html('<a class="nav-link" data-toggle="modal" data-target="#add_cancellation_reason"><small><i class="fas fa-plus"></i></small> Add new reason</a>');

    });
    $('#extra_settings_tab').click(function() {
        $('#change_eable_btn').empty();
        fetch_featured_images();
        fetch_delivery_charges();
        // $('#change_eable_btn').html('<a class="nav-link" data-toggle="modal" data-target="#add_featured_image_modal"><small><i class="fas fa-plus"></i></small> Add image</a>');

    });
    $('#pincode_and_village_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn_2').html('<a class="nav-link" data-toggle="modal" data-target="#add_pincode_modal"><small><i class="fas fa-plus"></i></small> Add Pincode</a>');
    });
    $('#pincode_tab').click(function() {
        $('#change_eable_btn_2').empty();
        $('#change_eable_btn_2').html('<a class="nav-link" data-toggle="modal" data-target="#add_pincode_modal"><small><i class="fas fa-plus"></i></small> Add Pincode</a>');
    });
    $('#village_tab').click(function() {
        $('#change_eable_btn_2').empty();
        $('#change_eable_btn_2').html('<a class="nav-link" data-toggle="modal" data-target="#add_village_modal"><small><i class="fas fa-plus"></i></small> Add Village</a>');

    });
    //=======================add-category====================
    $('#add_category_form').submit(function(e) {
        let new_category_name = document.getElementById("new_category_name").value;
        let category_image_data = $('#category_image')[0].files[0];
        let category_form_data = new FormData();
        e.preventDefault();
        if (new_category_name == "") {
            toastr.error("Please enter category name");
        } else if (category_image_data == undefined || category_image_data == null) {
            toastr.error("Please select image");
        } else {

            $('#btn_create_category').prop('disabled', true);
            $('#btn_create_category').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            category_form_data.append('new_category_name', new_category_name);
            category_form_data.append('category_image', category_image_data);
            $.ajax({
                url: "settings/add_new_category",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: category_form_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#btn_create_category').prop('disabled', false);
                        $('#btn_create_category').html('Create');
                        fetch_categories();
                        $('#new_category_name').val('');
                        $('#category_imagePreview').css('background-image', ' url(assets/dist/img/product_default_image.png)');
                    } else {
                        $('#btn_create_category').prop('disabled', false);
                        $('#btn_create_category').html('Create');
                        toastr.error(data['message']);
                    }
                }
            });
        }
    });
    //==========add-sub-category==========
    $('#sub_category_form').submit(function(e) {
        let new_sub_category_name = document.getElementById("new_sub_category_name").value;
        let selected_category = document.getElementById("select_category").value;
        let sub_category_image_data = $('#sub_category_image')[0].files[0];
        let sub_category_form_data = new FormData();
        e.preventDefault();
        if (selected_category == "") {
            toastr.error("Please select category ");
        } else if (new_sub_category_name == "") {
            toastr.error("Please enter sub category name");
        } else if (sub_category_image_data == undefined || sub_category_image_data == null) {
            toastr.error("Please select image");
        } else {
            $('#btn_create_sub_category').prop('disabled', true);
            $('#btn_create_sub_category').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            sub_category_form_data.append('new_sub_category_name', new_sub_category_name);
            sub_category_form_data.append('sub_category_image', sub_category_image_data);
            sub_category_form_data.append('selected_category', selected_category);
            $.ajax({
                url: "settings/add_new_sub_category",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: sub_category_form_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#btn_create_sub_category').prop('disabled', false);
                        $('#btn_create_sub_category').html('Create');
                        fetch_sub_categories();
                        $('#new_sub_category_name').val('');
                        $('#selected_category').attr('selected', 'selected');
                        $('#sub_category_imagePreview').css('background-image', ' url(assets/dist/img/product_default_image.png)');
                        $('#sub_category_image').val('');
                    } else {
                        $('#btn_create_sub_category').prop('disabled', false);
                        $('#btn_create_sub_category').html('Create');
                        toastr.error(data['message']);
                    }
                }
            });
        }
    });
    //==========add-brands==========
    $('#brand_form').submit(function(e) {
        let new_brand_name = document.getElementById("new_brand_name").value;
        let brand_image_data = $('#brand_image')[0].files[0];
        let brand_form_data = new FormData();
        e.preventDefault();
        if (new_brand_name == "") {
            toastr.error("Please enter Brand name");
        } else if (brand_image_data == undefined || brand_image_data == null) {
            toastr.error("Please select image");
        } else {
            $('#btn_create_brand').prop('disabled', true);
            $('#btn_create_brand').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            brand_form_data.append('new_brand_name', new_brand_name);
            brand_form_data.append('brand_image', brand_image_data);
            $.ajax({
                url: "settings/add_new_brand",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: brand_form_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#btn_create_brand').prop('disabled', false);
                        $('#btn_create_brand').html('Create');
                        fetch_brands(1);
                        $('#new_brand_name').val('');
                        $('#brand_imagePreview').css('background-image', ' url(assets/dist/img/product_default_image.png)');
                        $('#brand_image').val('');
                    } else {
                        $('#btn_create_brand').prop('disabled', false);
                        $('#btn_create_brand').html('Create');
                        toastr.error(data['message']);
                    }
                }
            });
        }
    });
    //==========add-unit_type==========
    $('#unit_type_form').submit(function(e) {
        e.preventDefault();
        let new_unit_type_name = document.getElementById("new_unit_type_name").value;
        if (new_unit_type_name == "") {
            toastr.error("Please enter unit type");
        } else {
            $('#btn_create_unit').prop('disabled', true);
            $('#btn_create_unit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: "settings/add_new_unit_type",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    new_unit_type_name: new_unit_type_name
                },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#btn_create_unit').prop('disabled', false);
                        $('#btn_create_unit').html('Create');
                        fetch_unit_type(1);
                        $('#new_unit_type_name').val('');
                    } else {
                        $('#btn_create_unit').prop('disabled', false);
                        $('#btn_create_unit').html('Create');
                        toastr.error(data['message']);
                    }
                }
            });
        }
    });
    //==========add-cancellation_reason==========
    $('#cancellation_reason_form').submit(function(e) {
        e.preventDefault();
        let new_cancellation_reason = document.getElementById("new_cancellation_reason").value;
        if (new_cancellation_reason == "") {
            toastr.error("Please enter a reason");
        } else {
            $('#btn_create_cancel_reason').prop('disabled', true);
            $('#btn_create_cancel_reason').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: "settings/add_new_cancellation_reason",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    new_cancellation_reason: new_cancellation_reason
                },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#btn_create_cancel_reason').prop('disabled', false);
                        $('#btn_create_cancel_reason').html('Create');
                        fetch_cancellation_reason(1);
                        $('#new_cancellation_reason').val('');
                    } else {
                        $('#btn_create_cancel_reason').prop('disabled', false);
                        $('#btn_create_cancel_reason').html('Create');
                        toastr.error(data['message']);
                    }
                }
            });
        }
    });
    //==========add-pincode==========
    $('#pincode_form').submit(function(e) {
        e.preventDefault();
        let new_pincode = document.getElementById("new_pincode").value;
        if (new_pincode == "") {
            toastr.error("Please enter pincode");
        } else {
            $('#btn_create_pincode').prop('disabled', true);
            $('#btn_create_pincode').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: "settings/add_new_pincode",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    new_pincode: new_pincode
                },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#btn_create_pincode').prop('disabled', false);
                        $('#btn_create_pincode').html('Create');
                        fetch_pincode(1);
                        $('#new_pincode').val('');
                    } else {
                        $('#btn_create_pincode').prop('disabled', false);
                        $('#btn_create_pincode').html('Create');
                        toastr.error(data['message']);
                    }
                }
            });
        }
    });
    //==========add-village==========
    $('#village_form').submit(function(e) {
        e.preventDefault();
        let new_village_name = document.getElementById("new_village_name").value;
        let pincode = document.getElementById("selected_pincode").value;
        if (pincode == "") {
            toastr.error("Please select pincode");
        } else if (new_village_name == "") {
            toastr.error("Please enter village name");
        } else {
            $('#btn_create_village').prop('disabled', true);
            $('#btn_create_village').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $.ajax({
                url: "settings/add_new_village",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    pincode: pincode,
                    new_village_name: new_village_name
                },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#btn_create_village').prop('disabled', false);
                        $('#btn_create_village').html('Create');
                        fetch_village(1);
                        $('#new_village_name').val('');
                        $('#selected_pincode_option').attr('selected', 'selected');

                    } else {
                        $('#btn_create_village').prop('disabled', false);
                        $('#btn_create_village').html('Create');
                        toastr.error(data['message']);
                    }
                }
            });
        }
    });

    //add feature image
    $('#featured_image_action').change(function() {
        var selected_action = $('#featured_image_action').val();
        if (selected_action != '') {
            if (selected_action != 'keyword') {
                $('#action_keyword_placeholder').empty();
                $.ajax({
                    url: "settings/fetch_action_data",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { selected_action: selected_action },
                    dataType: 'json',
                    success: function(data) {
                        let action_str = '';
                        action_str += '<label for="featured_image_action_keyword">Action Keyword<span class="text-danger">*</span></label>';
                        if (selected_action == 'product') {
                            action_str += '<input type="hidden" id="featured_image_action_keyword" name="selected_product_id" value="">';
                            action_str += '<div id="brand_search_loader" class="ui left icon  input purple double fluid">';
                            action_str += '<input type="text" class="form-control" id="product_search" placeholder="Search product" autocomplete="off">';
                            action_str += '<i class="search icon"></i>';
                            action_str += '</div>';
                            // action_str += '<input type="text" class="form-control" id="product_search" placeholder="Search product" autocomplete="off">';
                            action_str += '<div class="list-group" id="show_product_list">';

                            action_str += '</div>';
                        } else {
                            action_str += '<select class="form-control " id="featured_image_action_keyword">';
                            action_str += '<option value=""  id="selected_action_keyword_id">Select action</option>';
                            $.each(data, function(index, val) {
                                action_str += '<option value="' + val.name + '">' + val.name + '</option>';
                            });
                            action_str += '</select>';
                        }
                        $('#action_keyword_placeholder').append(action_str);
                        $('#product_search').keyup(function() {
                            $('#featured_image_action_keyword').val('');
                            let search_product_text = $(this).val();
                            let num_of_letters = $("#product_search").val().length;
                            if (num_of_letters >= 2) {
                                $('#brand_search_loader').addClass("loading");
                                $.ajax({
                                    url: "settings/fetch_product",
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        search_product_text: search_product_text
                                    },
                                    dataType: 'json',
                                    success: function(data) {
                                        $('#show_product_list').empty();
                                        let product_list = '';
                                        if (data == '') {
                                            product_list += '<a href="" class="list-group-item list-group-item-action" style="pointer-events: none">No data found</a>';
                                        } else {
                                            $.each(data, function(index, val) {
                                                product_list += '<a href="' + val.id + '" class="list-group-item list-group-item-action">' + val.name + '</a>';
                                            });
                                        }

                                        $('#show_product_list').show();
                                        $('#show_product_list').append(product_list);
                                        $('#brand_search_loader').removeClass("loading");

                                    }
                                });
                            } else {
                                $('#show_product_list').empty();
                            }
                        });
                        $('#show_product_list').on('click', 'a', function(e) {
                            e.preventDefault();
                            let list_text = $(this).text();
                            let selected_product = $(this).attr('href');
                            $('#featured_image_action_keyword').val(selected_product);
                            $('#product_search').val(list_text);
                            $('#show_product_list').hide();
                        });
                    }
                });
            } else {
                $('#action_keyword_placeholder').empty();
                let action_str = '';
                action_str += '<label for="featured_image_action_keyword">Action Keyword<span class="text-danger">*</span></label>';
                action_str += '<input class="form-control" id="featured_image_action_keyword" placeholder="Enter keyword" autocomplete="off">';
                $('#action_keyword_placeholder').append(action_str);
            }
        } else {
            $('#action_keyword_placeholder').empty();
        }
    });


    $('#featured_image_form').submit(function(e) {
        var selected_action = $('#featured_image_action').val();
        var featured_image_action_keyword = $('#featured_image_action_keyword').val();
        let featured_image_data = $('#featured_image')[0].files[0];
        let featured_image_form_data = new FormData();
        e.preventDefault();
        if (selected_action == "" || featured_image_data == undefined || featured_image_data == null) {
            toastr.error("All fields are have to be fill");
        } else {
            $('#add_feature_images_btn').prop('disabled', true);
            $('#featured_image_create_btn').prop('disabled', true);
            featured_image_form_data.append('selected_action', selected_action);
            featured_image_form_data.append('featured_image_action_keyword', featured_image_action_keyword);
            featured_image_form_data.append('featured_image', featured_image_data);
            $.ajax({
                url: "settings/add_featured_image",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: featured_image_form_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        $('#btn_modal_close').click();
                        if (selected_action == 'keyword') {
                            $('#featured_image_action_keyword').val('');
                            $('#selected_action_id').attr('selected', 'selected');
                            $('#featured_imagePreview').css('background-image', ' url(assets/dist/img/product_default_image.png)');
                            $('#featured_image').val('');
                            $('#featured_image_create_btn').prop('disabled', false);
                            $('#add_feature_images_btn').prop('disabled', false);
                            fetch_featured_images();
                            toastr.success(data['message']);
                        } else {
                            $('#selected_action_id').attr('selected', 'selected');
                            $('#selected_action_keyword_id').attr('selected', 'selected');
                            $('#featured_imagePreview').css('background-image', ' url(assets/dist/img/product_default_image.png)');
                            $('#featured_image').val('');
                            $('#featured_image_create_btn').prop('disabled', false);
                            $('#add_feature_images_btn').prop('disabled', false);
                            fetch_featured_images();
                            toastr.success(data['message']);
                        }
                    } else {
                        $('#featured_image_create_btn').prop('disabled', false);
                        $('#add_feature_images_btn').prop('disabled', false);
                        toastr.error(data['message']);
                    }

                }
            });
        }
    });
    //fetch feature image
    function fetch_featured_images() {
        $.ajax({
            url: "settings/fetch_featured_images",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#featured_image_view_placeholder').empty();
                let fetch_featured_images_view_str = '';
                $.each(data, function(index, val) {
                    fetch_featured_images_view_str += '<div class="col-md-4">';
                    fetch_featured_images_view_str += '<div class="card">';
                    fetch_featured_images_view_str += '<img src="' + val.url + '" class="card-img-top" alt="...">';
                    fetch_featured_images_view_str += '<div class="card-body">';

                    fetch_featured_images_view_str += '<p><h4>ACTION: <span class="text-dark">' + val.action.toUpperCase() + '</span></h4>';
                    fetch_featured_images_view_str += '<span class="float-right"><button class="btn btn-outline-danger" onclick="delete_featured_image(' + val.id + ',' + val.upload_id + ')"><i class="fas fa-trash"></i></button></span></p>';
                    fetch_featured_images_view_str += '</div>';
                    fetch_featured_images_view_str += '</div>';
                    fetch_featured_images_view_str += '</div>';
                });
                $('#featured_image_view_placeholder').append(fetch_featured_images_view_str);

            }
        });
    }
    window.delete_featured_image = function(id, upload_id) {
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "settings/delete_featured_image",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { id: id, upload_id: upload_id },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                fetch_featured_images();
                                toastr.success(data['message']);
                            } else {
                                toastr.error(data['message']);
                            }
                        }
                    });
                }
            });
    }

    //fetch delivery charges

    function fetch_delivery_charges() {
        $.ajax({
            url: "settings/fetch_delivery_charges",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#delivery_charge_table_tbody').empty();
                let delivery_charge_str = '';
                $.each(data, function(index, val) {
                    delivery_charge_str += '<tr>';
                    delivery_charge_str += '<td>' + val.up_to + '</td>';
                    delivery_charge_str += '<td>' + val.charge + '</td>';
                    delivery_charge_str += '<td id="delivery_charge_change_btn"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modify_delivery_charge_modal' + val.id + '" id="modify_delivery_charge_btn' + val.id + '" onclick="modify_delivery_charges(' + val.id + ',' + val.charge + ',' + val.up_to + ')">Modify</button></td>';
                    delivery_charge_str += '</tr>';
                });
                $('#delivery_charge_table_tbody').append(delivery_charge_str);

            }
        });
    }
    window.modify_delivery_charges = function(id, charge, up_to) {
        $('#delivery_charge_modification_modal').empty();
        let create_deliver_charge_modify_modal = '';
        create_deliver_charge_modify_modal += '<div class="modal fade" id="modify_delivery_charge_modal1" tabindex="-1">';
        create_deliver_charge_modify_modal += '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">';
        create_deliver_charge_modify_modal += '<div class="modal-content">';
        create_deliver_charge_modify_modal += '<div class="modal-header">';
        create_deliver_charge_modify_modal += '<h5 class="modal-title" id="modal_title">Modify delivery charge</h5>';
        create_deliver_charge_modify_modal += '<button type="button" id="btn_modal_close" class="close" data-dismiss="modal" aria-label="Close">';
        create_deliver_charge_modify_modal += '<span aria-hidden="true">&times;</span>';
        create_deliver_charge_modify_modal += '</button>';
        create_deliver_charge_modify_modal += ' </div>';
        create_deliver_charge_modify_modal += '<div class="modal-body">';
        create_deliver_charge_modify_modal += '<form class="" id="delivery_charge_form">';
        create_deliver_charge_modify_modal += '<div class="form-row">';
        create_deliver_charge_modify_modal += '<div class="form-group col-md-6">';
        create_deliver_charge_modify_modal += '<label for="amount_up_to' + id + '">Up to</label>';
        create_deliver_charge_modify_modal += '<input class="form-control" id="amount_up_to' + id + '" placeholder="Enter up to amount" value="' + up_to + '" autocomplete="off">';
        create_deliver_charge_modify_modal += '</div>';
        create_deliver_charge_modify_modal += '<div class="form-group col-md-6">';
        create_deliver_charge_modify_modal += '<label for="delivery_charge' + id + '">Charge</label>';
        create_deliver_charge_modify_modal += '<input class="form-control" id="delivery_charge' + id + '" placeholder="Enter delivery charge" value="' + charge + '" autocomplete="off">';
        create_deliver_charge_modify_modal += '</div>';
        create_deliver_charge_modify_modal += '</div>';
        create_deliver_charge_modify_modal += '</form>';
        create_deliver_charge_modify_modal += '</div>';
        create_deliver_charge_modify_modal += '<div class="modal-footer">';
        create_deliver_charge_modify_modal += '<div class="ui buttons">';
        create_deliver_charge_modify_modal += '<button class="ui button" data-dismiss="modal" id="modal_cancel' + id + '">Cancel</button>';
        create_deliver_charge_modify_modal += '<div class="or"></div>';
        create_deliver_charge_modify_modal += '<button class="ui positive button" id="save_changes' + id + '">Save changes</button>';
        create_deliver_charge_modify_modal += '</div>';
        create_deliver_charge_modify_modal += '</div>';
        create_deliver_charge_modify_modal += '</div>';
        create_deliver_charge_modify_modal += '</div>';
        create_deliver_charge_modify_modal += '</div>';
        $('#delivery_charge_modification_modal').append(create_deliver_charge_modify_modal);
        $('#save_changes' + id).click(function(e) {
            e.preventDefault();
            let amount_up_to = $('#amount_up_to' + id).val();
            let delivery_charge = $('#delivery_charge' + id).val();
            if (charge == delivery_charge && up_to == amount_up_to) {
                toastr.error("Please change something");
            } else if (amount_up_to == '' || delivery_charge == '') {
                toastr.error("Fill all Fields");
            } else {
                $('#modal_cancel' + id).click();
                $('#modify_delivery_charge_btn' + id).prop('disabled', true);
                $.ajax({
                    url: "settings/modify_delivery_charge",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { id: id, amount_up_to: amount_up_to, delivery_charge: delivery_charge },
                    dataType: 'json',
                    success: function(data) {
                        if (data['success'] == 1) {
                            fetch_delivery_charges();
                            toastr.success(data['message']);
                        } else {
                            toastr.error(data['message']);
                        }
                    }
                });
            }
        });
    }

    //fetch Minimum order
    fetch_minimum_order();

    function fetch_minimum_order() {
        $.ajax({
            url: "settings/fetch_minimum_order",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                console.log(data['fetched_minimum_order']);
                $('#minimum_order_table_tbody').empty();
                let minimum_order_str = '';
                // $.each(data['fetched_minimum_order'], function(index, val) {
                minimum_order_str += '<tr>';
                minimum_order_str += '<td>' + data['fetched_minimum_order'].minimum_order_amount + '</td>';
                minimum_order_str += '<td id="minimum_order_btn"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modify_minimum_order_modal' + data['fetched_minimum_order'].id + '" id="modify_minimum_order_btn' + data['fetched_minimum_order'].id + '" onclick="modify_minimum_order(' + data['fetched_minimum_order'].id + ',' + data['fetched_minimum_order'].minimum_order_amount + ')">Modify</button></td>';
                minimum_order_str += '</tr>';
                // });
                $('#minimum_order_table_tbody').append(minimum_order_str);

            }
        });
    }

    window.modify_minimum_order = function(id, minimum_order) {
        $('#minimum_order_modification_modal').empty();
        let create_minimum_order_modify_modal = '';
        create_minimum_order_modify_modal += '<div class="modal fade" id="modify_minimum_order_modal' + id + '" tabindex="-1">';
        create_minimum_order_modify_modal += '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">';
        create_minimum_order_modify_modal += '<div class="modal-content">';
        create_minimum_order_modify_modal += '<div class="modal-header">';
        create_minimum_order_modify_modal += '<h5 class="modal-title" id="modal_title">Modify minimum order price</h5>';
        create_minimum_order_modify_modal += '<button type="button" id="btn_modal_close" class="close" data-dismiss="modal" aria-label="Close">';
        create_minimum_order_modify_modal += '<span aria-hidden="true">&times;</span>';
        create_minimum_order_modify_modal += '</button>';
        create_minimum_order_modify_modal += ' </div>';
        create_minimum_order_modify_modal += '<div class="modal-body">';
        create_minimum_order_modify_modal += '<form class="" id="minimum_order_form">';
        create_minimum_order_modify_modal += '<div class="form-row">';
        create_minimum_order_modify_modal += '<div class="form-group col-md-12">';
        create_minimum_order_modify_modal += '<label for="minimum_order' + id + '">Charge</label>';
        create_minimum_order_modify_modal += '<input class="form-control" id="minimum_order' + id + '" placeholder="Enter minimum order" value="' + minimum_order + '" autocomplete="off">';
        create_minimum_order_modify_modal += '</div>';
        create_minimum_order_modify_modal += '</div>';
        create_minimum_order_modify_modal += '</form>';
        create_minimum_order_modify_modal += '</div>';
        create_minimum_order_modify_modal += '<div class="modal-footer">';
        create_minimum_order_modify_modal += '<div class="ui buttons">';
        create_minimum_order_modify_modal += '<button class="ui button" data-dismiss="modal" id="minimum_order_modal_cancel' + id + '">Cancel</button>';
        create_minimum_order_modify_modal += '<div class="or"></div>';
        create_minimum_order_modify_modal += '<button class="ui positive button" id="minimum_order_save_changes' + id + '">Save changes</button>';
        create_minimum_order_modify_modal += '</div>';
        create_minimum_order_modify_modal += '</div>';
        create_minimum_order_modify_modal += '</div>';
        create_minimum_order_modify_modal += '</div>';
        create_minimum_order_modify_modal += '</div>';
        $('#minimum_order_modification_modal').append(create_minimum_order_modify_modal);
        $('#minimum_order_save_changes' + id).click(function(e) {
            e.preventDefault();
            let minimum_order = $('#minimum_order' + id).val();
            if (minimum_order == '') {
                toastr.error("Fill all Fields");
            } else {
                $('#minimum_order_modal_cancel' + id).click();
                $('#minimum_order_save_changes' + id).prop('disabled', true);
                $.ajax({
                    url: "settings/modify_minimum_order",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { id: id, minimum_order: minimum_order },
                    dataType: 'json',
                    success: function(data) {
                        if (data['success'] == 1) {
                            fetch_minimum_order();
                            toastr.success(data['message']);
                        } else {
                            toastr.error(data['message']);
                        }
                    }
                });
            }
        });
    }

    //add_offers
    $('#add_offer_form').submit(function(e) {
        var offer_name = $('#offer_name').val();
        var offer_description = $('#offer_description').val();
        let order_price = $('#order_price').val();
        let discount_amount = $('#discount_amount').val();
        e.preventDefault();
        if (offer_name == "" || offer_description == '' || order_price == "" || discount_amount == "") {
            toastr.error("All fields are have to be fill");
        } else if (parseFloat(discount_amount) >= parseFloat(order_price)) {
            toastr.error("Discount amount should be less than order price");
        } else {
            $.ajax({
                url: "settings/add_offer",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { offer_name: offer_name, offer_description: offer_description, order_price: order_price, discount_amount: discount_amount },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        $('#offer_name').val('');
                        $('#offer_description').val('');
                        $('#order_price').val('');
                        $('#discount_amount').val('');
                        $('#btn_offer_modal_close').click();
                        fetch_offers(1);
                        toastr.success(data['message']);
                    } else {
                        toastr.error(data['message']);
                    }
                }
            });
        }
    });

    function fetch_offers(refresh) {
        if (refresh == 1) {
            offers_table.destroy();
        }
        $.ajax({
            url: "settings/fetch_offers",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#offers_table_tbody').empty();
                let offers_str = '';
                $.each(data, function(index, val) {
                    offers_str += '<tr>';
                    offers_str += '<td>' + val.name + '</td>';
                    offers_str += '<td>' + val.desc + '</td>';
                    offers_str += '<td>' + val.order_price + '</td>';
                    offers_str += '<td>' + val.discount_amount + '</td>';
                    offers_str += '<td ><button id="offers_delete_btn' + val.id + '" class="btn btn-outline-danger btn-sm" onclick="delete_offer(' + val.id + ')"><i class="fas fa-trash"></i></button></td>';
                    offers_str += '</tr>';
                });
                $('#offers_table_tbody').append(offers_str);
                offers_table = $('#offers_table').DataTable({
                    "dom": "ftrip",
                    'columnDefs': [{

                        'targets': [4],

                        'orderable': false,

                    }]
                });
            }
        });
    }
    window.delete_offer = function(id) {
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#offers_delete_btn' + id).prop('disabled', true);
                    $.ajax({
                        url: "settings/delete_offer",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { id: id },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                fetch_offers(1);
                                toastr.success(data['message']);
                            } else {
                                toastr.error(data['message']);
                            }
                        }
                    });
                }
            });
    }



    //========fetch-category=====
    function fetch_categories() {
        var category_list_view = "";
        var category_list = "";
        $.ajax({
            url: "settings/fetch_all_categories",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                category_list += '<option value="" id="selected_category">Select Category</option>';
                $.each(data, function(index, val) {
                    let status = '';
                    let users = "";
                    $('#all_category_view').empty();
                    $('#select_category').empty();
                    if (val.is_active == "1") {
                        status = '<span class="text-success">Active</span>';
                    } else {
                        status = '<span class="text-danger">Deactive</span>';
                    }
                    category_list_view += '<div class="col-md-2">';
                    category_list_view += '<div class="card border-0 elevation-3">';
                    category_list_view += '<div class="card-body text-center">';
                    category_list_view += '<img src="' + val.url + '" class=" img-circle  " height="100px" width="100px">';
                    category_list_view += '<h5 class="card-text mt-2" id="category_name"><strong>' + val.name + '</strong></h5>';
                    // category_list_view += '<p class="card-text" id="status">' + status + '</p>';
                    category_list_view += '<p class="card-text"><button class="btn btn-outline-danger btn-sm rounded-circle  ml-1" onclick="delete_category(' + val.category_id + ');"><i class="fas fa-trash"></i></button></p>';
                    category_list_view += '</div>';
                    category_list_view += '</div>';
                    category_list_view += '</div>';
                    category_list += '<option value="' + val.category_id + '">' + val.name + '</option>';
                });
                $('#all_category_view').append(category_list_view);
                $('#select_category').append(category_list);

            }
        });

    }
    //===========fetch_sub_category=============
    function fetch_sub_categories() {
        var sub_category_list_view = "";
        $.ajax({
            url: "settings/fetch_all_sub_categories",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, val) {
                    let status = '';
                    $('#all_sub_category_list_view').empty();
                    if (val.sub_category_status == "1") {
                        status = '<span class="text-success">Active</span>';
                    } else {
                        status = '<span class="text-danger">Deactive</span>';
                    }
                    sub_category_list_view += '<div class="col-md-2 p-3">';
                    sub_category_list_view += '<div class="ui raised segment">';
                    sub_category_list_view += '<a class="ui teal ribbon label">' + val.category_name + '</a>';
                    // sub_category_list_view += '<div class="card border-0 elevation-3">';
                    sub_category_list_view += '<div class="card-body text-center">';
                    sub_category_list_view += '<img src="' + val.url + '" class=" img-circle  " height="100px" width="100px">';
                    sub_category_list_view += '<h5 class="card-text mt-2" id="category_name"><strong>' + val.sub_category_name + '</strong></h5>';
                    // sub_category_list_view += '<p class="card-text" id="status">' + status + '</p>';
                    sub_category_list_view += '<p class="card-text"><button class="btn btn-outline-danger btn-sm rounded-circle  ml-1" onclick="delete_sub_category(' + val.sub_category_id + ');"><i class="fas fa-trash"></i></button></p>';
                    sub_category_list_view += '</div>';
                    sub_category_list_view += '</div>';
                    sub_category_list_view += '</div>';
                });
                $('#all_sub_category_list_view').append(sub_category_list_view);
            }
        });

    }
    //========fetch-brands=====
    function fetch_brands(refresh) {
        if (refresh == 1) {
            brands_dataTable.destroy();
        }
        var brand_list = "";
        let count = 1;
        $.ajax({
            url: "settings/fetch_all_brands",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, val) {
                    let status = '';
                    let users = "";
                    $('#all_brand_table_tbody').empty();
                    if (val.is_active == "1") {
                        status = '<span class="text-success">Active</span>';
                    } else {
                        status = '<span class="text-danger">Deactive</span>';
                    }
                    // if (val.added_by == "1") {
                    //     users = '<span class="text-green">ADMIN</span>';
                    // } else {
                    //     users = '<span class="text-danger">OTHERS</span>';
                    // }
                    let date = new Date((val.added_on) * 1000);
                    brand_list += '<tr>';
                    brand_list += '<th scope="row">' + count + '</th>';
                    brand_list += '<td><img src="' + val.url + '" class="rounded-circle" height="60px" width="60px"></td>';
                    brand_list += '<td>' + val.name + '</td>';
                    // brand_list += '<td>' + users + '</td>';
                    brand_list += '<td>' + date.toLocaleString(); + '</td>';
                    // brand_list += '<td>' + status + '</td>';
                    brand_list += '<td><button class="btn btn-outline-danger rounded-circle  ml-2" onclick="delete_brand(' + val.id + ');"><i class="fas fa-trash"></i></button></td>';
                    brand_list += '</tr>';
                    count++;
                });
                $('#all_brand_table_tbody').append(brand_list);
                brands_dataTable = $('#all_brand_table').DataTable({
                    "dom": "ftip",
                    'columnDefs': [{

                        'targets': [4],

                        'orderable': false,

                    }]
                });

            }
        });

    }
    //========fetch-unit_type=====
    function fetch_unit_type(refresh) {
        if (refresh == 1) {
            unit_type_dataTable.destroy();
        }
        var unit_type_list = "";
        let count = 1;
        $.ajax({
            url: "settings/fetch_all_unit_type",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, val) {
                    let status = '';
                    let users = "";
                    $('#all_unit_type_table_tbody').empty();
                    if (val.is_deleted == "0") {
                        status = '<span class="text-success">Active</span>';
                    } else {
                        status = '<span class="text-danger">Deactive</span>';
                    }
                    unit_type_list += '<tr class="">';
                    unit_type_list += '<th scope="row">' + count + '</th>';
                    unit_type_list += '<td>' + val.name + '</td>';
                    // unit_type_list += '<td>' + status + '</td>';
                    unit_type_list += '<td><button class="btn btn-outline-danger rounded-circle  ml-2" onclick="delete_unit_type(' + val.id + ');"><i class="fas fa-trash"></i></button></td>';
                    unit_type_list += '</tr>';
                    count++;
                });
                $('#all_unit_type_table_tbody').append(unit_type_list);
                unit_type_dataTable = $('#all_unit_type_table').DataTable({
                    "dom": "ftip",
                    'columnDefs': [{

                        'targets': [2],

                        'orderable': false,

                    }]
                });

            }
        });

    }
    //========fetch-cancellation_reason=====
    function fetch_cancellation_reason(refresh) {
        if (refresh == 1) {
            cancellation_reason_dataTable.destroy();
        }
        var cancellation_reason_list = "";
        let count = 1;
        $.ajax({
            url: "settings/fetch_all_cancellation_reason",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, val) {
                    $('#all_cancellation_reason_table_tbody').empty();
                    cancellation_reason_list += '<tr class="">';
                    cancellation_reason_list += '<th scope="row">' + count + '</th>';
                    cancellation_reason_list += '<td>' + val.id + '</td>';
                    cancellation_reason_list += '<td>' + val.reason + '</td>';
                    cancellation_reason_list += '</tr>';
                    count++;
                });
                $('#all_cancellation_reason_table_tbody').append(cancellation_reason_list);
                cancellation_reason_dataTable = $('#all_cancellation_reason_table').DataTable({
                    "dom": "ftip",
                    'columnDefs': [{

                        'targets': [2],

                        'orderable': false,

                    }]
                });

            }
        });

    }
    //========fetch-pincode=====
    function fetch_pincode(refresh) {
        if (refresh == 1) {
            pincode_dataTable.destroy();
        }
        var pincode_list = "";
        var pincode_list_table = "";
        let count = 1;
        $.ajax({
            url: "settings/fetch_all_pincode",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#selected_pincode').empty();
                $('#all_pincode_table_tbody').empty();
                pincode_list += '<option value="" id="selected_pincode_option">Select Pincode</option>';
                $.each(data, function(index, val) {
                    let status = '';
                    if (val.is_active == "1") {
                        status = '<span class="text-success">Active</span>';
                        chechbox_input = '<input type="checkbox" class="custom-control-input " id="status_pincode_action_change' + val.id + '" checked onclick="status_pincode_change(' + val.id + ')">';
                    } else {
                        status = '<span class="text-danger">Deactive</span>';
                        chechbox_input = '<input type="checkbox" class="custom-control-input " id="status_pincode_action_change' + val.id + '" onclick="status_pincode_change(' + val.id + ')">';
                    }
                    pincode_list_table += '<tr>';
                    pincode_list_table += '<th scope="row">' + count + '</th>';
                    pincode_list_table += '<td>' + val.pincode + '</td>';
                    pincode_list_table += '<td>' + status + '</td>';
                    pincode_list_table += '<td><div class="custom-control custom-switch custom-switch-md mb-2" id="status_pincode_action_switch' + val.id + '">';
                    pincode_list_table += chechbox_input;
                    pincode_list_table += '<label class="custom-control-label" for="status_pincode_action_change' + val.id + '" id="status_pincode_action_change_level' + val.id + '"></label>';
                    pincode_list_table += '</div></td>';
                    pincode_list_table += '</tr>';
                    pincode_list += '<option value="' + val.id + '">' + val.pincode + '</option>';
                    count++;

                });
                $('#all_pincode_table_tbody').append(pincode_list_table);
                $('#selected_pincode').append(pincode_list);
                pincode_dataTable = $('#all_pincode_table').DataTable({
                    "dom": "ftip",
                    'columnDefs': [{

                        'targets': [3],

                        'orderable': false,

                    }]
                });

            }
        });

    }
    //update_pincode_status
    window.status_pincode_change = function(pincode_id) {
            $('#status_pincode_action_change' + pincode_id).prop('disabled', true);
            if ($('#status_pincode_action_change' + pincode_id).is(":checked")) {
                var updated_status = 1;
            } else {
                var updated_status = 0;
            }
            $.ajax({
                url: "settings/update_pincode_status",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { pincode_id: pincode_id, updated_status: updated_status },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#status_pincode_action_change' + pincode_id).prop('disabled', false);
                        fetch_pincode(1);
                        fetch_village(1);
                    } else {
                        $('#status_pincode_action_change' + pincode_id).prop('disabled', false);
                        fetch_pincode(1);
                        fetch_village(1);
                    }
                }
            });
        }
        //========fetch-village=====
    function fetch_village(refresh) {
        if (refresh == 1) {
            village_dataTable.destroy();
        }
        var village_list_table = "";
        let count = 1;
        $.ajax({
            url: "settings/fetch_all_village",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, val) {
                    let status = '';
                    let chechbox_input = '';
                    $('#all_village_table_tbody').empty();
                    if (val.village_is_active == "1") {
                        status = '<span class="text-success">Active</span>';
                        chechbox_input = '<input type="checkbox" class="custom-control-input " id="status_village_action_change' + val.village_id + '" checked onclick="status_change(' + val.village_id + ')">';
                    } else {
                        status = '<span class="text-danger">Deactive</span>';
                        chechbox_input = '<input type="checkbox" class="custom-control-input" id="status_village_action_change' + val.village_id + '" onclick="status_change(' + val.village_id + ')">';
                    }

                    if (val.pincode_status == 0) {
                        chechbox_input = '<input type="checkbox" class="custom-control-input" id="status_village_action_change' + val.village_id + '" onclick="status_change(' + val.village_id + ')" disabled>';
                    }
                    village_list_table += '<tr>';
                    village_list_table += '<th scope="row">' + count + '</th>';
                    village_list_table += '<td>' + val.village_name + '</td>';
                    village_list_table += '<td>' + val.pincode_number + '</td>';
                    village_list_table += '<td>' + status + '</td>';
                    village_list_table += '<td><div class="custom-control custom-switch custom-switch-md mb-2" id="status_action_switch' + val.village_id + '">';

                    village_list_table += chechbox_input;
                    village_list_table += '<label class="custom-control-label" for="status_village_action_change' + val.village_id + '" id="status_village_action_change_level' + val.village_id + '"></label>';
                    village_list_table += '</div></td>';
                    village_list_table += '</tr>';
                    count++;
                });
                $('#all_village_table_tbody').append(village_list_table);
                village_dataTable = $('#all_village_table').DataTable({
                    "dom": "ftip",
                    'columnDefs': [{

                        'targets': [4],

                        'orderable': false,

                    }]
                });

            }
        });

    }
    //update_village_status
    window.status_change = function(village_id) {
            $('#status_village_action_change' + village_id).prop('disabled', true);
            if ($('#status_village_action_change' + village_id).is(":checked")) {
                var updated_status = 1;
            } else {
                var updated_status = 0;
            }
            $.ajax({
                url: "settings/update_village_status",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { village_id: village_id, updated_status: updated_status },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success((data['message']));
                        $('#status_village_action_change' + village_id).prop('disabled', false);
                        fetch_village(1);
                    } else {
                        toastr.error(data['message']);
                        $('#status_village_action_change' + village_id).prop('disabled', false);
                        fetch_village(1);
                    }
                }
            });
        }
        //===============on image change====================
    $("#category_image").change(function() {
        let image = "category";
        readURL(this, image);
    });
    $("#sub_category_image").change(function() {
        let image = "sub_category";
        readURL(this, image);
    });
    $("#featured_image").change(function() {
        let image = "featured_image";
        readURL(this, image);
    });
    $("#brand_image").change(function() {
        let image = "brand_image";
        readURL(this, image);
    });
    //=====================image Preview ==================================
    function readURL(input, img) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                if (img == "category") {
                    $('#category_imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#category_imagePreview').hide();
                    $('#category_imagePreview').fadeIn(650);
                }
                if (img == "sub_category") {
                    $('#sub_category_imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#sub_category_imagePreview').hide();
                    $('#sub_category_imagePreview').fadeIn(650);
                }
                if (img == "featured_image") {
                    $('#featured_imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#featured_imagePreview').hide();
                    $('#featured_imagePreview').fadeIn(650);
                }
                if (img == "brand_image") {
                    $('#brand_imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#brand_imagePreview').hide();
                    $('#brand_imagePreview').fadeIn(650);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    //===================================delete_category================
    window.delete_category = function(id) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    closeModal: false,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "settings/delete_category",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                category_id: id
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data['success'] == 1) {
                                    swal(data['message'], {
                                        icon: "success",
                                        buttons: false,
                                        timer: 1000
                                    });
                                    fetch_categories();
                                } else {
                                    swal(data['message'], {
                                        icon: "error",
                                    });
                                }
                            }
                        });
                    } else {
                        swal("Your data is safe", {
                            buttons: false,
                            timer: 500
                        });
                    }
                });
        }
        //===================================delete_sub_category================
    window.delete_sub_category = function(id) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    closeModal: false,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "settings/delete_sub_category",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                sub_category_id: id
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data['success'] == 1) {
                                    swal(data['message'], {
                                        icon: "success",
                                        buttons: false,
                                        timer: 1000
                                    });
                                    fetch_sub_categories();
                                } else {
                                    swal(data['message'], {
                                        icon: "error",
                                    });
                                }
                            }
                        });
                    } else {
                        swal("Your data is safe", {
                            buttons: false,
                            timer: 500
                        });
                    }
                });
        }
        //===================================delete_brands================
    window.delete_brand = function(id) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    closeModal: false,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "settings/delete_brand",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                brand_id: id
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data['success'] == 1) {
                                    swal(data['message'], {
                                        icon: "success",
                                        buttons: false,
                                        timer: 1000
                                    });
                                    fetch_brands(1);
                                } else {
                                    swal(data['message'], {
                                        icon: "error",
                                    });
                                }
                            }
                        });
                    } else {
                        swal("Your data is safe", {
                            buttons: false,
                            timer: 500
                        });
                    }
                });
        }
        //===================================delete_unit_type================
    window.delete_unit_type = function(id) {
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "settings/delete_unit_type",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            unit_type_id: id
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                swal(data['message'], {
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000
                                });
                                fetch_unit_type(1);
                            } else {
                                swal(data['message'], {
                                    icon: "error",
                                });
                            }
                        }
                    });
                } else {
                    swal("Your data is safe", {
                        buttons: false,
                        timer: 500
                    });
                }
            });
    }
}

function order_details() {
    var delivery_partner = '';
    var orders_status = '';
    $("#order_menu").addClass("menu-open");
    // for calender input
    $('#rangestart').calendar({
        type: 'date',
        endCalendar: $('#rangeend')
    });
    $('#rangeend').calendar({
        type: 'date',
        startCalendar: $('#rangestart')
    });
    $('#date_filter_calender').calendar({
        type: 'date'
    });
    $('.ui.dropdown').dropdown();
    //==========table_menu_filter=============
    $('#all_order').click(function() {
        orders_status = "";
        fetch_order_details_for_table(1, 0, 0);
        remove_all_menu_btn_class();
        $('#all_order').addClass("active");
    });
    $('#active_order').click(function() {
        orders_status = "Active";
        fetch_order_details_for_table(1, 0, 0);
        remove_all_menu_btn_class();
        $('#active_order').addClass("active");
    });
    $('#ready_to_delivery_order').click(function() {
        orders_status = "Ready to deliver";
        fetch_order_details_for_table(1, 0, 0);
        remove_all_menu_btn_class();
        $('#ready_to_delivery_order').addClass("active");
    });
    $('#out_for_delivery_order').click(function() {
        orders_status = "Out for Delivery";
        fetch_order_details_for_table(1, 0, 0);
        remove_all_menu_btn_class();
        $('#out_for_delivery_order').addClass("active");
    });
    $("#delivered_order").click(function() {
        orders_status = "Delivered";
        fetch_order_details_for_table(1, 0, 0);
        remove_all_menu_btn_class();
        $('#delivered_order').addClass("active");
    });
    $("#canceled_order").click(function() {
        orders_status = "Canceled";
        fetch_order_details_for_table(1, 0, 0);
        fetch_order_details_for_table(1, 0, 0);
        remove_all_menu_btn_class();
        $('#canceled_order').addClass("active");
    });
    $('#rejected_order').click(function() {
        orders_status = "Rejected";
        fetch_order_details_for_table(1, 0, 0);
        remove_all_menu_btn_class();
        $('#rejected_order').addClass("active");
    });

    function remove_all_menu_btn_class() {
        $('#all_order').removeClass("active");
        $('#active_order').removeClass("active");
        $('#ready_to_delivery_order').removeClass("active");
        $('#out_for_delivery_order').removeClass("active");
        $('#delivered_order').removeClass("active");
        $('#canceled_order').removeClass("active");
        $('#rejected_order').removeClass("active");
    }
    $('#search_order_filter').keyup(function() {
        if (this.value.toUpperCase() == "ACTIVE" || this.value.toUpperCase() == "READY TO DELIVER" || this.value.toUpperCase() == "DELIVERED" || this.value.toUpperCase() == "CANCELED" || this.value.toUpperCase() == "REJECTED") {} else {
            orders_dataTable.search(this.value).draw();
        }
    });
    // =====================================================================

    fetch_order_details_for_table(0, 0, 0);
    fetch_delivery_partner_details();

    function fetch_delivery_partner_details() {
        $.ajax({
            url: "orders/fetch_delivery_partner_details",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#delivery_partner_list').empty();
                $.each(data, function(index, val) {
                    delivery_partner += '<option value="' + val.id + '">' + val.name + '</option>';
                });
            }
        });
    }

    // ==============date search==============
    $('#date_range_search').click(function() {
        $('#date_filter').val('');
        fetch_order_details_for_table(1, 1, 0);
    });
    $("#date_filter_btn").click(function() {
        $('#start_date').val('');
        $('#end_date').val('');
        fetch_order_details_for_table(1, 0, 1);
    });
    //refresh button
    $("#btn_refresh_order_table").click(function() {
        orders_dataTable.ajax.reload();
    });
    //auto refresh
    setInterval(function() {
        orders_dataTable.ajax.reload();
    }, 60000);
    //fetch-order-details-table
    function fetch_order_details_for_table(refresh, date_serach, date_filter) {
        if (refresh == 1) {
            orders_dataTable.destroy();
        }
        if (date_serach == 1) {
            var end_date = document.getElementById('end_date').value;
            var start_date = document.getElementById('start_date').value;

            if (start_date == "" || end_date == "") {
                toastr.error("Please select both date to search");
            }

        } else {
            var start_date = "";
            var end_date = "";
        }
        if (date_filter == 1) {
            var date_filter = document.getElementById('date_filter').value;

            if (date_filter == "") {
                toastr.error("Please select a date to filter");
            }
        } else {
            var date_filter = "";
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        orders_dataTable = $('#all_order_table').DataTable({

            "processing": true,
            "serverSide": true,
            "dom": 'trip',
            "order": [2, 'desc'],
            "ajax": {
                "url": "orders/fetch_order_details",
                "data": { start_date: start_date, end_date: end_date, date_filter: date_filter, orders_status: orders_status },
                "dataType": "json",
                "type": "POST",
            },
            "columns": [{
                "data": "order_id"
            }, {
                "data": "customer_name"
            }, {
                "data": "date_time"
            }, {
                "data": "pin_code"
            }, {
                "data": "village_name"
            }, {
                "data": "total_discount"
            }, {
                "data": "payable_amount"
            }, {
                "data": "status"
            }, {
                "data": "delivery_boy"
            }, {
                "data": "action"
            }],
            'columnDefs': [{

                'targets': [9],

                'orderable': false,

            }]
        });
    }
    //===============assign-btn=======================
    window.assign_delivery_partner = function(orders_id) {
        $('#create_assign_modal').empty();
        let create_modal = "";
        create_modal = '<div class="modal fade" id="assign_delivery_partner_modal' + orders_id + '" tabindex="-1" data-backdrop="static" data-keyboard="false">';
        create_modal += '<div class="modal-dialog">';
        create_modal += '<div class="modal-content">';
        create_modal += '<div class="modal-header">';
        create_modal += '<h5 class="modal-title">Select delivery partner</h5>';
        create_modal += '</div>';
        create_modal += '<div class="modal-body">';
        create_modal += '<select class="custom-select" id="delivery_partner_list' + orders_id + '">';
        create_modal += '<option value="" selected>Select delivery partner</option>';
        create_modal += delivery_partner;
        create_modal += '</select>';
        create_modal += '</div>';
        create_modal += '<div class="modal-footer">';
        create_modal += '<div class="ui buttons">';
        create_modal += '<button class="ui button" data-dismiss="modal" id="modal_cancel' + orders_id + '">Cancel</button>';
        create_modal += '<div class="or"></div>';
        create_modal += '<button class="ui positive button" id="assign' + orders_id + '">Assign</button>';
        create_modal += '</div>';
        create_modal += '</div>';
        create_modal += '</div>';
        create_modal += '</div>';
        create_modal += '</div>';
        $('#create_assign_modal').append(create_modal);
        $('#modal_cancel' + orders_id).click(function() {
            $('#delivery_partner_list' + orders_id).val('');
        });
        $('#assign' + orders_id).click(function() {
            delivery_partner_id = document.getElementById("delivery_partner_list" + orders_id).value
            if (delivery_partner_id == "") {
                toastr.error("Please select a delivery partner");
            } else {
                $('#btn_assign').html('<span class="spinner-border spinner-border-sm"></span>');
                swal({
                        title: "Are you sure?",
                        text: "",
                        icon: "warning",
                        buttons: true,
                        closeModal: false,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $('#modal_cancel' + orders_id).click();
                            toastr.info("Please wait assigning order!");
                            $('#btn_assign').addClass('disabled');
                            $.ajax({
                                url: "orders/assign_delivery_partner",
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: { delivery_partner_id: delivery_partner_id, orders_id: orders_id },
                                dataType: 'json',
                                success: function(data) {
                                    if (data['success'] == 1) {
                                        toastr.success((data['message']));
                                        orders_dataTable.ajax.reload();
                                        $('#btn_assign').html('<i class="pen icon"></i>');
                                    } else {
                                        toastr.error(data['message']);
                                    }
                                }
                            });
                        } else {
                            $('#btn_assign').html('<i class="pen icon"></i>');
                        }
                    });
            }
        });
    }

    // ======================view order=======================
    window.view_order = function(orders_id) {
        //creating view_order_modal
        let create_modal = "";
        create_modal = '<div class="modal fade" id="view_order_modal' + orders_id + '" tabindex="-1" data-backdrop="static" data-keyboard="false">';
        create_modal += '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">';
        create_modal += '<div class="modal-content">';
        create_modal += '<div class="modal-header">';
        create_modal += '<h5 class="modal-title">VIEW ORDER</h5>';
        create_modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        create_modal += '<span aria-hidden="true">&times;</span>';
        create_modal += '</button>';
        create_modal += '</div>';
        create_modal += '<div class="modal-body">';
        create_modal += '<h3 id="customer_name">Debanjan Baidya</h3>';
        create_modal += '<p>';
        create_modal += '<i class="fas fa-mobile text-primary ml-1 mr-2"></i><span id="customer_mobile_no"></span><br>';
        create_modal += '<span id="order_email_icon"><i class="fas fa-envelope text-primary mr-2"></i><span id="customer_email"></span><br></span>';
        create_modal += '<i class="fas fa-map-marker-alt text-primary mr-2 ml-1"></i><span id="customer_address"></span>';
        create_modal += ' </p>';
        create_modal += '<h4>Item Details</h4>';
        create_modal += '<div class="table-responsive">';
        create_modal += '<table class="table table-sm table-hover ">';
        create_modal += '<thead>';
        create_modal += '<tr>';
        create_modal += '<th scope="col">#</th>';
        create_modal += '<th scope="col">Name</th>';
        create_modal += '<th scope="col">MRP</th>';
        create_modal += '<th scope="col">Discount</th>';
        create_modal += '<th scope="col">Selling Price</th>';
        create_modal += '<th scope="col">Qty</th>';
        create_modal += '<th scope="col">Total</th>';
        create_modal += '</tr>';
        create_modal += '</thead>';
        create_modal += '<tbody id="item_details_tbody">';
        create_modal += '</tbody>';
        create_modal += '</table>';
        create_modal += '</div>';
        create_modal += '<table class="table table-sm table-hover" style="width:100%;">';
        create_modal += '<tr>';
        create_modal += '<td >Total <br>Special discount <br>Delivery charge</td>';
        create_modal += '<td class="text-right"><span id="total"></span><br> <span class="text-success" id="special_discount"></span> <br><span class="" id="view_delivery_charge"></span></td>';
        create_modal += '</tr>';
        create_modal += '<tr>';
        create_modal += '<td><strong>Payable</strong></td>';
        create_modal += '<td class="text-right"><strong id="total_payable_amount"></strong></td>';
        create_modal += '</tr>';
        create_modal += '</table>';
        create_modal += '</div>';
        create_modal += '<div class="modal-footer">';
        create_modal += '</div>';
        create_modal += '</div>';
        create_modal += '</div>';
        create_modal += '</div>';
        $('#view_order_modal_content').empty();
        $('#view_order_modal_content').append(create_modal);
        //fetch_view_order_details
        $.ajax({
            url: "orders/fetch_order_full_details",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { orders_id: orders_id },
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, val) {
                    $("#customer_name").html(val.customer_name);
                    $("#customer_mobile_no").html(val.customer_mobile_number);
                    if (val.customer_email == "") {
                        $('#order_email_icon').empty();
                    } else {
                        $("#customer_email").html(val.customer_email);
                    }

                    let address = val.village_name + ' , ' + val.area + ' , ' + val.landmark + '<span class=""></span>' + val.city + ' , ' + val.state + '- ' + val.pincode + ' , ' + val.country;
                    $("#customer_address").html(address);
                    $('#item_details_tbody').empty();
                    let count = 1;
                    let item_list = '';
                    let per_item_total = 0;
                    let total = 0;
                    $.each(val.item_details, function(index, val) {
                        per_item_total = parseFloat(val.item_quantity) * parseFloat(val.per_qty_sell_price);
                        item_list += '<tr>';
                        item_list += '<th scope="row">' + count + '</th>';
                        item_list += '<td>' + val.item_name + '</td>';
                        item_list += '<td>' + val.per_qty_mrp + '</td>';
                        item_list += '<td>' + val.per_qty_discount + '</td>';
                        item_list += '<td>' + val.per_qty_sell_price + '</td>';
                        item_list += '<td>' + val.item_quantity + '</td>';
                        item_list += '<td class="text-right">' + per_item_total.toFixed(2) + '</td>';
                        item_list += '</tr>';
                        total += parseFloat(per_item_total);
                        count++;
                    });
                    $('#item_details_tbody').append(item_list);
                    $('#total').empty();
                    $('#total_payable_amount').empty();
                    $('#special_discount').empty();
                    $('#view_delivery_charge').empty();
                    $('#total').html(total.toFixed(2));
                    $('#total_payable_amount').html(Math.round(val.total_payable_amount));
                    $('#special_discount').html('-' + val.applied_coupon_amount);
                    $('#view_delivery_charge').html(val.delivery_charge);
                });

            }
        });

    }

    //export to pdf
    window.export_order_details = function(orders_id) {
        var win = window.open("orders/export_order_details" + "?orders_id=" + orders_id, "_blank");
        win.focus();
    }

    //reject_order
    window.reject_order = function(orders_id) {
        //create_modal
        let create_modal = "";
        create_modal = '<div class="modal fade" id="reject_order_modal' + orders_id + '" tabindex="-1" data-backdrop="static" data-keyboard="false">';
        create_modal += '<div class="modal-dialog">';
        create_modal += '<div class="modal-content">';
        create_modal += '<div class="modal-header">';
        create_modal += '<h5 class="modal-title">Reject Order</h5>';
        create_modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        create_modal += '<span aria-hidden="true">&times;</span>';
        create_modal += '</button>';
        create_modal += '</div>';
        create_modal += '<div class="modal-body">';
        create_modal += '<label for="reject_reason">Choose Reason</label>';
        create_modal += '<select class="custom-select" id="reject_reason">';
        create_modal += '</select>';
        create_modal += '</div>';
        create_modal += '<div class="modal-footer">';
        create_modal += '<div class="ui buttons">';
        create_modal += '<button class="ui button" data-dismiss="modal" id="modal_cancel' + orders_id + '">Cancel</button>';
        create_modal += '<div class="or"></div>';
        create_modal += '<button class="ui negative button" id="reject' + orders_id + '">Reject</button>';
        create_modal += '</div>';
        create_modal += '</div>';
        create_modal += '</div>';
        create_modal += '</div>';
        create_modal += '</div>';
        $('#reject_order_modal_content').empty();
        $('#reject_order_modal_content').append(create_modal);
        fetch_cancellation_reason();

        function fetch_cancellation_reason() {
            let cancellation_reason_list = "";
            $.ajax({
                url: "settings/fetch_all_cancellation_reason",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(data) {
                    $('#reject_reason').empty();
                    cancellation_reason_list += '<option value="" selected>Select Reason</option>';
                    $.each(data, function(index, val) {
                        cancellation_reason_list += '<option value="' + val.id + '">' + val.reason + '</option>';
                    });
                    $('#reject_reason').append(cancellation_reason_list);
                }
            });
        }

        $('#reject' + orders_id).click(function() {
            let rejected_reason = document.getElementById('reject_reason').value;
            if (rejected_reason == "") {
                toastr.error("Please select a reason..!");
            } else {
                swal({
                        title: "Are you sure?",
                        text: "Once rejected, you will not be able to change!",
                        icon: "warning",
                        buttons: true,
                        closeModal: false,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $('#modal_cancel' + orders_id).click();
                            $('#reject_order_icon' + orders_id).html('<span class="spinner-border spinner-border-sm"></span>');
                            $('#reject_order_icon' + orders_id).addClass('disabled');
                            toastr.info("Please wait rejecting order!");
                            $.ajax({
                                url: "orders/reject_order",
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: { orders_id: orders_id, rejected_reason: rejected_reason },
                                dataType: 'json',
                                success: function(data) {
                                    if (data['success'] == 1) {
                                        toastr.success((data['message']));
                                        $('#reject_order_icon' + orders_id).html('<i class="times icon"></i>');
                                        orders_dataTable.ajax.reload();
                                    } else {
                                        toastr.error(data['message']);
                                    }
                                }
                            });
                        }
                    });
            }
        });
    }

};

function report() {
    $("#report_menu").addClass("menu-open");
    // for calender input
    $('#order_by_product_rangestart').calendar({
        endCalendar: $('#order_by_product_rangeend')
    });
    $('#order_by_product_rangeend').calendar({
        startCalendar: $('#order_by_product_rangestart')
    });
    //for aggregator calender
    $('#aggregator_rangestart').calendar({
        endCalendar: $('#aggregator_rangeend')
    });
    $('#aggregator_rangeend').calendar({
        startCalendar: $('#aggregator_rangestart')
    });
    //for village
    $('#village_rangestart').calendar({
        endCalendar: $('#village_rangeend')
    });
    $('#village_rangeend').calendar({
        startCalendar: $('#village_rangestart')
    });
    //for drop-down
    $('.ui.dropdown').dropdown();
    //reload order_by_product_table after 1minute
    setInterval(function() {
        report_order_by_product_table.ajax.reload();
    }, 60000);
    //reload report_aggregator_table after 1minute
    setInterval(function() {
        report_aggregator_table.ajax.reload();
    }, 60000);
    //refresh button
    $("#btn_refresh_order_by_product_table").click(function() {
        report_order_by_product_table.ajax.reload();
    });
    //===========================
    fetch_pincode();
    fetch_aggregator_report(0);
    fetch_order_by_product(0, 0, 0);
    $('#order_by_product_date_range_search').click(function() {
        fetch_order_by_product(1, 1);
    });
    //fetch_order_by_product
    function fetch_order_by_product(refresh, order_by_product_date_search, print) {
        if (refresh == 1) {
            report_order_by_product_table.destroy();
        }
        if (order_by_product_date_search == 1) {
            var start_date = document.getElementById('order_by_product_start_date').value;
            var end_date = document.getElementById('order_by_product_end_date').value;

            if (start_date == "" || end_date == "") {
                toastr.error("Please select both date to search");
            }

        } else {
            var start_date = "";
            var end_date = "";
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        report_order_by_product_table = $('#report_order_by_product_table').DataTable({

            "processing": true,
            "serverSide": true,
            "dom": 'trp',
            "order": [1, 'asc'],
            "ajax": {
                "url": "reports/fetch_order_by_product",
                "data": { start_date: start_date, end_date: end_date },
                "dataType": "json",
                "type": "POST",
            },
            "columns": [{
                "data": "sl_no"
            }, {
                "data": "product_id"
            }, {
                "data": "product_name"
            }, {
                "data": "unit_type"
            }, {
                "data": "unit_quantity"
            }, {
                "data": "total_product_order"
            }, {
                "data": "total_unit_quantity"
            }, {
                "data": "total_amount"
            }],
            'columnDefs': [{

                'targets': [0],

                'orderable': false,

            }]

        });
    }
    //aggregator date_search
    $('#aggregator_date_range_search').click(function() {
        fetch_aggregator_report(1, 1);
    });
    //aggregator refresh button
    $("#btn_refresh_aggregator_table").click(function() {
        report_aggregator_table.ajax.reload();
    });
    //fetch-order-details-table
    function fetch_aggregator_report(refresh, date_search) {
        if (refresh == 1) {
            report_aggregator_table.destroy();
        }
        if (date_search == 1) {
            var start_date = document.getElementById('aggregator_start_date').value;
            var end_date = document.getElementById('aggregator_end_date').value;
            if (start_date == "" || end_date == "") {
                toastr.error("Please select both date to search");
            }
        } else {
            var start_date = "";
            var end_date = "";
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        report_aggregator_table = $('#aggregator_table').DataTable({

            "processing": true,
            "serverSide": true,
            "dom": 'trip',
            "ajax": {
                "url": "reports/fetch_aggregator_report",
                "data": { start_date: start_date, end_date: end_date },
                "dataType": "json",
                "type": "POST",
            },
            "columns": [{
                "data": "id"
            }, {
                "data": "name"
            }, {
                "data": "total_order"
            }, {
                "data": "total_order_amount"
            }, {
                "data": "action"
            }],
            'columnDefs': [{

                'targets': [0, 1, 2, 3, 4],

                'orderable': false,

            }]
        });
    }
    //print order by products details
    $('#btn_print_order_by_product_report').click(function() {
        var start_date = document.getElementById('order_by_product_start_date').value;
        var end_date = document.getElementById('order_by_product_end_date').value;
        if (start_date == "" || end_date == "") {
            toastr.error("Please select date range to print");
        } else {
            var order_by_product_win = window.open("reports/print_order_by_product_report" + "?start_date=" + start_date + "&end_date=" + end_date, "_blank");
            order_by_product_win.focus();
        }
    });
    //print_aggregator details
    $('#btn_print_aggregator_report').click(function() {
        var start_date = document.getElementById('aggregator_start_date').value;
        var end_date = document.getElementById('aggregator_end_date').value;
        if (start_date == "" || end_date == "") {
            toastr.error("Please select date range to print");
        } else {
            var aggregator_report_win = window.open("reports/print_aggregator_report" + "?start_date=" + start_date + "&end_date=" + end_date, "_blank");
            aggregator_report_win.focus();
        }
    });
    //fetch pincode
    function fetch_pincode() {

        $.ajax({
            url: "reports/fetch_pincode",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                var pincode_list = "";
                pincode_list += '<option value="" id="selected_pincode">Select Pincode</option>';
                $.each(data, function(index, val) {
                    pincode_list += '<option value="' + val.pincode_id + '">' + val.pincode + '</option>';
                });
                $('#pincode').append(pincode_list);
            }
        });
    }
    //fetch_village
    $('#pincode').change(function() {
        $('#total_village_order').empty();
        $('#total_village_order_amount').empty();
        var selected_pincode = document.getElementById('pincode').value;
        $('#village').empty();
        $.ajax({
            url: "reports/fetch_village",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { selected_pincode: selected_pincode },
            dataType: 'json',
            success: function(data) {
                var village_list = "";
                village_list += '<option value="" id="select_category">Select Category</option>';
                $.each(data, function(index, val) {
                    village_list += '<option value="' + val.village_id + '">' + val.village_name + '</option>';
                });
                $('#village').append(village_list);
            }
        });
    });

    //fetch_data_according to pincode and village
    function fetch_order_on_village(date_search) {
        var selected_pincode = document.getElementById('pincode').value;
        var selected_village = document.getElementById('village').value;
        if (date_search == 1) {
            var start_date = document.getElementById('village_start_date').value;
            var end_date = document.getElementById('village_end_date').value;
            if (start_date == "" || end_date == "") {
                toastr.error("Please select both date to search");
            }
        } else {
            var start_date = "";
            var end_date = "";
        }
        $.ajax({
            url: "reports/data_on_pincode_village",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { selected_pincode: selected_pincode, selected_village: selected_village, start_date: start_date, end_date: end_date },
            dataType: 'json',
            success: function(data) {

                $.each(data, function(index, val) {
                    $('#total_village_order').html(val.total_order);
                    if (val.total_order_amount == 0 || val.total_order_amount == null) {
                        var total_order_amount = 0;
                    } else {
                        var total_order_amount = val.total_order_amount;
                    }
                    $('#total_village_order_amount').html('₹ ' + total_order_amount);
                });

            }
        });
    }
    //on chnge village call fetch function
    $('#village').change(function() {
        $('#total_village_order').empty();
        $('#total_village_order_amount').empty();
        let selected_village = document.getElementById('village').value;
        if (selected_village != "") {
            fetch_order_on_village(0);
        }
    });
    //if_date_village_search
    $('#village_date_range_search').click(function() {
        let selected_pincode = document.getElementById('pincode').value;
        let selected_village = document.getElementById('village').value;
        if (selected_pincode == "" || selected_village == "") {
            toastr.error("Please select pincode and village first");
        } else {
            $('#total_village_order').empty();
            $('#total_village_order_amount').empty();
            fetch_order_on_village(1);
        }
    });
    var flag = 0;
    // $('#btn_view_aggregator_order_details').click(function() {

    // });
    window.aggregator_view_order_details = function(aggregator_id) {
        $('#view_aggregator_order_details_modal_placeholder').empty();
        flag++;

        let aggregator_view_order_str = '';
        aggregator_view_order_str += '';
        aggregator_view_order_str += '<div class="modal fade" id="aggregator_view_order_details_modal' + aggregator_id + '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        aggregator_view_order_str += '<div class="modal-dialog modal-dialog-scrollable modal-lg">';
        aggregator_view_order_str += '<div class="modal-content">';
        aggregator_view_order_str += '<div class="modal-header">';
        aggregator_view_order_str += '<h5 class="modal-title">Order details</h5>';
        aggregator_view_order_str += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        aggregator_view_order_str += '<span aria-hidden="true">&times;</span>';
        aggregator_view_order_str += '</button>';
        aggregator_view_order_str += '</div>';
        aggregator_view_order_str += '<div class="modal-body">';
        aggregator_view_order_str += '<div class="col-md-12  card border border-primary">';
        aggregator_view_order_str += '<div class="table-responsive  mt-2 mb-2">';
        aggregator_view_order_str += '<div class="d-flex">';
        aggregator_view_order_str += '<button class="circular ui icon button primary small" id="report_btn_refresh_aggregator_view_order_details_table" data-tooltip="Refresh table" data-variation="mini" data-position="right center" data-inverted="">';
        aggregator_view_order_str += '<i class="sync alternate icon"></i>';
        aggregator_view_order_str += '</button>';
        aggregator_view_order_str += '</div>';
        aggregator_view_order_str += '<table class="table table-bordered table-sm text-center" id="report_aggregator_view_order_details_table" width="100%" data-page-length="10">';
        aggregator_view_order_str += '<thead class="thead-light">';
        aggregator_view_order_str += '<tr>';
        aggregator_view_order_str += '<th scope="col">Id</th>';
        aggregator_view_order_str += '<th scope="col">Pincode</th>';
        aggregator_view_order_str += '<th scope="col">Village</th>';
        aggregator_view_order_str += '<th scope="col">Total amount</th>';
        aggregator_view_order_str += '<th scope="col">Date & Time</th>';
        aggregator_view_order_str += '</tr>';
        aggregator_view_order_str += '</thead>';
        aggregator_view_order_str += '</table>';
        aggregator_view_order_str += '</div>';
        aggregator_view_order_str += '</div>';
        aggregator_view_order_str += '</div>';
        aggregator_view_order_str += '</div>';
        aggregator_view_order_str += '</div>';
        aggregator_view_order_str += '</div>';
        $('#view_aggregator_order_details_modal_placeholder').append(aggregator_view_order_str);
        if (flag <= 1) {
            fetch_aggregator_order_details(0);
        } else {
            fetch_aggregator_order_details(1);
        }
        //aggregator refresh order_view button
        $("#report_btn_refresh_aggregator_view_order_details_table").click(function() {
            report_aggregator_view_order_details_table.ajax.reload();
        });
        //fetch_aggregator_order_details
        function fetch_aggregator_order_details(refresh, date_search) {
            if (refresh == 1) {
                report_aggregator_view_order_details_table.destroy();
            }
            // if (date_search == 1) {
            var start_date = document.getElementById('aggregator_start_date').value;
            var end_date = document.getElementById('aggregator_end_date').value;
            // if (start_date == "" || end_date == "") {
            //     toastr.error("Please select both date to search");
            // }
            // } else {
            //     var start_date = "";
            //     var end_date = "";
            // }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            report_aggregator_view_order_details_table = $('#report_aggregator_view_order_details_table').DataTable({

                "processing": true,
                "serverSide": true,
                "dom": 'trip',
                "ajax": {
                    "url": "reports/fetch_aggregator_order_details",
                    "data": { start_date: start_date, end_date: end_date, aggregator_id: aggregator_id },
                    "dataType": "json",
                    "type": "POST",
                },
                "columns": [{
                    "data": "id"
                }, {
                    "data": "pincode"
                }, {
                    "data": "village_name"
                }, {
                    "data": "total_amount"
                }, {
                    "data": "date_time"
                }]
            });
        }
    }


}

function users() {
    var date_options = {
        year: "numeric",
        month: "2-digit",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit"
    };
    fetch_customer(0);
    fetch_admin(0);
    fetch_aggregator(0);
    fetch_salesman(0);
    $('#customer_tab').click(function() {
        fetch_customer(1);
        $('#change_eable_btn').empty();
    });
    $('#admin_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn').append('<button class="ui  primary small button nav-link mt-2" data-toggle="modal" data-target="#admin_modal" id="add_admin_btn"><i class="fas fa-plus"></i> Add Admin</button>');
    });
    $('#aggregator_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn').append('<button class="ui  primary small button nav-link mt-2" data-toggle="modal" data-target="#aggregator_modal" id="add_aggregator_btn"><i class="fas fa-plus"></i> Add Aggregator</button>');
    });
    $('#salesman_tab').click(function() {
        $('#change_eable_btn').empty();
        $('#change_eable_btn').append('<button class="ui  primary small button nav-link mt-2" data-toggle="modal" data-target="#salesman_modal" id="add_salesman_btn"><i class="fas fa-plus"></i> Add Salesman</button>');
    });
    //refresh button
    $("#btn_refresh_customer_table").click(function() {
        customer_table.ajax.reload();
    });
    //auto refresh
    // setInterval(function() {
    //     customer_table.ajax.reload();
    // }, 60000);
    //all search_table
    $('#search_customer_filter').keyup(function () {
        customer_table.search(this.value).draw();
    });
    $('#search_admin_filter').keyup(function() {
        admin_table.search(this.value).draw();
    });
    $('#search_aggregator_filter').keyup(function() {
        aggregator_table.search(this.value).draw();
    });
    $('#search_salesman_filter').keyup(function() {
        salesman_table.search(this.value).draw();
    });
    //fetch total_customer
    function fetch_customer(refresh) {
        if (refresh == 1) {
            customer_table.destroy();
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        customer_table = $('#customer_table').DataTable({

            "processing": true,
            "serverSide": true,
            "dom": 'trip',
            "order": [0, 'desc'],
            "ajax": {
                "url": "users/fetch_customer",
                "dataType": "json",
                "type": "POST",
            },
            "columns": [{
                "data": "customer_id"
            }, {
                "data": "customer_name"
            }, {
                "data": "customer_email"
            }, {
                "data": "customer_mobile"
            }, {
                "data": "created_at"
            }, {
                "data": "action"
            }],
            'columnDefs': [{

                'targets': [5],

                'orderable': false,

            }]

        });
    }
    window.view_customer_details = function(customer_id) {
        $('#customer_details_modal').empty();
        let customer_details_modal_str = '';
        customer_details_modal_str += '<div class="modal fade" id="view_customer_details_modal' + customer_id + '" tabindex="-1">';
        customer_details_modal_str += '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">';
        customer_details_modal_str += '<div class="modal-content  card">';
        customer_details_modal_str += '<button type="button" class="btn btn-primary rounded-pill btn-sm view_product_modal_btn_close" data-dismiss="modal">';
        customer_details_modal_str += '<span class="">&times;</span>';
        customer_details_modal_str += '</button>';
        customer_details_modal_str += '<div class="modal-body card-body">';
        customer_details_modal_str += '<div class="row">';
        customer_details_modal_str += '<div class=" col-md-12">';
        customer_details_modal_str += '<p class="border-bottom border-dark mt-3"><strong class="h3">Customer details</strong></p>';
        customer_details_modal_str += '<table class="table table-sm table-borderless">';
        customer_details_modal_str += '<colgroup>';
        customer_details_modal_str += '<col style="width:100px;">';
        customer_details_modal_str += '<col style="width:10px;">';
        customer_details_modal_str += '<col>';
        customer_details_modal_str += '</colgroup>';
        customer_details_modal_str += '<tr>';
        customer_details_modal_str += '<td><strong>Name</strong></td>';
        customer_details_modal_str += '<td>:</td>';
        customer_details_modal_str += '<td><span id="customer_name"></span></td>';
        customer_details_modal_str += '</tr>';
        customer_details_modal_str += '<tr>';
        customer_details_modal_str += '<td><strong>Email</strong></td>';
        customer_details_modal_str += '<td>:</td>';
        customer_details_modal_str += '<td><span id="customer_email"></span></td>';
        customer_details_modal_str += '</tr>';
        customer_details_modal_str += '<tr>';
        customer_details_modal_str += '<td><strong>Mobile No.</strong></td>';
        customer_details_modal_str += '<td>:</td>';
        customer_details_modal_str += '<td><span id="customer_mobile_no"></span></td>';
        customer_details_modal_str += '</tr>';
        customer_details_modal_str += '<tr>';
        customer_details_modal_str += '<td><strong>Total orders</strong></td>';
        customer_details_modal_str += '<td>:</td>';
        customer_details_modal_str += '<td><span id="customer_total_order"></span></td>';
        customer_details_modal_str += '</tr>';
        customer_details_modal_str += '<tr>';
        customer_details_modal_str += '<td><strong>Created at</strong></td>';
        customer_details_modal_str += '<td>:</td>';
        customer_details_modal_str += '<td><span id="customer_created_at"></span></td>';
        customer_details_modal_str += '</tr>';
        customer_details_modal_str += '<tr>';
        customer_details_modal_str += '<td><strong>Addresses</strong></td>';
        customer_details_modal_str += '<td>:</td>';
        customer_details_modal_str += '<td><span id="addresses"></span></td>';
        customer_details_modal_str += '</tr>';
        customer_details_modal_str += '</table>';
        customer_details_modal_str += '</div>';
        customer_details_modal_str += '</div>';
        customer_details_modal_str += '</div>';
        customer_details_modal_str += '</div>';
        customer_details_modal_str += '</div>';
        customer_details_modal_str += '</div>';
        $('#customer_details_modal').append(customer_details_modal_str);
        $.ajax({
            url: "users/fetch_customer_details",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { customer_id: customer_id },
            dataType: 'json',
            success: function(data) {
                $("#customer_name").empty();
                $("#customer_email").empty();
                $("#customer_mobile_no").empty();
                $("#customer_total_order").empty();

                $("#customer_name").html(data.name);
                $("#customer_email").html(data.email);
                $("#customer_mobile_no").html(data.mobile_number);
                $("#customer_total_order").html(data.total_order);
                let date = new Date((data.created_at) * 1000);
                $("#customer_created_at").html(date.toLocaleString());
                $('#addresses').empty();
                var address_str = '';
                $.each(data.address, function(index, val) {
                    address_str += '<p>' + val.house_no + ',' + val.village_name + ',' + val.area + '<br>';
                    address_str += 'Near: ' + val.landmark + '<br>' + val.state + '-' + val.pin_code + '</p>';
                });
                $('#addresses').append(address_str);

            }
        });

    }

    // add admin
    $('#admin_form').submit(function(e) {
        e.preventDefault();
        let admin_name = document.getElementById("admin_name").value;
        let admin_email = document.getElementById("admin_email").value;
        let admin_mobile = document.getElementById("admin_mobile").value;
        let email_format = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        if (admin_name == '' || admin_email == '' || admin_mobile == '') {
            toastr.error("*Marked Items Are mandatory To fill");
        } else if (!(admin_email.match(email_format))) {
            toastr.error("Please enter a valid email");
        } else if (admin_mobile.length != 10) {
            toastr.error("Mobile nuumber need to be 10 digits");
        } else {
            $('#admin_modal_close_modal').click();
            $('#add_admin_btn').html('<span class="spinner-border spinner-border-sm"></span>');
            $('#add_admin_btn').prop('disabled', true);
            $.ajax({
                url: "users/add_admin",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { admin_name: admin_name, admin_email: admin_email, admin_mobile: admin_mobile },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success(data['message']);
                        $('#add_admin_btn').html('<i class="fas fa-plus"></i> Add Admin');
                        $('#add_admin_btn').prop('disabled', false);
                        fetch_admin(1);
                    } else {
                        toastr.error(data['message']);
                        $('#add_admin_btn').prop('disabled', false);
                    }

                }
            });
        }
    });

    // add aggregator
    $('#aggregator_form').submit(function(e) {
        e.preventDefault();
        let aggregator_name = document.getElementById("aggregator_name").value;
        let aggregator_email = document.getElementById("aggregator_email").value;
        let aggregator_mobile = document.getElementById("aggregator_mobile").value;
        let email_format = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        if (aggregator_name == '' || aggregator_email == '' || aggregator_mobile == '') {
            toastr.error("*Marked Items Are mandatory To fill");
        } else if (!(aggregator_email.match(email_format))) {
            toastr.error("Please enter a valid email");
        } else if (aggregator_mobile.length != 10) {
            toastr.error("Mobile nuumber need to be 10 digits");
        } else {
            $('#aggregator_modal_close_modal').click();
            $('#add_aggregator_btn').html('<span class="spinner-border spinner-border-sm"></span>');
            $('#add_aggregator_btn').prop('disabled', true);
            $.ajax({
                url: "users/add_aggregator",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { aggregator_name: aggregator_name, aggregator_email: aggregator_email, aggregator_mobile: aggregator_mobile },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success(data['message']);
                        $('#add_aggregator_btn').html('<i class="fas fa-plus"></i> Add Aggregator');
                        $('#add_aggregator_btn').prop('disabled', false);
                        fetch_aggregator(1);
                    } else {
                        toastr.error(data['message']);
                        $('#add_aggregator_btn').prop('disabled', false);
                    }

                }
            });
        }
    });

    // add salesman
    $('#salesman_form').submit(function(e) {
        e.preventDefault();
        let salesman_name = document.getElementById("salesman_name").value;
        let salesman_email = document.getElementById("salesman_email").value;
        let salesman_mobile = document.getElementById("salesman_mobile").value;
        let email_format = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        if (salesman_name == '' || salesman_email == '' || salesman_mobile == '') {
            toastr.error("*Marked Items Are mandatory To fill");
        } else if (!(salesman_email.match(email_format))) {
            toastr.error("Please enter a valid email");
        } else if (salesman_mobile.length != 10) {
            toastr.error("Mobile nuumber need to be 10 digits");
        } else {
            $('#salesman_modal_close_modal').click();
            $('#add_salesman_btn').html('<span class="spinner-border spinner-border-sm"></span>');
            $('#add_salesman_btn').prop('disabled', true);
            $.ajax({
                url: "users/add_salesman",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { salesman_name: salesman_name, salesman_email: salesman_email, salesman_mobile: salesman_mobile },
                dataType: 'json',
                success: function(data) {
                    if (data['success'] == 1) {
                        toastr.success(data['message']);
                        $('#add_salesman_btn').html('<i class="fas fa-plus"></i> Add Salesman');
                        $('#add_salesman_btn').prop('disabled', false);
                        fetch_salesman(1);
                    } else {
                        toastr.error(data['message']);
                        $('#add_salesman_btn').prop('disabled', false);
                    }

                }
            });
        }
    });

    // fetch_admin
    function fetch_admin(refresh) {
        if (refresh == 1) {
            admin_table.destroy();
        }
        $.ajax({
            url: "users/fetch_admin",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#admin_table_tbody').empty();
                let admin_list = '';
                let sl_no = 1;
                $.each(data, function(index, val) {
                    let date = new Date((val.created_at) * 1000);

                    if (val.is_deleted == 0) {
                        var status = '<button class="ui button green mini" id="admin_status_btn' + val.id + '" onclick="change_admin_status(' + val.id + ',' + val.is_deleted + ',\'' + val.email + '\',\'' + val.name + '\')">Active</button>';
                    } else {
                        var status = '<button class="ui button red mini" id="admin_status_btn' + val.id + '" onclick="change_admin_status(' + val.id + ',' + val.is_deleted + ',\'' + val.email + '\',\'' + val.name + '\')">Deactive</button>';
                    }
                    admin_list += '<tr>';
                    admin_list += '<td>' + sl_no + '</td>';
                    admin_list += '<td>' + val.id + '</td>';
                    admin_list += '<td>' + val.name + '</td>';
                    admin_list += '<td>' + val.email + '</td>';
                    admin_list += '<td>' + val.mobile_number + '</td>';
                    admin_list += '<td>' + date.toLocaleString('en-IN', date_options) + '</td>';
                    admin_list += '<td>' + status + '</td>';
                    admin_list += '<td><button class="circular ui icon button primary small" data-tooltip="Reset password"  data-variation="mini" data-position="top right"  id="admin_reset_btn' + val.id + '" onclick="reset_admin_pass(' + val.id + ',\'' + val.email + '\',\'' + val.name + '\')"><i class="sync alternate icon"></i></button></td>';
                    admin_list += '</tr>';
                    sl_no++;
                });
                $('#admin_table_tbody').append(admin_list);
                admin_table = $('#admin_table').DataTable({
                    "dom": 'trip',
                    'columnDefs': [{

                        'targets': [7],

                        'orderable': false,

                    }]
                });
            }
        });

    }
    //change_admin_status
    window.change_admin_status = function(admin_id, status, admin_email, admin_name) {
        if (status == 0) {
            status = 1;
        } else {
            status = 0;
        }
        swal({
                title: "Want to deactived?",
                text: "",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#admin_status_btn' + admin_id).html('<span class="spinner-border spinner-border-sm"></span>');
                    $('#admin_status_btn' + admin_id).prop('disabled', true);
                    $.ajax({
                        url: "users/change_admin_status",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { admin_id: admin_id, status: status, admin_email: admin_email, admin_name: admin_name },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                toastr.success((data['message']));
                                $('#admin_status_btn' + admin_id).prop('disabled', false);
                                fetch_admin(1);
                            } else {
                                toastr.error(data['message']);
                                $('#admin_status_btn' + admin_id).prop('disabled', false);
                                fetch_admin(1);
                            }
                        }
                    });
                }
            });
    }

    //reset_admin_pass
    window.reset_admin_pass = function(admin_id, admin_email, admin_name) {
        swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#admin_reset_btn' + admin_id).html('<span class="spinner-border spinner-border-sm"></span>Reseting..');
                    $('#admin_reset_btn' + admin_id).prop('disabled', true);
                    $.ajax({
                        url: "users/reset_admin_password",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { admin_id: admin_id, admin_email: admin_email, admin_name: admin_name },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                toastr.success((data['message']));
                                $('#admin_reset_btn' + admin_id).prop('disabled', false);
                                fetch_admin(1);
                            } else {
                                toastr.error(data['message']);
                                $('#admin_reset_btn' + admin_id).prop('disabled', false);
                                fetch_admin(1);
                            }
                        }
                    });
                }
            });
    }

    // fetch aggregator
    function fetch_aggregator(refresh) {
        if (refresh == 1) {
            aggregator_table.destroy();
        }
        $.ajax({
            url: "users/fetch_aggregator",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#aggregator_table_tbody').empty();
                let aggregator_list = '';
                let sl_no = 1;
                $.each(data, function(index, val) {
                    let date = new Date((val.created_at) * 1000);
                    if (val.is_deleted == 0) {
                        var status = '<button class="ui button green mini" id="aggregator_status_btn' + val.id + '" onclick="change_aggregator_status(' + val.id + ',' + val.is_deleted + ',\'' + val.email + '\',\'' + val.name + '\')">Active</button>';
                    } else {
                        var status = '<button class="ui button red mini" id="aggregator_status_btn' + val.id + '" onclick="change_aggregator_status(' + val.id + ',' + val.is_deleted + ',\'' + val.email + '\',\'' + val.name + '\')">Deactive</button>';
                    }
                    aggregator_list += '<tr>';
                    aggregator_list += '<td>' + sl_no + '</td>';
                    aggregator_list += '<td>' + val.id + '</td>';
                    aggregator_list += '<td>' + val.name + '</td>';
                    aggregator_list += '<td>' + val.email + '</td>';
                    aggregator_list += '<td>' + val.mobile_number + '</td>';
                    aggregator_list += '<td>' + date.toLocaleString('en-IN', date_options) + '</td>';
                    aggregator_list += '<td>' + status + '</td>';
                    aggregator_list += '<td><button class="circular ui icon button primary small" data-tooltip="Reset password"  data-variation="mini" data-position="top right"  id="aggregator_reset_btn' + val.id + '" onclick="reset_aggregator_pass(' + val.id + ',\'' + val.email + '\',\'' + val.name + '\')"><i class="sync alternate icon"></i></button></td>';
                    aggregator_list += '</tr>';
                    sl_no++;
                });
                $('#aggregator_table_tbody').append(aggregator_list);
                aggregator_table = $('#aggregator_table').DataTable({
                    "dom": 'trip',
                    'columnDefs': [{

                        'targets': [7],

                        'orderable': false,

                    }]
                });
            }
        });

    }


    //change_admin_status
    window.change_aggregator_status = function(aggregator_id, status, aggregator_email, aggregator_name) {
        if (status == 0) {
            status = 1;
        } else {
            status = 0;
        }
        swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#aggregator_status_btn' + aggregator_id).html('<span class="spinner-border spinner-border-sm"></span>');
                    $('#aggregator_status_btn' + aggregator_id).prop('disabled', true);
                    $.ajax({
                        url: "users/change_aggregator_status",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { aggregator_id: aggregator_id, status: status, aggregator_email: aggregator_email, aggregator_name: aggregator_name },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                toastr.success((data['message']));
                                $('#aggregator_status_btn' + aggregator_id).prop('disabled', false);
                                fetch_aggregator(1);
                            } else {
                                toastr.error(data['message']);
                                $('#aggregator_status_btn' + aggregator_id).prop('disabled', false);
                                fetch_aggregator(1);
                            }
                        }
                    });
                }
            });
    }

    //reset_aggregator_pass
    window.reset_aggregator_pass = function(aggregator_id, aggregator_email, aggregator_name) {
        swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#aggregator_reset_btn' + aggregator_id).html('<span class="spinner-border spinner-border-sm"></span>Reseting..');
                    $('#aggregator_reset_btn' + aggregator_id).prop('disabled', true);
                    $.ajax({
                        url: "users/reset_aggregator_password",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { aggregator_id: aggregator_id, aggregator_email: aggregator_email, aggregator_name: aggregator_name },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                toastr.success((data['message']));
                                $('#aggregator_reset_btn' + aggregator_id).prop('disabled', false);
                                fetch_aggregator(1);
                            } else {
                                toastr.error(data['message']);
                                $('#aggregator_reset_btn' + aggregator_id).prop('disabled', false);
                                fetch_aggregator(1);
                            }
                        }
                    });
                }
            });
    }

    // fetch salesman
    function fetch_salesman(refresh) {
        if (refresh == 1) {
            salesman_table.destroy();
        }
        $.ajax({
            url: "users/fetch_salesman",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                $('#salesman_table_tbody').empty();
                let salesman_list = '';
                let sl_no = 1;
                $.each(data, function(index, val) {
                    let date = new Date((val.created_at) * 1000);
                    if (val.is_deleted == 0) {
                        var status = '<button class="ui button green mini" id="salesman_status_btn' + val.id + '" onclick="change_salesman_status(' + val.id + ',' + val.is_deleted + ',\'' + val.email + '\',\'' + val.name + '\')">Active</button>';
                    } else {
                        var status = '<button class="ui button red mini" id="salesman_status_btn' + val.id + '" onclick="change_salesman_status(' + val.id + ',' + val.is_deleted + ',\'' + val.email + '\',\'' + val.name + '\')">Deactive</button>';
                    }
                    salesman_list += '<tr>';
                    salesman_list += '<td>' + sl_no + '</td>';
                    salesman_list += '<td>' + val.id + '</td>';
                    salesman_list += '<td>' + val.name + '</td>';
                    salesman_list += '<td>' + val.email + '</td>';
                    salesman_list += '<td>' + val.mobile_number + '</td>';
                    salesman_list += '<td>' + date.toLocaleString('en-IN', date_options) + '</td>';
                    salesman_list += '<td>' + status + '</td>';
                    salesman_list += '<td><button class="circular ui icon button primary small" data-tooltip="Reset password"  data-variation="mini" data-position="top right"  id="salesman_reset_btn' + val.id + '" onclick="reset_salesman_pass(' + val.id + ',\'' + val.email + '\',\'' + val.name + '\')"><i class="sync alternate icon"></i></button></td>'
                    salesman_list += '</tr>';
                    sl_no++;
                });
                $('#salesman_table_tbody').append(salesman_list);
                salesman_table = $('#salesman_table').DataTable({
                    "dom": 'trip',
                    'columnDefs': [{

                        'targets': [7],

                        'orderable': false,

                    }]
                });
            }
        });

    }

    //change_salesman_status
    window.change_salesman_status = function(salesman_id, status, salesman_email, salesman_name) {
        if (status == 0) {
            status = 1;
        } else {
            status = 0;
        }
        swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#salesman_status_btn' + salesman_id).html('<span class="spinner-border spinner-border-sm"></span>');
                    $('#salesman_status_btn' + salesman_id).prop('disabled', true);
                    $.ajax({
                        url: "users/change_salesman_status",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { salesman_id: salesman_id, status: status, salesman_email: salesman_email, salesman_name: salesman_name },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                toastr.success((data['message']));
                                $('#salesman_status_btn' + salesman_id).prop('disabled', false);
                                fetch_salesman(1);
                            } else {
                                toastr.error(data['message']);
                                $('#salesman_status_btn' + salesman_id).prop('disabled', false);
                                fetch_salesman(1);
                            }
                        }
                    });
                }
            });
    }

    //reset_salesman_pass
    window.reset_salesman_pass = function(salesman_id, salesman_email, salesman_name) {
        swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                closeModal: false,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#salesman_reset_btn' + salesman_id).html('<span class="spinner-border spinner-border-sm"></span>Reseting..');
                    $('#salesman_reset_btn' + salesman_id).prop('disabled', true);
                    $.ajax({
                        url: "users/reset_salesman_password",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { salesman_id: salesman_id, salesman_email: salesman_email, salesman_name: salesman_name },
                        dataType: 'json',
                        success: function(data) {
                            if (data['success'] == 1) {
                                toastr.success((data['message']));
                                $('#salesman_reset_btn' + salesman_id).prop('disabled', false);
                                fetch_salesman(1);
                            } else {
                                toastr.error(data['message']);
                                $('#salesman_reset_btn' + salesman_id).prop('disabled', false);
                                fetch_salesman(1);
                            }
                        }
                    });
                }
            });
    }

}
