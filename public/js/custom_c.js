jQuery(document).ready(function() {
    $(".cancel").click(function () {
        history.go(-1);
    });
    
    function format(state) {
        if (!state.id) return state.text; // optgroup
        return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
    }
    
    $(".dopdownselect").select2({
//        placeholder: "Select",
        allowClear: true,
        formatResult: format,
        width: 'auto', 
        formatSelection: format,
        escapeMarkup: function (m) {
            return m;
        }
    });
    
    //Select dop down with out search
    $(".dopdownselect-hidden-search").select2({
        allowClear: true,
        minimumResultsForSearch: -1,
        width: 'auto', 
        escapeMarkup: function (m) {
            return m;
        }
    });
    
    //For swat alert
    $('.delete').click(function (e) {
        e.preventDefault();
        var linkURL = $(this).attr("href");
        swal({
            title: "Are you sure?",
            text: "Your will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete",
            closeOnConfirm: false
        },
        function () {
            window.location.href = linkURL;
        });

    });
    
   
    $("#opener").click(function () {
        $("#dialog").dialog("open");
    });
    
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
    
    $('.marquee').marquee({
        //speed in milliseconds of the marquee
        duration: 20000,
        //gap in pixels between the tickers

        //time in milliseconds before the marquee will start animating
        delayBeforeStart: 0,
        //'left' or 'right'
        direction: 'left',
        //true or false - should the marquee be duplicated to show an effect of continues flow
        duplicated: false,
        pauseOnHover: true
    });
//
});