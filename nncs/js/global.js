var winWidth = $(window).width();
var winHeight = $(window).height();
var myWindow = null;
var elmArr = [];
var show = false;

function _checkConnection() {
    if (navigator.onLine) {
        $("#popup").html("<b>Internet Connection Is OK.</b>");
        setTimeout(hidePopup, 4000);
    } else {
        $("#popup").html('<b>No Internet Connection.</b>').show();
        return false;
    }
    setTimeout(_checkConnection, 4000);
}
_checkConnection();

$(document).ready(function() {
    bodyPaddingTop();
    bodyPanelHeight();
    setTimeout(hide_alert, 8000);
    setTimeout(hide_ajax_message, 8000);

    $(document).on("click", "#pagination li a", function(e) {
        showLoader("Processing...", true);
        var _form = $("#frmSearch");
        var _srcUrl = $(this).attr('href');

        $.ajax({
            type: "POST",
            url: _srcUrl,
            data: _form.serialize(),
            success: function(res) {
                showLoader("", false);
                $("#ajaxContent").html('');
                $("#ajaxContent").html(res);
            }
        });
        e.preventDefault();
    });

    $(document).on("click", "#clear_from", function(e) {
        var _form = $("#frmSearch");
        _form[0].reset();
        $("#search").trigger("click");
        e.preventDefault();
    });

    $(document).on("change", "#itemCount", function(e) {
        $("#search").trigger("click");
    });

    $(document).on("change", "#checkAll", function() {
        var $this = $(this);
        var element = $("#deleteForm input[type='checkbox']");
        if ($this.is(":checked")) {
            $(element).prop("checked", true);
            $(element).closest('tr').addClass('bg-danger');
            enable("#admin_del_btn");
            enable("#admin_reset_btn");
            enable(".crud_btn");
            $("#btnArea").slideDown(200);
        } else {
            $(element).prop("checked", false);
            $(element).closest('tr').removeClass('bg-danger');
            disable("#admin_del_btn");
            disable("#admin_reset_btn");
            disable(".crud_btn");
            $("#btnArea").slideUp(200);
        }
    });

    $(document).on("change", "#deleteForm input[type='checkbox']", function() {
        if ($(this).is(":checked")) {
            $(this).closest('tr').addClass('bg-danger');
        } else {
            $(this).closest('tr').removeClass('bg-danger');
        }

        if ($("#deleteForm .check:checked").length > 0) {
            $("#checkAll").prop("checked", true);
            enable("#admin_del_btn");
            enable("#admin_reset_btn");
            enable(".crud_btn");
            $("#btnArea").slideDown(200);
        } else {
            $("#checkAll").prop("checked", false);
            disable("#admin_del_btn");
            disable("#admin_reset_btn");
            disable(".crud_btn");
            $("#btnArea").slideUp(200);
        }
    });

    $(document).on('click', '.admin_nav_toggle', function() {
        if ($('#admin_nav').css('left') == '-2000px') {
            $('#admin_nav').animate({left: 0}, 300);
        } else {
            $('#admin_nav').animate({left: -2000}, 300);
        }
    });
});

$(window).resize(function() {
    bodyPaddingTop();
    bodyPanelHeight();
});

function bodyPaddingTop() {
    return $('body').css('padding-top', ($('#header_nav').height() + $("#breadcrumbBar").height() + 24));
}

function bodyPanelHeight() {
    var unitHeight = $('#header_nav').height() + $("#breadcrumbBar").height() + $('#footerPanel').height() + 24;
    return $('#bodyPanel').css('min-height', (winHeight - unitHeight));
}

