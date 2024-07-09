/* global $ */
import toastr from 'toastr';

window.toastr = toastr;
$(document).ready(function () {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-bottom-left",
        "onclick": null,
        "showDuration": "0",
        "hideDuration": "0",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "show",
        "hideMethod": "fadeOut",
        "tapToDismiss" : false
    };

    var table = $('.dataTables-example').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
        "columnDefs": [{
            "targets": 'no-sort',
            "orderable": false,
        }],
        fnDrawCallback: function (oSettings) {
            $("[data-toggle='confirmation']").confirmation({singleton: true, popout: true});
        }

    });

    $('#pp-selector').on('change', function (event) {
        window.location.href = $(event.target).val();
    });

    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        // other options
    });

    $('#details_btn').on('click', function (e) {
        e.preventDefault();
        var request_id = $(this).data('request_id');
        var route = $(this).data('hreff');
        console.log(request_id);
        $.ajax({
            type: "GET",
            data: {},
            url: route,
            success: function (data) {
                $('#device_details_parent_' + request_id).html(data);

            },
            error: function (data) {
                if (data.status == 401 || data.status == 404) {
                    window.location = data.getResponseHeader('Location')
                }
                else {
                    var json = $.parseJSON(data.responseText);
                    var error = '';
                    $.each(json, function (k, v) {
                        error += v;
                    });
                    toastr.error('', error);
                }
            }
        });
    });

    $('body').on('confirmed.bs.confirmation', '.delete-ajax', function (e) {
        e.preventDefault();
        var route = $(this).data('hreff');
        var token = $(this).data('token');
        var target_id = $(this).data('id');
        var button = $(this);
        var to_generate = $(this).data('to_generate');
        var doNotReload = $(this).data('do-not-reload');
        $.ajax({
            type: "POST",
            data: {_method: 'delete', _token: token, id: target_id},
            url: route,
            success: function (data) {
                if (data.msg == device_assigned) {
                    swal({
                            title: device_assigned,
                            text: device_assigned,
                            type: "warning",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: false,
                            allowEscapeKey: true,
                            closeOnCancel: false,
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $.ajax({
                                    type: "POST",
                                    url: data.route,//$(this).attr('deactivate'),
                                    data: {_method: 'delete', _token: data.token, id: target_id},
                                    success: function (msg) {
                                        toastr.success("", msg);
                                        setTimeout(function () {
                                            location.reload(1);
                                        }, 2000);
                                    },
                                    error: function (newMsg) {
                                        swal(cancelled, newMsg, "error");

                                    }
                                });
                            } else {

                                swal(cancelled, cancelledText, "error");
                            }
                        });
                } else if (data.msg == device_move_notify) {
                    swal({
                            title: device_move_notify,
                            text: device_move_notify,
                            type: "warning",
                            showCancelButton: false,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: false,
                            allowEscapeKey: true,
                            closeOnCancel: false,
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $.ajax({
                                    type: "POST",
                                    url: data.route,//$(this).attr('deactivate'),
                                    data: {_method: 'delete', _token: data.token, id: target_id},
                                    success: function (msg) {
                                        toastr.success("", msg);
                                        setTimeout(function () {
                                            location.reload(1);
                                        }, 2000);
                                    },
                                    error: function (newMsg) {
                                        swal(cancelled, newMsg, "error");

                                    }
                                });
                            } else {

                                swal(cancelled, cancelledText, "error");
                            }
                        });
                } else {
                    if (to_generate) {
                        toastr.success('', data);
                    } else {
                        $('#toRemove-' + target_id).remove();
                        if (data == 'Activated') {
                            toastr.success('', data);
                        } else if (data == 'Activation Code Generated') {
                            toastr.success('', data);
                        } else {
                            toastr.error('', data);
                        }
                    }
                    if (doNotReload == 'yes') {
                        //alert(doNotReload);
                    } else {
                        setTimeout(function () {
                            location.reload(1);
                        }, 2000);
                    }
                }
            },
            error: function (data) {
                if (data.status == 401 || data.status == 404) {
                    window.location = data.getResponseHeader('Location')
                }
                else {
                    var json = $.parseJSON(data.responseText);
                    var error = '';
                    $.each(json, function (k, v) {
                        error += v;
                    });
                    toastr.error('', error);
                }
            }
        });
    });

    $('body').on('confirmed.bs.confirmation', '.post-ajax', function (e) {
        e.preventDefault();
        var route = $(this).data('hreff');
        var token = $(this).data('token');
        var target_id = $(this).data('id');
        var doNotReload = $(this).data('do-not-reload');

        $.ajax({
            type: "POST",
            data: {_token: token, id: target_id},
            url: route,
            success: function (data) {
                toastr.success('', data);

                if (doNotReload !== 'yes') {
                    setTimeout(function () {
                        location.reload(1);
                    }, 2000);
                }
            },
            error: function (data) {
                if (data.status === 401 || data.status === 404) {
                    window.location = data.getResponseHeader('Location')
                }
                else {
                    toastr.error('', data.responseText);
                }
            }
        });
    });

    $('#modal-form').on('show.bs.modal', function (e) {
        var route = $(e.relatedTarget).data('href');

        $.ajax({
            type: "get",
            data: {},
            url: route,
            success: function (data) {
                $("#modal-form .modal-content").html(data);
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });

                if ($("#address").length) {
                    window.addressHandler($("#address"), $("#city"), $("#zip_code"), $("#country"), $("#latitude"), $("#longitude"));
                }

                $("#select_customer_type").on("change", function (e) {
                    var selected = $('#select_customer_type').val();
                    var transPlaceholder = $('#select_customer').data('trans-placeholder')
                    $('#otherDist').on('ifChecked', function(event){
                        $('#select_customer')
                            .find('option')
                            .remove()
                            .end()
                            .append($('<option>', {value: '0', text : transPlaceholder, address : ''}))
                            .attr('disabled','disabled').trigger('chosen:updated');

                        var route = $(this).data('url');
                        var token = $(this).data('token');
                        var target_id = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            url: route,
                            data: {
                                _token: token,
                                id: target_id,
                                others:"yes"
                            },
                            success: function (msg) {
                                $.each(msg, function (i, item) {
                                    $('#select_customer').append($('<option>', {
                                        value: msg[i]['id'],
                                        text : msg[i]['company'],
                                        address : msg[i]['address']
                                    }));
                                });
                            },
                            error: function (msg) {
                                alert("Connection error");
                            },
                            complete: function (){
                                $('#select_customer').removeAttr('disabled').trigger('chosen:updated');
                            }
                        });

                    });
                    $('#otherDist').on('ifUnchecked', function(event){
                        $('#select_customer')
                            .find('option')
                            .remove()
                            .end()
                            .append($('<option>', {value: '0', text : transPlaceholder, address : ''}))
                            .attr('disabled','disabled').trigger('chosen:updated');

                        var route = $(this).data('url');
                        var token = $(this).data('token');
                        var target_id = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            url: route,
                            data: {
                                _token: token,
                                id: target_id,
                                others:"no"
                            },
                            success: function (msg) {
                                $.each(msg, function (i, item) {
                                    $('#select_customer').append($('<option>', {
                                        value: msg[i]['id'],
                                        text : msg[i]['company'],
                                        address : msg[i]['address']
                                    }));
                                });
                            },
                            error: function (msg) {
                                alert("Connection error");
                            },
                            complete: function (){
                                $('#select_customer').removeAttr('disabled').trigger('chosen:updated');
                            }
                        });

                    });
                    /*if (chckValue) {
                            $('#select_customer')
                                .find('option')
                                .remove()
                                .end();
                        }*/

                    if (selected == 1) {
                        $("#keep_data").hide();
                        $("#customerExisting").hide();
                        $("#customerRegister").show();
                        $("#overwrite_warranty").hide();

                    } else if (selected == 2) {
                        $("#keep_data").hide();
                        $("#customerRegister").hide();
                        $("#customerExisting").show();
                        $("#overwrite_warranty").hide();


                    } else if (selected == 3) {
                        $("#keep_data").hide();
                        $("#customerRegister").hide();
                        $("#customerExisting").hide();
                        $("#overwrite_warranty").hide();

                        console.log($("#keep_data").val());
                        /*if ($("#keep_data").val() == ){

                        }*/
                    } else {
                        $("#keep_data").hide();
                        $("#customerRegister").hide();
                    }
                });

                $("#formMove").on("submit", function (e) {
                    e.preventDefault();
                    var btn = $("[type='submit']").button('loading');
                    var selected = $('#select_customer_type').val();
                    var radioValue = $('#keep_data:checked').val();
                    var overwrite_warranty_new = $('#overwrite_warranty:checked').val();
                    var oldCustomer = $('#oldCustomer').val();
                    var newCustomer = $('option:selected', '#select_customer').text();
                    var newCustomerAddress =  $('option:selected', '#select_customer').attr('address');// $('#select_customer').getAttribute('address');
                    var action = $(this).attr('action');
                    var customer = $('#select_customer').val();
                    var _token = $("[name='_token']").val();//$('#_token').val();

                    if (selected == 2){
                        if (customer == 0){
                            toastr.error('', select_new_customer_validation);
                            btn.button('reset');
                        }else {
                            swal({
                                    title: confirmMoveTitle,
                                    text: "<div class='row'>" +
                                        "<div class='row'>" +
                                        "<div class='col-xs-12'>" +
                                        confirmFirstLine + " <strong> " + oldCustomer + "</strong><br/></div></div>" +
                                        "<div class='row'><div class='col-xs-12'>" +
                                        confirmSecondLine + " <strong>< " + newCustomer + " , " + newCustomerAddress + " ></strong><br/></div>" +
                                        "</div></div>",
                                    type: "warning",
                                    showCancelButton: true,
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: true,
                                    allowEscapeKey: true,
                                    html: true,
                                    closeOnCancel: false,
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        $.ajax({
                                            type: "POST",
                                            url: action,
                                            data: {
                                                _token: _token,
                                                customerType: selected,
                                                customer: customer,
                                                overwrite_warranty_new:overwrite_warranty_new
                                            },
                                            success: function (newMsg) {
                                                toastr.success('', newMsg);

                                                setTimeout(function () {
                                                    location.reload(1);
                                                }, 1000);

                                            },
                                            error: function (newMsg) {
                                                swal.close();

                                                if (newMsg.status == 401 || newMsg.status == 404) {
                                                    window.location = newMsg.getResponseHeader('Location')
                                                }
                                                $("#modal-form .modal-content .form-body .alert-danger").fadeOut(500);
                                                var json = $.parseJSON(newMsg.responseText);
                                                var error = '<div class="alert alert-block alert-danger fade in"><button type="button" class="close" data-dismiss="alert"></button> <p>';
                                                $.each(json, function (k, v) {
                                                    error += v + "</p>";
                                                });
                                                $("#modal-form .modal-content .form-body").prepend(error + '</div>');
                                                btn.button('reset');

                                            }

                                        });
                                    } else {

                                        swal(cancelled, cancelledText, "error");
                                        $('#modal-form').modal('toggle');

                                    }
                                });
                        }
                    }else {

                        $.ajax({
                            type: "POST",
                            url: $(this).attr('action'),
                            data: new FormData(this),
                            processData: false,
                            contentType: false,
                            success: function (msg) {
                                toastr.success('', msg);
                                setTimeout(function () {
                                    location.reload(1);
                                }, 2000);
                            },
                            error: function (msg) {
                                if (msg.status == 401 || msg.status == 404) {
                                    window.location = msg.getResponseHeader('Location')
                                }
                                $("#modal-form .modal-content .form-body .alert-danger").fadeOut(500);
                                var json = $.parseJSON(msg.responseText);
                                var error = '<div class="alert alert-block alert-danger fade in"><button type="button" class="close" data-dismiss="alert"></button> <p>';
                                $.each(json, function (k, v) {
                                    error += v + "</p>";
                                });
                                $("#modal-form .modal-content .form-body").prepend(error + '</div>');
                                btn.button('reset');
                            }
                        });
                    }

                });

                $("#formTested").on("submit", function (e) {
                    e.preventDefault();
                    var btn = $("[type='submit']").button('loading');
                    $.ajax({
                        type: "POST",
                        url: $(this).attr('action'),
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function (msg) {
                            /*toastr.success('', msg);
                             setTimeout(function () {
                             location.reload(1);
                             }, 2000);*/
                            $('#modal-form').modal('toggle');

                            swal({
                                    title: checkAlertFirstLine,
                                    text: checkAlertSecondLine,
                                    type: "warning",
                                    showCancelButton: true,
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: false,
                                    allowEscapeKey: true,
                                    closeOnCancel: false,
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        swal({
                                                title: checkAlertFirstLineSecondLevel,
                                                text: "<div class='row'>" +
                                                    "<div class=\"alert alert-danger\">" +
                                                    "<div class='row'>" +
                                                    "<div class='col-xs-5'>" +
                                                    existingSerial + "</div><div class='col-xs-7'><strong> " + msg.old_serial + "</strong><br/></div>" +
                                                    "<div class='col-xs-5'>" +
                                                    newSerial + "</div><div class='col-xs-7'><strong> " + msg.serial + "</strong><br/></div>" +
                                                    "<div class='col-xs-5'>" +
                                                    oldType + "</div><div class='col-xs-7'><strong> " + msg.old_device_type + "</strong><br/></div>" +
                                                    "<div class='col-xs-5'>" +
                                                    newType + "</div><div class='col-xs-7'><strong> " + msg.device_type + "</strong><br/></div>" +
                                                    "</div></div>" +
                                                    "</div>",
                                                type: "warning",
                                                showCancelButton: true,
                                                closeOnConfirm: false,
                                                showLoaderOnConfirm: true,
                                                allowEscapeKey: true,
                                                html: true,
                                                closeOnCancel: false,
                                            },
                                            function (isConfirm) {
                                                if (isConfirm) {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: updateSecondLevel,
                                                        data: {
                                                            _token: msg._token,
                                                            id: msg.id,
                                                            serial: msg.serial,
                                                            old_serial: msg.old_serial
                                                        },
                                                        success: function (newMsg) {
                                                            setTimeout(function () {
                                                                swal({
                                                                    title: newMsg,
                                                                    text: newMsg
                                                                }, function () {
                                                                    setTimeout(function () {
                                                                        location.reload(1);
                                                                    }, 1000);
                                                                });
                                                            }, 1000);
                                                        },
                                                        error: function (newMsg) {
                                                            swal(cancelled, newMsg, "error");

                                                        }

                                                    });
                                                } else {

                                                    swal(cancelled, cancelledText, "error");
                                                }
                                            });
                                    } else {
                                        swal(cancelled, cancelledText, "error");
                                    }
                                });
                        },
                        error: function (msg) {
                            if (msg.status == 401 || msg.status == 404) {
                                window.location = msg.getResponseHeader('Location')
                            }
                            $("#modal-form .modal-content .form-body .alert-danger").fadeOut(500);
                            var json = $.parseJSON(msg.responseText);
                            var error = '<div class="alert alert-block alert-danger fade in"><button type="button" class="close" data-dismiss="alert"></button> <p>';
                            $.each(json, function (k, v) {
                                error += v + "</p>";
                            });
                            $("#modal-form .modal-content .form-body").prepend(error + '</div>');
                            btn.button('reset');
                        }
                    });
                });

                $("#formTest").on("submit", function (e) {
                    var btn = $("[type='submit']").button('loading');
                    var doNotReload = $(this).data('do-not-reload');

                    e.preventDefault();

                    $.ajax({
                        type: "POST",
                        url: $(this).attr('action'),
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function (msg) {
                            if (doNotReload === 'yes') {
                                toastr.options.onHidden  = function () {
                                    location.reload(1);
                                }
                            } else {
                                setTimeout(function () {
                                    location.reload(1);
                                }, 2000);
                            }

                            toastr.success('', msg);
                        },
                        error: function (msg) {
                            if (msg.status == 401 || msg.status == 404) {
                                window.location = msg.getResponseHeader('Location')
                            }
                            $("#modal-form .modal-content .form-body .alert-danger").fadeOut(500);
                            var json = $.parseJSON(msg.responseText);
                            var error = '<div class="alert alert-block alert-danger fade in"><button type="button" class="close" data-dismiss="alert"></button> <p>';
                            $.each(json, function (k, v) {
                                error += v + "</p>";
                            });
                            $("#modal-form .modal-content .form-body").prepend(error + '</div>');
                            btn.button('reset');
                        }
                    });
                });
            }
            , error: function (data) {
                if (data.status === 401 || data.status === 404) {
                    window.location = data.getResponseHeader('Location')
                }
            }
        });
    });

    $('[data-tooltip="tooltip"]').tooltip();

    $(document)
        .on("click", "button.toggle-modal-code-generation-content", function (e) {
            var modalForm = $(e.target).parents("#modal-form");

            $(modalForm)
                .find(".modal-header, .modal-body, .modal-footer")
                .toggleClass("hidden");

            $(modalForm)
                .find("fieldset")
                .each(function (i, el) {
                    $(el).prop({"disabled": !$(el).is(":disabled")});
                });
        })
        .on("change", "input.guarantee-agreed", function (e) {
            $(e.target)
                .parents("#modal-form")
                .find("button.continue")
                .prop({"disabled": !$(e.target).is(":checked")});
        });
});
