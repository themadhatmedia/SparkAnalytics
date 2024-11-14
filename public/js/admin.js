var arrChartRec = [];


/*add site*/
summernote()
function summernote() {
    if ($(".summernote-simple").length) {
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            maxHeight: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 250,

        });
        $('.dropdown-toggle').dropdown();
    }

    if ($(".summernote-simple-2").length) {
        $('.summernote-simple-2').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            maxHeight: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 250,

        });
    }


}

// Validation Code
function validation() {
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.forEach.call(forms, function (form) {
        
        form.addEventListener('submit', function (event) {
            var submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            
            if (submitButton) {
                submitButton.disabled = true;
            }
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

$(document).ready(function () {
    if ($(".pc-dt-simple").length > 0) {
        $($(".pc-dt-simple")).each(function (index, element) {
            var id = $(element).attr('id');
            const dataTable = new simpleDatatables.DataTable("#" + id);
        });
    }
    
    if ($(".needs-validation").length > 0) {
        validation();
    }


    // common_bind();
    summernote();


    // for Choose file
    $(document).on('change', 'input[type=file]', function () {
        var fileclass = $(this).attr('data-filename');
        var finalname = $(this).val().split('\\').pop();
        $('.' + fileclass).html(finalname);
    });
});

function cookie_inputs() {

    if ($('#enable_cookie').prop('checked') == true) {
        $('.cookie_input').removeAttr("disabled");
        $('.cookie-logging').show();
    } else {
        $('.cookie-logging').hide();
        $('.cookie_input').attr("disabled", "true");
    }
}

function show_download_link() {

    if ($('#cookie_logging').prop('checked') == true) {
        $('.cookie_download').show();
    } else {
        $('.cookie_download').hide();
    }
}

$(document).ready(function () {

    genrate_accesstoken();
    setInterval(genrate_accesstoken, 3600000);
});

function genrate_accesstoken() {
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: $("#path_admin").val() + "/genrate_accesstoken",
        method: "POST",
        data: {
            "_token": token
        },
        success: function (data) {
            console.log('called');
        }
    });
}

function get_property() {
    var token = $('meta[name="csrf-token"]').attr('content');
    var account_id = $("#select_account_id option:selected").val();
    var site_name = $("#select_account_id option:selected").attr('data-id');
    $("#site_name").val(site_name);


    $.ajax({
        url: $("#path_admin").val() + "/getProperty",
        method: "POST",
        data: {
            "_token": token,
            'account_id': account_id
        },
        success: function (data) {
            $('#select_property_id').replaceWith($('#select_property_id').html(data));
            $("#select-property-div").css("display", "");

        }
    });

}

function save_custom_setting(type) {
    var token = $('meta[name="csrf-token"]').attr('content');

    var share_site = $('#share_site').val();
    var share_met = $('#share_met').val();
    var share_metric = $('#share_metric').val();
    var share_dim = $('#share_dim').val();
    var share_dimension = $('#share_dimension').val();
    var password = $('#password').val();

    if ($('#is_password').prop('checked') == true) {
        var is_password = 1;
    } else {
        var is_password = 0;

    }
    console.log(type);
    $.ajax({
        url: $("#path_admin").val() + "/share/setting/" + type,
        method: "POST",
        data: {
            "_token": token,
            'share_site': share_site,
            'share_met': share_met,
            'share_metric': share_metric,
            'share_dim': share_dim,
            'share_dimension': share_dimension,
            'is_password': is_password,
            'password': password
        },
        success: function (data) {
            $("#share_custom_report").modal('hide');
            $(".link").empty();
            $(".link").append(data);
            toastrs('Success', "Setting Save Successfully", 'success');


        }
    });
}

function get_view() {
    var token = $('meta[name="csrf-token"]').attr('content');
    var account_id = $("#select_account_id option:selected").val();
    var property_id = $("#select_property_id option:selected").val();
    var property_name = $("#select_property_id option:selected").attr('data-id');
    $("#property_name").val(property_name);


    $.ajax({
        url: $("#path_admin").val() + "/getView",
        method: "POST",
        data: {
            "_token": token,
            'account_id': account_id,
            'property_id': property_id
        },
        success: function (data) {

            $('#select_view_id').replaceWith($('#select_view_id').html(data));
            $("#select-view-div").css("display", "");

        }
    });

}

function get_view_name() {
    var view_name = $("#select_view_id option:selected").attr('data-id');
    $("#view_name").val(view_name);
    $("#view-name-div").css("display", "");
}
/*end add site*/
/*widget*/
function get_site_id() {
    var site_id = $("#site-list option:selected").val();
    $("#site_id").val(site_id);
}

function save_widget(type) {
    var token = $('meta[name="csrf-token"]').attr('content');
    if (type == "1") { //add 
        var id = $('#add_id').val();
        var title = $('#title').val();
        var site_id = $('#site_id').val();
        var metric_1 = $("#metric_1 option:selected").val();
        var metric_2 = $("#metric_2 option:selected").val();
    }
    if (type == "0") { //edit
        var id = $('#edit_id').val();
        var site_id = $('#s_id').val();
        var title = $('#edit_title').val();
        var metric_1 = $("#edit_metric_1 option:selected").val();
        var metric_2 = $("#edit_metric_2 option:selected").val();
    }
    $.ajax({
        url: $("#path_admin").val() + "/save-widget",
        method: "POST",
        data: {
            "_token": token,
            'id': id,
            'title': title,
            'site_id': site_id,
            'metric_1': metric_1,
            'metric_2': metric_2
        },
        success: function (data) {
            if (type == "1") { //add 

                $('#title').val("");

            }
            if (data.stutas == "1") {
                widget_data(data.id, 1);

                toastrs('Success', data.success, 'success');
                $("#add_widget_modal").modal('hide');
            }
            if (data.stutas == "0") {
                $("#add_widget_modal").modal('hide');
                toastrs('Error', data.error, 'error');
            }
        }
    });

}

function widget_data(wid_id, type) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var site_id = $("#site-list option:selected").val();
    $('#progress').css("display", "");
    var date = $('#date_duration').val();
    if (date == "") {
        var date = moment().subtract(29, 'days').format('MM/DD/YYYY') + " - " + moment().format('MM/DD/YYYY');
    }
    $.ajax({
        url: $("#path_admin").val() + "/widget-data",
        method: "POST",
        data: {
            "_token": token,
            'site_id': site_id,
            'wid_id': wid_id,
            'type': type,
            'date': date
        },
        success: function (data) {
            if (type == "0") {
                $(".widget-card-data").empty();
                $(".widget-card-data").append(data);
            } else {
                var widget_data = document.getElementById('widget_div_' + wid_id);

                if (widget_data) {
                    $(".widget-card-data").find('#widget_div_' + wid_id).replaceWith(data);
                } else {
                    $("#empty-data").remove();
                    $(".widget-card-data").append(data);
                }
            }
            $('#progress').css("display", "none");
        }
    });
}

function edit_widget(id) {
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/edit-widget",
        method: "POST",
        data: {
            "_token": token,
            'id': id
        },
        success: function (data) {
            $("#edit_id").val(data.id);
            $("#s_id").val(data.site_id);
            $("#edit_title").val(data.title);
            $('#edit_metric_1 option[value="' + data.metrics_1 + '"]').prop('selected', true);
            $('#edit_metric_2 option[value="' + data.metrics_2 + '"]').prop('selected', true);

        }
    });


}
$(document).ready(function () {
    $('input[name = "date_duration"]').daterangepicker({
        opens: 'right',
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        dateLimit: {
            'months': 2,
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
    });
});
/*end widget*/
/* site standard*/
function dash_site_detail() {

    var site_id = $("#site-list option:selected").val();

    var site_name = $("#site-list option:selected").attr('data-site');
    $("#current_site").html(site_name);
    $("#current_site").attr("data-siteid", site_id);
    $('#share_site').val(site_id);
    $('.share_link').attr('id', site_id);
    dashboard_load_data();

}

function dashboard_load_data() {

    var siteid = $('#current_site').attr('data-siteid');

    if ($('#usersChart').length) {
        get_chart_data("get_user_data", "dashboard", "year", siteid);
    }
    if ($('#bounceRateChart').length) {
        get_chart_data("bounceRateChart", "dashboard", "year", siteid);
    }
    if ($('#sessionDuration').length) {
        get_chart_data("sessionDuration", "dashboard", "year", siteid);
    }
    if ($('#session_by_device').length) {
        get_chart_data("session_by_device", "dashboard", "year", siteid);
    }
    if ($('#user-timeline-chart-year').length) {
        get_chart_data("user-timeline-chart", "dashboard", "year", siteid);
    }
    if ($('#user-timeline-chart-month').length) {
        get_chart_data("user-timeline-chart", "dashboard", "15daysago", siteid);
    }
    if ($('#user-timeline-chart-week').length) {
        get_chart_data("user-timeline-chart", "dashboard", "week", siteid);
    }
    if ($('#live_users').length > 0) {

        get_live_user(siteid);
    }
    if ($('#active_pages').length > 0) {
        get_active_pages(siteid);
    }
    if ($('.mapcontainer').length) {
        get_chart_data("mapcontainer", "dashboard", "year", siteid);
    }


}

function set_site_detail() {
    var site_id = $("#site-list option:selected").val();
    var site_name = $("#site-list option:selected").attr('data-site');
    $("#current_site").html(site_name);
    $("#current_site").attr("data-siteid", site_id);

    window.location.replace($("#path_admin").val() + "/site-standard/" + site_id);

}
$('#week-chart').click(function () {
    var siteid = $('#current_site').attr('data-siteid');

    get_chart_data("user-timeline-chart", "dashboard", "week", siteid);
})


$('#15daysago-chart').click(function () {

    var siteid = $('#current_site').attr('data-siteid');
    get_chart_data("user-timeline-chart", "dashboard", "15daysago", siteid);
})


$('#year-chart').click(function () {
    var siteid = $('#current_site').attr('data-siteid');

    get_chart_data("user-timeline-chart", "dashboard", "year", siteid);
});


function get_chart_data(type, chart_page, chart_duration, siteid) {

    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: $("#path_admin").val() + "/get-chart",
        method: "POST",
        data: {
            "_token": token,
            'type': type,
            'chart_page': chart_page,
            'chart_duration': chart_duration,
            "siteid": siteid
        },
        success: function (data) {
            if ($(".link").length > 0) {
                if ($(".link").html().length == 0) {
                    $(".link").empty();
                    $(".link").append(data.link);
                }
            }


            if (data.is_success == 1) {
                var label = data.data.labels;
                var datasets = data.data.datasets;
                if (type == "get_user_data") {

                    area_chart(label, datasets, type, "#ff3a6e", "Users")
                }
                if (type == "bounceRateChart") {

                    area_chart(label, datasets, type, "#ffa21d", "Bounce Rate")
                }
                if (type == "sessionDuration") {

                    area_chart(label, datasets, type, "#3ec9d6", "Bounce Rate")
                }
                if (type == "session_by_device") {
                    pie_chart(label, datasets)
                }
                if (type == "user-timeline-chart") {
                    $("#total_Active_Users_" + chart_duration).html(data.total.Active_Users);
                    $("#total_New_Users_" + chart_duration).html(data.total.New_Users);

                    user_timeline_chart(label, datasets, chart_duration);

                }
                if (type == 'mapcontainer') {

                    map_chart(datasets);
                }
            } else {
                var label = data.data.labels;
                var datasets = data.data.datasets;
                if (type == "get_user_data") {

                    area_chart(label, datasets, type, "#ff3a6e", "Users")
                }
                if (type == "bounceRateChart") {

                    area_chart(label, datasets, type, "#ffa21d", "Bounce Rate")
                }
                if (type == "sessionDuration") {

                    area_chart(label, datasets, type, "#3ec9d6", "Bounce Rate")
                }
                if (type == "session_by_device") {
                    pie_chart(label, datasets)
                }
                toastrs('Error', data.message, 'error');

            }
        }
    });
}