(function($) {
    $.fn.showAjaxMessage = function(options) {
        var _handler = $(this);

        var settings = {
            html: 'Undefined',
            type: 'alert'
        };

        settings = $.extend(settings, options);

        if (settings.type == 'success') {
            _handler
                    .removeClass('alert-danger')
                    .removeClass('alert-info')
                    .removeClass('alert-warning')
                    .addClass('alert-success')
                    .html(settings.html)
                    .show();
        } else if (settings.type == 'error') {
            _handler
                    .removeClass('alert-success')
                    .removeClass('alert-info')
                    .removeClass('alert-warning')
                    .addClass('alert-danger')
                    .html(settings.html)
                    .show();
        } else if (settings.type == 'warning') {
            _handler
                    .removeClass('alert-success')
                    .removeClass('alert-info')
                    .removeClass('alert-danger')
                    .addClass('alert-warning')
                    .html(settings.html)
                    .show();
        } else {
            _handler
                    .removeClass('alert-danger')
                    .removeClass('alert-success')
                    .removeClass('alert-warning')
                    .addClass('alert-info')
                    .html(settings.html)
                    .show();
        }
        setTimeout(hide_ajax_message, 6000);
    };

    $.fn.callAjax = function(options) {
        var _handler = $(this);

        var settings = {
            url: '',
            data: {},
            dataType: 'json',
            crossDomain: false,
            global: true,
            processData: true,
            type: 'POST',
            respTo: $('<div>'),
            beforeSend: function() {
                settings.respTo.hide();
                _handler.show();
            },
            done: function(data, textStatus, jqXHR) {
                _handler.hide();
                settings.respTo.html(JSON.stringify(data)).show();
            },
            fail: function(jqXHR, textStatus, errorThrown) {
                _handler.hide();
                settings.respTo.html('Error: while processing the request <br/>' + errorThrown).show();
            },
            always: function(data, textStatus, jqXHR) {
            }
        };

        settings = $.extend(settings, options);

        $.ajaxSetup({
            url: settings.url,
            dataType: settings.dataType,
            crossDomain: settings.crossDomain,
            global: settings.global,
            processData: settings.processData,
            type: settings.type,
            beforeSend: settings.beforeSend
        });

        var jqxhr = $.ajax({
            data: settings.data
        })
                .done(settings.done)
                .fail(settings.fail)
                .always(settings.always);

        return jqxhr;
    };

    $.fn.priceField = function() {
        $(this).keydown(function(e) {
            var val = $(this).val();
            var code = (e.keyCode ? e.keyCode : e.which);
            var nums = ((code >= 96) && (code <= 105)) || ((code >= 48) && (code <= 57)); //keypad || regular
            var backspace = (code == 8);
            var specialkey = (e.metaKey || e.altKey || e.shiftKey);
            var arrowkey = ((code >= 37) && (code <= 40));
            var Fkey = ((code >= 112) && (code <= 123));
            var decimal = ((code == 110 || code == 190) && val.indexOf('.') == -1);

            // UGLY!!
            var misckey = (code == 9) || (code == 144) || (code == 145) || (code == 45) || (code == 46) || (code == 33) || (code == 34) || (code == 35) || (code == 36) || (code == 19) || (code == 20) || (code == 92) || (code == 93) || (code == 27);

            var properKey = (nums || decimal || backspace || specialkey || arrowkey || Fkey || misckey);
            var properFormatting = backspace || specialkey || arrowkey || Fkey || misckey || ((val.indexOf('.') == -1) || (val.length - val.indexOf('.') < 3) || ($(this).getCaret() < val.length - 2));

            if (!(properKey && properFormatting)) {
                return false;
            }
        });

        $(this).blur(function() {
            var val = $(this).val();
            if (val === '') {
                $(this).val('0.00');
            } else if (val.indexOf('.') == -1) {
                $(this).val(val + '.00');
            } else if (val.length - val.indexOf('.') == 1) {
                $(this).val(val + '00');
            } else if (val.length - val.indexOf('.') == 2) {
                $(this).val(val + '0');
            }
        });

        return $(this);
    };
})(jQuery);

function filterAjaxResponse(d) {
    if (typeof (d) == "string" && d != '') {
        d = JSON.stringify(d);
    }

    // check if the response ask login
    if (d.authorized != null && d.authorized != 'undefined' && d.authorized == false) {
        redirectTo(baseUrl + 'login');
        return false;
    }

    return d;
}

