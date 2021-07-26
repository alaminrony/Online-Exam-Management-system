$(document).ready(function() {
    $('.delete').on('click', function(e) {
        e.preventDefault();
        var form = $(this).parents('form');
        swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it",
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm)
                form.submit();
        });
    });

    $(".js-source-states").select2();


    $(".interger-decimal-only").each(function() {
        $(this).keypress(function(e) {
            var code = e.charCode;

            if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
                return true;
            } else {
                return false;
            }
        });
    });

    //newly added
    $(".integer-decimal-only").each(function() {
        $(this).keypress(function(e) {
            var code = e.charCode;

            if (((code >= 48) && (code <= 57)) || code == 0 || code == 46) {
                return true;
            } else {
                return false;
            }
        });
    });

    $(".integer-only").each(function() {
        $(this).keypress(function(e) {
            var code = e.charCode;

            if (((code >= 48) && (code <= 57)) || code == 0) {
                return true;
            } else {
                return false;
            }
        });
    });

    $('button.reset-date').click(function() {
        var remove = $(this).attr('remove');
        $('#' + remove).val('');
    });


    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
    });


    $('.month-date-picker').datepicker({
        format: "yyyy-mm",
        viewMode: "months",
        minViewMode: "months",
    });


    $('.current-date-picker').datepicker({
        format: 'yyyy-mm-dd',
        minDate: '0',
    });
    
    
    $('.datepicker2').datepicker({
        format: 'dd MM yyyy',
        autoclose: true,
        todayHighlight: true,
    });
    

});
function validateNumberInputVariable(totalQty, processProductId, length) {
    var options = {
        closeButton: true,
        debug: false,
        positionClass: "toast-bottom-right",
        onclick: null,
    };
    //Find out the position of "." at Quantity
    var totalQtyPointPos = totalQty.toString().indexOf(".");
    if (totalQtyPointPos != -1) {
        var totalQtyArr = totalQty.toString().split(".");
        var kgAmnt = totalQtyArr[0];
        var gmAmntStr = totalQtyArr[1];
        var gmAmntStrLen = gmAmntStr.length;
        if (gmAmntStrLen > length) {
            var allowedQtyStr = kgAmnt + "." + gmAmntStr.substring(0, length);
            //$('#qty-' + processProductId).val(allowedQtyStr);
            if (totalQty != '') {
                $('#qty-' + processProductId).val(allowedQtyStr);
            }
            toastr.error('Error', "Only 1 Digit after Decimal point is allowed!", options);
            return false;
        }//EOF - if length
    }//EOF - if -1
}//EOF - function


function validateNumberInput(totalQty, processProductId) {

    var options = {
        closeButton: true,
        debug: false,
        positionClass: "toast-bottom-right",
        onclick: null,
    };
    //Find out the position of "." at Quantity
    var totalQtyPointPos = totalQty.toString().indexOf(".");
    if (totalQtyPointPos != -1) {
        var totalQtyArr = totalQty.toString().split(".");
        var kgAmnt = totalQtyArr[0];
        var gmAmntStr = totalQtyArr[1];
        var gmAmntStrLen = gmAmntStr.length;
        if (gmAmntStrLen > 6) {
            var allowedQtyStr = kgAmnt + "." + gmAmntStr.substring(0, 6);
            //$('#qty-' + processProductId).val(allowedQtyStr);
            if (totalQty != '') {
                $('#totalQty-' + processProductId).val(allowedQtyStr);
            }
            toastr.error('Error', "Only 6 Digits after Decimal point are allowed!", options);
            return false;
        }//EOF - if length
    }//EOF - if -1
}//EOF - function

function unitConversion(totalQty, processProductId) {
    //Find out the position of "." at Quantity
    var totalQtyPointPos = totalQty.toString().indexOf(".");
    if (totalQtyPointPos != -1) {
        //alert("Hello...");
        var totalQtyArr = totalQty.toString().split(".");
        var kgAmnt = totalQtyArr[0];
        var gmAmntArr = totalQtyArr[1];
        var kgFinalAmntStr = '';
        if (kgAmnt > 0) {
            kgFinalAmntStr = parseInt(kgAmnt) + " KG";
        }

        var lengthOfGm = gmAmntArr.length;//length of amount after decimal point
        //var zeroPadLength = (6 - (lengthOfGm)); //6 is fixed as 1KG is equal to 1000000 mg (0.000001 KG => 6 digit after decimal point)
        var pad = '000000';
        var totalAmntStr = (gmAmntArr + pad).substring(0, pad.length);
        var gmStr = totalAmntStr.substring(0, 3);//Subtract gram aamount
        var gmFinalAmntStr = "";
        if (gmStr > 0) {
            gmFinalAmntStr = parseInt(gmStr) + " gm";
        }
        var miliGmStr = totalAmntStr.substring(3, 6);//Subtract miligram aamount
        var mgFinalAmntStr = "";
        if (miliGmStr > 0) {
            mgFinalAmntStr = parseInt(miliGmStr) + " mg";
        }

        var text = kgFinalAmntStr + " " + gmFinalAmntStr + " " + mgFinalAmntStr;
        $('#totalQtyDetail-' + processProductId).val(text);
    }//EOF - if totalQtyPointPos != -1
}