// function get_chart_data(type,chart_duration,siteid) {

//         var token= $('meta[name="csrf-token"]').attr('content');
//         // alert(siteid)
//         $.ajax({
//                 url: $("#path_admin").val()+"/get-chart" ,
//                 method:"POST",
//                 data: {"_token": token,'type':type,'chart_duration':chart_duration,"siteid":siteid},
//                 success: function(data) {
//                     if($(".link").length>0)
//                     {
//                       if ($(".link").html().length == 0) {
//                         $(".link").empty();
//                         $(".link").append(data.link);   
//                       }
//                     }

//                    if(data.is_success ==1)
//                    {
//                       var label=data.data.labels;
//                       var datasets=data.data.datasets;
//                       if(type=="get_user_data")
//                       {

//                         area_chart(label,datasets,type,"#ff3a6e","Users")
//                       }
//                       if(type=="bounceRateChart")
//                       {

//                         area_chart(label,datasets,type,"#ffa21d","Bounce Rate")
//                       }
//                       if(type=="sessionDuration")
//                       {

//                         area_chart(label,datasets,type,"#3ec9d6","Bounce Rate")
//                       }
//                       if(type=="session_by_device")
//                       {



//                         pie_chart(label,datasets)
//                       }
//                       if(type=="mapcontainer")
//                       {