function get_sum(elm, target) {
    var tval = document.getElementsByClassName(elm), sum = 0, i;
    for (i = tval.length; i--; )
        if (tval[i].value)
            sum += parseInt(tval[i].value, 10);
    return document.getElementById(target).value = sum;
}

function get_loan_sum(elm, target) {
    var tval = document.getElementsByClassName(elm);
    var sum = 0, i = 0;
    for (i = tval.length; i--; ) {
        //var _ftval = parseFloat(tval[i].value).toFixed(2);
        if (tval[i].value)
            //sum += tval[i].value;
            sum += parseInt(tval[i].value, 10);
    }
    //return document.getElementById(target).value = parseFloat(sum).toFixed(2);
    return document.getElementById(target).value = sum;
}

function update_sum(elm, target, total) {
    var sum = 0;
    var eval = !isNaN($(elm).val()) ? $(elm).val() : 0;
    var tval = !isNaN($(target).val()) ? $(target).val() : 0;
    sum += parseInt(eval + tval);
    return document.getElementById(total).value = sum;
}

function interest_count(day, amount) {
    return (day * amount);
}

function per_day_interest_count(amount, rate, day) {
    return (rate * amount) / (day * 100);
}

function add(a, b) {
    return a + b;
}

function pr(arr) {
    console.log(arr);
}

function showLoader(text, show) {
    if (show == true) {
        $("#cover").show();
        $('#superLoader').show();
        $('#superLoaderText').html(text);
    } else {
        $("#cover").hide();
        $('#superLoader').hide();
        $('#superLoaderText').html(text);
    }
}

function DoNav(theUrl) {
    redirectTo(theUrl);
}

function redirectTo(url) {
    if (url !== '') {
        window.location = url;
    }
}

function toggleCheckboxes(element) {
    if ($(element).is(":checked")) {
        $(element).prop("checked", true);
        enable("#admin_del_btn");
    } else {
        $(element).prop("checked", false);
        disable("#admin_del_btn");
    }
}

function disable(element) {
    return $(element).attr('disabled', 'disabled');
}

function enable(element) {
    return $(element).removeAttr('disabled');
}

function ChangeColor(tableRow, highLight) {
    if (highLight) {
        tableRow.style.backgroundColor = '#F9F9F9';
        tableRow.style.cursor = 'pointer';
    } else {
        tableRow.style.backgroundColor = '';
    }
}

function reset_index() {
    showLoader("", false);
    disable("#admin_del_btn");
    $("#checkAll").prop("checked", false);
    $("#deleteForm input[type='checkbox']").prop("checked", false);
    $("tr").removeClass("bg-danger");
}

function showMessage(msg) {
    $("#popup").html('');
    $("#popup").html(msg).show();
}

function hideMessage() {
    $("#popup").html('').hide();
}

function hidePopup() {
    return $("#popup").fadeOut(400);
}

function hide_ajax_message() {
    return $("#ajaxMessage, #ajaxMessageModal, #ajaxModalMessage").slideUp(200);
}

function hide_alert() {
    return $(".alert-dismissible").slideUp(200);
}

function limitWords(textToLimit, wordLimit) {
    var finalText = "";

    var text2 = textToLimit.replace(/\s+/g, ' ');

    var text3 = text2.split(' ');

    var numberOfWords = text3.length;

    var i = 0;

    if (numberOfWords > wordLimit)
    {
        for (i = 0; i < wordLimit; i++)
            finalText = finalText + " " + text3[i] + " ";

        return finalText + "...";
    }
    else
        return textToLimit;
}

function showCatgoryRows(elment) {
    if ($(elment).val() == "All" || $(elment).val() == "") {
        $(".pro_cat").show();
    } else {
        $(".pro_cat").hide();
        $(".pro_cat_" + $(elment).val()).show();
    }
}

