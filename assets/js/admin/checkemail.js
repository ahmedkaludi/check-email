/**
 * Show/Hide individual add-on license key input.
 */
( function( $ ) {
	$( document ).ready( function() {
            $(".checkemail-hide").hide();
            $("#checkemail_autoheaders,#checkemail_customheaders").bind("change", function(){
                    if ($("#checkemail_autoheaders").is(":checked")){
                            $("#customheaders").hide();
                            $("#autoheaders").show();
                    }
                    if ($("#checkemail_customheaders").is(":checked")){
                            $("#autoheaders").hide();
                            $("#customheaders").show();
                    }
            });
	} );
} )(jQuery);