//                         map_chart(data.data);
//                       }
//                       if(type=="user-timeline-chart")
//                       {
//                         $("#total_visitor_"+chart_duration).html(data.total.New_Visitor);
//                         $("#total_returning_visitor_"+chart_duration).html(data.total.Returning_Visitor);

//                         user_timeline_chart(label,datasets,chart_duration);

//                       }
//                    }
//                    else
//                    {
//                      console.log("Something went wrong");
//                    }

//                 }
//             });
//     }

function area_chart(label, datasets, type, color, title) {
    var options = {
        chart: {
            height: 250,
            type: 'area',
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        series: [{
            name: title,
            data: datasets
        }],
        xaxis: {
            categories: label,
        },
        colors: [color],
        grid: {
            strokeDashArray: 4,
        },
        legend: {
            show: false,
        },

        yaxis: {

            tickAmount: 7,
            min: 0,
            max: Math.max.apply(Math, datasets),
        }
    };
    if (type == "bounceRateChart") {
        $("#bounceRateChart").empty();

        var chart = new ApexCharts(document.querySelector("#bounceRateChart"), options);
    }
    if (type == "get_user_data") {
        $("#usersChart").empty();

        var chart = new ApexCharts(document.querySelector("#usersChart"), options);
    }
    if (type == "sessionDuration") {
        $("#sessionDuration").empty();

        var chart = new ApexCharts(document.querySelector("#sessionDuration"), options);
    }
    chart.render();
}


function pie_chart(label, datasets) {

    var options = {
        series: datasets,
        chart: {
            // width: 600,
            width: 400,
            type: 'donut',

        },
        stroke: {
            width: 0,
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        total: {
                            showAlways: false,
                            show: false
                        }
                    }
                }
            }
        },
        labels: label,
        dataLabels: {
            dropShadow: {
                blur: 3,
                opacity: 0.8
            }
        },
        fill: {
            type: 'solid',
            opacity: 1,

        },
        states: {
            hover: {
                filter: 'none'
            }
        },


        responsive: [{
            breakpoint: 740,
            options: {
                chart: {
                    width: 400
                },
                legend: {
                    position: 'top'
                }
            }
        }, {
            breakpoint: 480,
            options: {
                chart: {
                    width: 230
                },
                legend: {
                    position: 'top'
                }
            }
        }, {
            breakpoint: 320,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'top'
                }
            }
        }]
    };
    $("#session_by_device").empty();
    var chart = new ApexCharts(document.querySelector("#session_by_device"), options);
    chart.render();
};