function printDiv(divName) {
    var bodyPadTop = document.body.style.paddingTop;
    var originalContents = document.body.innerHTML;
    var printContents = document.getElementById(divName).innerHTML;

    document.body.innerHTML = printContents;
    window.print();

    document.body.innerHTML = originalContents;
    document.body.style.paddingTop = bodyPadTop;
}

function printElem(elem) {
    createPopup($(elem).html());
}

function createPopup(data) {
    var mywindow = window.open('', 'my div', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,height=600, width=1024, left=150, top=30');
    mywindow.document.write('<html><head><title></title>');
    /*optional stylesheet*/
    mywindow.document.write('<link rel="stylesheet" href="' + baseUrl + '/css/print.css" type="text/css">');
    mywindow.document.write('</head><body><div class="media_print_body"></div>');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    //mywindow.document.close(); // necessary for IE >= 10
    //mywindow.focus(); // necessary for IE >= 10
    mywindow.print();
    //mywindow.close();

    return true;
}

function openWindow() {
    window.open('', 'Hello test', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,scrollbars=yes,height=550,width=1024,left=150,top=50');
    window.focus();
}

function openConfirm() {
    window.confirm("Press a button!");
}

function child_open(message) {
    var top = (screen.height) ? (screen.height - 120) / 2 : 0;
    var left = (screen.width) ? (screen.width - 300) / 2 : 0;

    myWindow = window.open("", "_blank", "directories=no, status=no, menubar=no, scrollbars=no, resizable=no,width=300, height=120,top=" + top + ",left=" + left);
    myWindow.document.write(message);
}

function parent_disable() {
    if (myWindow && !myWindow.closed)
        myWindow.focus();
}

function insertAfter(referenceNode, newNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function cloneRow(e) {
    // first find our row
    var elem = e;
    while (elem.tagName != 'TR') {
        elem = elem.parentNode;
    }

    var newElem = elem.cloneNode(true);
    insertAfter(elem, newElem);
}

var counter = 6;
function addField(table) {
    var tableRef = document.getElementById(table);
    var newRow = tableRef.insertRow(-1);
    var maxVal = document.getElementById('cost_per_qty').max;
    newRow.setAttribute("id", "tbl_row_" + counter);

    var newCell = newRow.insertCell(0);
    newCell.setAttribute("class", "text-center");
    newCell.innerHTML = counter;

    newCell = newRow.insertCell(1);
    newCell.setAttribute("class", "no_pad");
    var newElem = document.createElement('input');
    newElem.setAttribute("type", "text");
    newElem.setAttribute("id", "sr_no_" + counter);
    newElem.setAttribute("name", "sr_no[]");
    newElem.setAttribute("class", "form-control search_srno");
    newElem.setAttribute("data-info", counter);
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(2);
    newElem = document.createElement('input');
    newElem.setAttribute("type", "hidden");
    newElem.setAttribute("id", "customer_id_" + counter);
    newElem.setAttribute("name", "customer_id[]");
    newElem.setAttribute("value", "");
    var newElem1 = document.createElement('span');
    newElem1.setAttribute("id", "customer_name_" + counter);
    newCell.appendChild(newElem);
    newCell.appendChild(newElem1);

    newCell = newRow.insertCell(3);
    newElem = document.createElement('input');
    newElem.setAttribute("type", "hidden");
    newElem.setAttribute("id", "type_" + counter);
    newElem.setAttribute("name", "type[]");
    newElem.setAttribute("value", '');
    var newElem2 = document.createElement('span');
    newElem2.setAttribute("id", "type_name_" + counter);
    newCell.appendChild(newElem);
    newCell.appendChild(newElem2);

    newCell = newRow.insertCell(4);
    newElem = document.createElement('input');
    newElem.setAttribute("type", "hidden");
    newElem.setAttribute("id", "agent_" + counter);
    newElem.setAttribute("name", "agent[]");
    newElem.setAttribute("value", '');
    var newElem2 = document.createElement('span');
    newElem2.setAttribute("id", "agent_name_" + counter);
    newCell.appendChild(newElem);
    newCell.appendChild(newElem2);

    newCell = newRow.insertCell(5);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "number");
    newElem.setAttribute("id", "quantity_" + counter);
    newElem.setAttribute("name", "quantity[]");
    newElem.setAttribute("class", "form-control qty");
    newElem.setAttribute("min", 0);
    newElem.setAttribute("max", "");
    newElem.setAttribute("step", "any");
    newElem.setAttribute("data-info", counter);
    newElem.setAttribute("value", "");
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(6);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "number");
    newElem.setAttribute("id", "rent_" + counter);
    newElem.setAttribute("name", "rent[]");
    newElem.setAttribute("class", "form-control rent_field");
    newElem.setAttribute("min", 0);
    newElem.setAttribute("max", maxVal);
    newElem.setAttribute("step", "any");
    newElem.setAttribute("data-info", counter);
    newElem.setAttribute("value", "");
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(7);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "text");
    newElem.setAttribute("id", "loan_amount_" + counter);
    newElem.setAttribute("name", "loan_amount[]");
    newElem.setAttribute("class", "form-control unitprice");
    newElem.setAttribute("value", "");
    newElem.setAttribute("readonly", "readonly");
    newCell.appendChild(newElem);

    enable("#remove_row");
    counter++;
}