function get_live_user(siteid) {


    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/live-user",
        method: "POST",
        data: {
            "_token": token,
            "siteid": siteid
        },
        success: function (data) {
            var parsed = JSON.parse(data);
            if (parsed.is_success) {

                $('#live_users').html(parsed.liveUser);

            }

        }
    });
}

function get_active_pages(siteid) {
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/active-page",
        method: "POST",
        data: {
            "_token": token,
            "siteid": siteid
        },
        success: function (data) {
            //alert(data.is_success); 

            if (data.is_success == 1) {

                var html = '';

                $.each(data.data, function (i, item) {
                    html += '<tr>';
                    html += '<th scope="row">' + (i + 1) + '</th>';
                    html += '<td>' + item.PageUrl + '</td>';
                    html += '<td>' + item.screenPageViews + '</td>';
                    html += '<td>' + item.screenPageViewsPerUser + '%</td>';
                    html += '</tr>';
                });
                $("#active_pages").html(html);

            }

        }
    });
}

function user_timeline_chart(label, datasets, type) {

    var data_arr = JSON.stringify(datasets);
    var parsed = JSON.parse(data_arr);


    var options = {
        series: [{
            name: parsed[0].label,
            data: parsed[0].data
        }, {
            name: parsed[1].label,
            data: parsed[1].data
        }],
        chart: {
            height: 350,
            type: 'area'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {

            categories: label,
            labels: {
                rotate: -45
            },
        },

    };
    if (type == "week") {


        $("#user-timeline-chart-week").empty();
        var chart = new ApexCharts(document.querySelector("#user-timeline-chart-week"), options);

    }
    if (type == "year") {

        $("#user-timeline-chart-year").empty();
        var chart = new ApexCharts(document.querySelector("#user-timeline-chart-year"), options);

    }
    if (type == "15daysago") {

        $("#user-timeline-chart-month").empty();
        var chart = new ApexCharts(document.querySelector("#user-timeline-chart-month"), options);

    }


    chart.render();
};


/*end site standard*/
/*quick view*/
function qick_view_data(siteName, siteid) {

    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/quick-view-data",
        method: "POST",
        data: {
            "_token": token,
            "siteName": siteName,
            "siteid": siteid
        },
        success: function (data) {

            if (data.data.is_success == 1) {
                if ($(".link").length > 0) {
                    if ($(".link").html().length == 0) {
                        $(".link").empty();
                        $(".link").append(data.link);
                    }
                }
                var top_left = data.data.data.top_left;
                var top_right = data.data.data.top_right;
                var bottom_left = data.data.data.bottom_left;
                var bottom_right = data.data.data.bottom_right;

                $.each(top_left, function (k, v) {
                    $('#top_left_id_' + siteid).html(k);
                    $('#top_left_value_' + siteid).html(v);
                });
                $.each(top_right, function (k, v) {
                    $('#top_right_id_' + siteid).html(k);
                    $('#top_right_value_' + siteid).html(v);
                });

                $.each(bottom_left, function (k, v) {
                    $('#bottom_left_id_' + siteid).html(k);
                    $('#bottom_left_value_' + siteid).html(v);
                });
                $.each(bottom_right, function (k, v) {
                    $('#bottom_right_id_' + siteid).html(k);
                    $('#bottom_right_value_' + siteid).html(v);
                });



            }
            if (data.chart.is_success == 1) {

                var site_id = data.site.id;
                var graph = data.site.graph;

                var graph_type = data.site.graph_type;
                var graph_color = data.site.graph_color;
                var labels = data.chart.data.labels;
                var datasets = data.chart.data.datasets;
                quick_chart(graph_type, graph_color, labels, datasets, site_id, graph);
            }
        }
    });
}

function quick_chart(graph_type, graph_color, labels, datasets, site_id, graph) {

    var options = {
        chart: {
            height: 440,
            type: graph_type,
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        series: [{
            name: graph,
            data: datasets
        }],
        xaxis: {
            categories: labels,
        },
        colors: [graph_color],
        grid: {
            strokeDashArray: 4,
        },
        legend: {
            show: false,
        },
        markers: {
            size: 4,
            colors: [graph_color],
            opacity: 0.9,
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        },
        yaxis: {

            tickAmount: 7,
            min: 0,
            max: Math.max.apply(Math, datasets),
        }
    };

    $("#quick_chart_" + site_id).empty();


    var chart = new ApexCharts(document.querySelector("#quick_chart_" + site_id), options);

    chart.render();
}

function edit_quick_view_data(id) {

    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/edit-quick-view-data",
        method: "POST",
        data: {
            "_token": token,
            'id': id
        },
        success: function (data) {

            $("#edit_id").val(data.id);
            $("#quick_view_model_header").html(data.site_name);
            $("#time_frame").val(data.timeframe);
            $("#graph").val(data.graph);
            $("#graph_type").val(data.graph_type);
            $("#top_left").val(data.top_left);
            $("#top_right").val(data.top_right);
            $("#bottom_left").val(data.bottom_left);
            $("#bottom_right").val(data.bottom_right);
            $("#colorPicker").val(data.graph_color);

        }
    });

}

function save_quick_view_data() {
    var token = $('meta[name="csrf-token"]').attr('content');

    event.preventDefault();

    var formValues = $("#quick_view_form")

    $.ajax({
        url: $("#path_admin").val() + "/save-quick-view-data",
        method: "POST",
        data: formValues.serialize(),
        success: function (data) {
            if (data.stutas == "0") {
                toastrs('Error', data.error, 'error');
            }
            if (data.stutas == "1") {
                $("#quick_chart_" + data.id).empty();
                $("#quick_chart_" + data.id).html('<div class="loader " id="progress_' + data.id + '"><div class="spinner text-center" style="align-items: center;"><img height="452px"  src="../public/assets/images/loader.gif"/></div></div>');
                qick_view_data(data.data.site_name, data.data.id);
                toastrs('Success', data.success, 'success');
            }

        }
    });
}
document.querySelectorAll('input[type=color]').forEach(function (picker) {

    var targetLabel = document.querySelector('label[for="' + picker.id + '"]'),
        codeArea = document.createElement('span');

    codeArea.innerHTML = picker.value;
    targetLabel.appendChild(codeArea);

    picker.addEventListener('change', function () {
        codeArea.innerHTML = picker.value;
        targetLabel.appendChild(codeArea);
    });
});
/*end quick view*/

/*manage-site*/

/**/
function edit_site(id) {
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/edit-site/" + id,
        method: "POST",
        data: {
            "_token": token
        },
        success: function (data) {
            $("#edit_id").val(data.id);
            $("#site_name").val(data.site_name);
        }
    });
}

function delete_record(url) {
    var msg = "Do you Sure Want To Delete This ?";
    if (confirm(msg)) {
        window.location.href = url;
    } else {
        window.location.reload();
    }
}
/*end manage-site*/

/*custom chart*/
function get_dimension() {
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/get-dimension",
        method: "POST",
        data: {
            "_token": token
        },
        success: function (data) {

            $('#dimension-list').empty().append(data);
        }

    });
}

function get_custom_cart() {

    var site_id = $("#site-list option:selected").val();
    var metric = $("#metrics-list option:selected").val();
    var dimension = $("#dimension-list option:selected").val();
    if (typeof (site_id) !== "undefined" && site_id != null) {
        if (metric == 0 || dimension == 0) {
            $("#custom_chart").empty();
            $("#custom_chart").html('<div class="col-12 pt-5 text-center"><h5 class=" text-danger">Metrics & Dimension Both Are Required.</h5></div>');
        } else {
            var date_duration = $("#date_duration").val();
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: $("#path_admin").val() + "/custom-chat",
                method: "POST",
                data: {
                    "_token": token,
                    "site_id": site_id,
                    "metric": metric,
                    "dimension": dimension,
                    "chart_duration": date_duration
                },
                success: function (data) {

                    if (data.is_success == 1) {
                        var label = data.data.labels;

                        var data_arr = JSON.stringify(data.data.datasets);
                        var parsed = JSON.parse(data_arr);
                        var datasets = parsed[0]['data'];
                        $("#custom_chart").empty();
                        $("#custom_chart").html('<div class="loader " id="progress"><div class="spinner text-center" style="align-items: center;"><img height="452px"  src="assets/images/loader.gif"/></div></div>');
                        custom_chat(datasets, label)
                    } else {
                        toastrs('Error', data.message, 'error');
                    }
                    var html = '<div class="btn p-0"><a class="btn btn-primary" onclick="' + share_setting("custom") + '" data-bs-toggle="modal"  data-bs-target="#share_custom_report"><span><i class="ti ti-settings"></i></span></a></div>';
                    $(".custom-setting").empty();
                    $(".custom-setting").append(html);
                    $("#download-btn").css("display", "");
                }

            });
        }
    } else {
        $("#custom_chart").empty();
        $("#custom_chart").html('<div class="col-12 pt-5 text-center"><h5 class=" text-danger">Please add site first.</h5></div>');
    }

}