function addLoanReceiveFormRow(table) {
    var tableRef = document.getElementById(table);
    var newRow = tableRef.insertRow(-1);
    newRow.setAttribute("id", "tbl_row_" + counter);

    var newCell = newRow.insertCell(0);
    newCell.setAttribute("class", "no_pad");
    var newElem = document.createElement('input');
    newElem.setAttribute("type", "text");
    newElem.setAttribute("id", "sr_no_" + counter);
    newElem.setAttribute("name", "sr_no[]");
    newElem.setAttribute("class", "form-control search_srno");
    newElem.setAttribute("data-info", counter);
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(1);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "number");
    newElem.setAttribute("id", "quantity_" + counter);
    newElem.setAttribute("name", "quantity[]");
    newElem.setAttribute("class", "form-control qty");
    newElem.setAttribute("value", "");
    newElem.setAttribute("readonly", "readonly");
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(2);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "number");
    newElem.setAttribute("id", "per_bag_loan_" + counter);
    newElem.setAttribute("name", "per_bag_loan[]");
    newElem.setAttribute("class", "form-control");
    newElem.setAttribute("value", "");
    newElem.setAttribute("readonly", "readonly");
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(3);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "number");
    newElem.setAttribute("id", "loan_amount_" + counter);
    newElem.setAttribute("name", "loan_amount[]");
    newElem.setAttribute("class", "form-control amount");
    newElem.setAttribute("value", "");
    newElem.setAttribute("readonly", "readonly");
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(4);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "number");
    newElem.setAttribute("id", "day_" + counter);
    newElem.setAttribute("name", "day[]");
    newElem.setAttribute("class", "form-control");
    newElem.setAttribute("value", "");
    newElem.setAttribute("readonly", "readonly");
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(5);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "number");
    newElem.setAttribute("id", "interest_" + counter);
    newElem.setAttribute("name", "interest[]");
    newElem.setAttribute("class", "form-control interest");
    newElem.setAttribute("value", "");
    newElem.setAttribute("readonly", "readonly");
    newCell.appendChild(newElem);

    newCell = newRow.insertCell(6);
    newCell.setAttribute("class", "no_pad");
    newElem = document.createElement('input');
    newElem.setAttribute("type", "number");
    newElem.setAttribute("id", "total_" + counter);
    newElem.setAttribute("name", "total[]");
    newElem.setAttribute("class", "form-control total");
    newElem.setAttribute("value", "");
    newElem.setAttribute("readonly", "readonly");
    newCell.appendChild(newElem);

    enable("#remove_row");
    counter++;
}