function custom_chat(datasets, label) {


    var colors = [
        '#008FFB',
        '#00E396',
        '#FEB019',
        '#FF4560',
        '#775DD0',
        '#546E7A',
        '#26a69a',
        '#D10CE8'
    ];
    var options = {
        series: [{
            data: datasets
        }],
        chart: {
            height: 350,
            type: 'bar',
            events: {
                click: function (chart, w, e) {
                    // console.log(chart, w, e)
                }
            }
        },
        colors: colors,
        plotOptions: {
            bar: {
                columnWidth: '45%',
                distributed: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: label,
            labels: {
                style: {
                    colors: colors,
                    fontSize: '12px'
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#custom_chart"), options);
    $("#custom_chart").empty();
    chart.render();
}

/*end custom chart*/

function get_channel_data() {

    var segment = '';
    var activeMetrics = 'sessions';
    if ($('.chanel-analytics').hasClass('active')) {
        segment = $('.chanel-analytics.active').attr('data-value');
    }
    var site = $("#site-list option:selected").val();
    if ($('#current_site').length) {
        var site = $('#current_site').attr('data-siteid');
    }
    var actionType = "get_analytics_data";
    var date = $("#date_duration").val();
    if (date == "") {
        var date = moment().subtract(29, 'days').format('MM/DD/YYYY') + " - " + moment().format('MM/DD/YYYY');
    }
    if ($('.card-metrics').hasClass('active')) {
        activeMetrics = $('.card-metrics.active').attr('id');
    }
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/get-channel-data",
        method: "POST",
        data: {
            "_token": token,
            'segment': segment,
            "activeMetrics": activeMetrics,
            'site': site,
            "actionType": actionType,
            "date": date
        },
        success: function (data) {
            if (data.is_success == 1) {
                if ($(".link").length > 0) {
                    if ($(".link").html().length == 0) {
                        $(".link").empty();
                        $(".link").append(data.link);
                    }
                }
                $.each(data.data, function (k, val) {
                    var v = parseFloat(val);
                    if (!Number.isInteger(v)) {
                        v = v.toFixed(2);
                    }
                    $('#metric_data_' + k).html(v);
                });
                arrChartRec = data.chart;

                analytics_chart(activeMetrics, "channel")

            }
        }
    });
}

function analytics_chart(metrics, type) {

    var labels;
    var dataVal;
    var met = arrChartRec[metrics];
    var min;
    var max;
    if (met != undefined && met != '') {
        labels = Object.keys(met);
        dataVal = Object.values(met);
        min = Math.min.apply(Math, dataVal);
        max = Math.max.apply(Math, dataVal);

    } else {
        labels = '';
        dataVal = [0];
        min = 0;
        max = 0;
    }

    var options = {
        chart: {
            height: 440,
            type: 'line',
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        series: [{
            name: metrics,
            data: dataVal
        }],
        xaxis: {
            categories: labels,
        },
        colors: ["#6fd943"],
        grid: {
            strokeDashArray: 4,
        },
        legend: {
            show: false,
        },

        yaxis: {

            tickAmount: 7,
            min: min,
            max: max,
        }
    };
    $("#" + type + "-line-chart-" + metrics).empty();
    var chart = new ApexCharts(document.querySelector("#" + type + "-line-chart-" + metrics), options);
    chart.render();
    var options = {
        chart: {
            height: 440,
            type: 'bar',
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        series: [{
            name: metrics,
            data: dataVal
        }],
        xaxis: {
            categories: labels,
        },
        colors: ["#6fd943"],
        grid: {
            strokeDashArray: 4,
        },
        legend: {
            show: false,
        },
        markers: {
            size: 4,
            colors: ["#6fd943"],
            opacity: 0.9,
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        },
        yaxis: {

            tickAmount: 7,
            min: min,
            max: max,
        }
    };
    $("#" + type + "-bar-chart-" + metrics).empty();
    var chart = new ApexCharts(document.querySelector("#" + type + "-bar-chart-" + metrics), options);
    chart.render();
}

/*end channel*/

/*audience*/
function get_audience_data() {

    var dimension = '';
    var activeCard = 'sessions';
    var date = $('#date_duration').val();

    var site = $("#site-list option:selected").val();
    if ($('#current_site').length) {
        var site = $('#current_site').attr('data-siteid');
    }

    if ($('.audience-analytics').hasClass('active')) {
        dimension = $('.audience-analytics.active').attr('data-value');
    }
    if ($('.audience-card-metrics').hasClass('active')) {
        activeCard = $('.audience-card-metrics.active').attr('id');
    }
    if (date == "") {
        var date = moment().subtract(29, 'days').format('MM/DD/YYYY') + " - " + moment().format('MM/DD/YYYY');
    }
    var actionType = "get_audience_data";
    var token = $('meta[name="csrf-token"]').attr('content');



    $.ajax({
        url: $("#path_admin").val() + "/get-audience-data",
        method: "POST",
        data: {
            "_token": token,
            'dimension': dimension,
            "activeCard": activeCard,
            'site': site,
            "actionType": actionType,
            "date": date
        },
        success: function (data) {
            if (data.is_success == 1) {

                if ($(".link").length > 0) {
                    if ($(".link").html().length == 0) {
                        $(".link").empty();
                        $(".link").append(data.link);
                    }
                }
                $.each(data.data, function (k, val) {
                    var v = parseFloat(val);
                    if (!Number.isInteger(v)) {
                        v = v.toFixed(2);
                    }
                    $('#audinece_metric_data_' + k).html(v);
                });
                arrChartRec = data.chart;


                analytics_chart(activeCard, "audience")
            }
        }
    });

}

/*end audience*/
/*page analytics*/
function get_page_data() {

    var dimension = '';
    var activeCard = 'sessions';
    var date = $('#date_duration').val();

    var site = $("#site-list option:selected").val();
    if ($('#current_site').length) {
        var site = $('#current_site').attr('data-siteid');
    }
    if ($('.page-analytics').hasClass('active')) {
        dimension = $('.page-analytics.active').attr('data-value');
    }
    if ($('.page-card-metrics').hasClass('active')) {
        activeCard = $('.page-card-metrics.active').attr('id');
    }
    if (date == "") {
        var date = moment().subtract(29, 'days').format('MM/DD/YYYY') + " - " + moment().format('MM/DD/YYYY');
    }
    var actionType = "get_page_data";
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: $("#path_admin").val() + "/get-page-data",
        method: "POST",
        data: {
            "_token": token,
            'dimension': dimension,
            "activeCard": activeCard,
            'site': site,
            "actionType": actionType,
            "date": date
        },
        success: function (data) {
            if (data.is_success == 1) {
                if ($(".link").length > 0) {
                    if ($(".link").html().length == 0) {
                        $(".link").empty();
                        $(".link").append(data.link);
                    }
                }
                $.each(data.data, function (k, val) {
                    var v = parseFloat(val);
                    if (!Number.isInteger(v)) {
                        v = v.toFixed(2);
                    }
                    $('#page_metric_data_' + k).html(v);
                });
                arrChartRec = data.chart;


                analytics_chart(activeCard, "page")
            }
        }
    });

}

/*end page analytics*/

/*seo analytics*/
function get_seo_data() {

    var dimension = '';
    var activeCard = 'sessions';
    var date = $('#date_duration').val();

    var site = $("#site-list option:selected").val();
    if ($('#current_site').length) {
        var site = $('#current_site').attr('data-siteid');
    }
    if ($('.seo-analytics').hasClass('active')) {
        dimension = $('.seo-analytics.active').attr('data-value');
    }
    if ($('.seo-card-metrics').hasClass('active')) {
        activeCard = $('.seo-card-metrics.active').attr('id');
    }
    if (date == "") {
        var date = moment().subtract(29, 'days').format('MM/DD/YYYY') + " - " + moment().format('MM/DD/YYYY');
    }
    var actionType = "get_seo_data";
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: $("#path_admin").val() + "/get-seo-data",
        method: "POST",
        data: {
            "_token": token,
            'dimension': dimension,
            "activeCard": activeCard,
            'site': site,
            "actionType": actionType,
            "date": date
        },
        success: function (data) {
            if (data.is_success == 1) {
                if ($(".link").length > 0) {
                    if ($(".link").html().length == 0) {
                        $(".link").empty();
                        $(".link").append(data.link);
                    }
                }
                $.each(data.data, function (k, val) {
                    var v = parseFloat(val);
                    if (!Number.isInteger(v)) {
                        v = v.toFixed(2);
                    }
                    $('#seo_metric_data_' + k).html(v);
                });
                arrChartRec = data.chart;


                analytics_chart(activeCard, "seo")
            }
        }
    });

}
/*end seo analytics*/

$(document).ready(function () {
    $('.show_confirm').click(function (event) {
        var form = $(this).closest("form");
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "This action can not be undone. Do you want to continue?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    });
});

function toastrs(title, message, type) {
    var o, i;
    var icon = '';
    var cls = '';
    if (type == 'success') {
        icon = 'fas fa-check-circle';
        // cls = 'success';
        cls = 'primary';
    } else {
        icon = 'fas fa-times-circle';
        cls = 'danger';
    }

    $.notify({
        icon: icon,
        title: " " + title,
        message: message,
        url: ""
    }, {
        element: "body",
        type: cls,
        allow_dismiss: !0,
        placement: {
            from: 'top',
            align: 'right'
        },
        offset: {
            x: 15,
            y: 15
        },
        spacing: 10,
        z_index: 1080,
        delay: 2500,
        timer: 2000,
        url_target: "_blank",
        mouse_over: !1,
        animate: {
            enter: o,
            exit: i
        },
        // danger
        template: '<div class="toast text-white bg-' + cls +
            ' fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
            '<div class="d-flex">' +
            '<div class="toast-body"> ' + message + ' </div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>' +
            '</div>'
        // template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
    });
}

function share_setting(type) {

    if (type == 'dashboard') {
        var id = $('#current_site').attr('data-siteid');
    }
    if (type == "channel" || type == "audience" || type == "page" || type == "seo" || type == "custom" || type == "standard") {
        var id = $("#site-list option:selected").val();
        $("#share_site").val(id);
    }
    if (type == "custom") {
        var met = $("#metrics-list option:selected").val();

        $("#share_met").val(met);
        var dim = $("#dimension-list option:selected").val();
        $("#share_dim").val(dim);
        var metrics = $("#metrics-list option:selected").attr('data-name');
        $("#share_metric").val(metrics);


        var dimension = $("#dimension-list option:selected").attr('data-name');
        $("#share_dimension").val(dimension);

    }
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/edit/share/setting/" + id + "/" + type,
        method: "POST",
        data: {
            "_token": token
        },
        success: function (data) {
            var parsed = data.json;
            if (type == data.type) {
                if (type != "custom") {
                    $.each(parsed, function (i, item) {
                        if (item == 1) {
                            $("#" + i).prop('checked', true);
                        }
                    });
                } else {
                    $("#share_met").val(met);
                    $("#share_dim").val(dim);
                    if (parsed.is_password == 1) {
                        $("#is_password").prop('checked', true);
                    }
                }

                if (parsed.is_password == 1) {

                    $("#password-box").css("display", "");
                    $("#password").val(parsed.password);

                }

            }



        }
    });
}

function edit_user(id) {
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/edit-user/" + id,
        method: "POST",
        data: {
            "_token": token
        },
        success: function (data) {

            $("#user_id").val(data.id);
            $("#edit_name").val(data.name);
            $("#edit_email").val(data.email);
            $('#edit_role option[value="' + data.role_id + '"]').prop('selected', true);

        }
    });


}

function reset_password(id, title = null) {

    var token = $('meta[name="csrf-token"]').attr('content');
    $("#reset_password .modal-title").html(title);
    $.ajax({
        url: $("#path_admin").val() + "/edit-user/" + id,
        method: "POST",
        data: {
            "_token": token
        },
        success: function (data) {

            $("#resete_id").val(data.id);


        }
    });
}

function quickview_share_setting() {


    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/quickview/edit/share/setting/",
        method: "POST",
        data: {
            "_token": token
        },
        success: function (data) {
            var parsed = data.allowed_setting;

            var ids = parsed.allowed_site_id;
            var splitted = ids.split(",");
            $.each(splitted, function (i, item) {
                $("#site_id-" + item).prop('checked', true);
            });
            if (parsed.is_password == 1) {
                $("#is_password").prop('checked', true);

                $("#password-box").css("display", "");
                $("#password").val(parsed.password);

            }
            $(".link").empty();
            $(".link").append(data.link);
        }

    });
}

function password_status() {

    if ($("#is_password").is(":checked")) {
        $("#password-box").css("display", "");
    } else {
        $("#password-box").css("display", "none");
        $("#password").val(null);

        // checkbox is not checked -> do something different
    }
}

function quick_link_password_status() {

    if ($("#is_password").is(":checked")) {
        $("#password-box").css("display", "");
    } else {
        $("#password-box").css("display", "none");
        $("#password").val(null);

        // checkbox is not checked -> do something different
    }
}

function get_custom_share_chart() {
    var date = $("#date_duration").val();
    if (date == "") {
        var date = moment().subtract(29, 'days').format('MM/DD/YYYY') + " - " + moment().format('MM/DD/YYYY');
    }
    var id = $('#current_site').attr('data-siteid');
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: $("#path_admin").val() + "/custom-share-chart",
        method: "POST",
        data: {
            "_token": token,
            'id': id,
            'chart_duration': date
        },
        success: function (data) {
            if (data.is_success == 1) {
                var label = data.data.labels;

                var data_arr = JSON.stringify(data.data.datasets);
                var parsed = JSON.parse(data_arr);
                var datasets = parsed[0]['data'];
                $("#custom_chart").empty();
                $("#custom_chart").html('<div class="loader " id="progress"><div class="spinner text-center" style="align-items: center;"><img height="452px"  src="assets/images/loader.gif"/></div></div>');
                custom_chat(datasets, label)
            }
        }
    });
}

function saveAsPDF(view) {
    var filename = $('#current_site').html();
    if (typeof (filename) === "undefined") {
        filename = $("#site-list option:selected").html();
    }


    var element = document.getElementById('printableArea');
    var opt = {
        margin: 0.3,
        filename: filename + ' ' + view,
        image: {
            type: 'jpeg',
            quality: 1
        },
        html2canvas: {
            scale: 4,
            dpi: 72,
            letterRendering: true
        },
        jsPDF: {
            unit: 'in',
            format: 'A2'
        }
    };
    html2pdf().set(opt).from(element).save();
}

$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {

    var title1 = $(this).data("title");
    var title2 = $(this).data("bs-original-title");
    var title = (title1 != undefined) ? title1 : title2;
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            // daterange_set();
            taskCheckbox();
            common_bind("#commonModal");
            commonLoader();
            select2();
            validation();

            if ($(".d_clock").length > 0) {
                // alert('hiii')
                $($(".d_clock")).each(function (index, element) {
                    var id = $(element).attr('id');


                    document.querySelector("#" + id).flatpickr({
                        enableTime: true,
                        noCalendar: true,
                    });

                });
            }
            // document.querySelector("#pc-timepicker-1").flatpickr({
            //     enableTime: true,
            //     noCalendar: true,
            // });


            if ($(".d_week").length > 0) {
                $($(".d_week")).each(function (index, element) {
                    var id = $(element).attr('id');

                    (function () {
                        const d_week = new Datepicker(document.querySelector('#' + id), {
                            buttonClass: 'btn',
                            format: 'yyyy-mm-dd',
                        });
                    })();

                });
            }

            if ($(".d_filter").length > 0) {
                $($(".d_filter")).each(function (index, element) {
                    var id = $(element).attr('id');

                    (function () {
                        const d_week = new Datepicker(document.querySelector('#' + id), {
                            buttonClass: 'btn',
                            format: 'yyyy-mm',
                        });
                    })();

                });
            }

        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

});
