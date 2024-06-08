( function( $ ) {

	$( document ).ready(function() {
		$( '#search_id-search-date-input' ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});

		$( document ).on('click', '#thickbox-footer-close', function( event ) {
			event.preventDefault();
			tb_remove();
		});

		// Sent status tool tip.
		$( ".el-help" ).tooltip( {
			content: function() { return $( this ).prop( "title" ); },
			position: {
				my: "center top",
				at: "center bottom+10",
				collision: "flipfit"
			},
			hide: {
				duration: 100
			},
			show: {
				duration: 100
			}
		});
	});

	var tabsInsertedEvent = 'tabs_elem_inserted';

	insertionQ( '#tabs' ).every(function ( element ) {
		$( element ).trigger( tabsInsertedEvent )
	});

	$( document ).on( tabsInsertedEvent, function() {
		var activeTabIndex = parseInt( $( "#tabs ul" ).data( "active-tab" ) );

		activeTabIndex = isNaN( activeTabIndex ) ? 1 : activeTabIndex;
		$( "#tabs" ).tabs( { active: activeTabIndex } );
	} );

})( jQuery );

function showPrint() {
	document.getElementById('check_mail_print_button').style.display = '';
}
function hidePrint() {
	document.getElementById('check_mail_print_button').style.display = 'none';
}
function printLog() 
{
	document.getElementById('TB_ajaxWindowTitle').innerHTML  = "<h3>Email Content</h3>";
	document.getElementById('TB_closeWindowButton').style.display = "none";
	document.querySelectorAll('.check_mail_non-printable').forEach(function(el) {
		el.style.display = 'none';
	});

	var divToPrint=document.getElementById('TB_window');

	my_table = document.getElementById('my_table')

	my_table.querySelectorAll('tr').forEach(function(tr) {
		tr.style.background  = '#eee';
	});
	

	my_table.style.border = "1px solid #000"
	my_table.style.borderWidth = "1px";
	my_table.style.borderColor = "#000";
	my_table.style.borderStyle = "solid";

	tabs = document.getElementById('tabs')

	tabs.style.border = "1px solid #000"
	tabs.style.marginTop = "30px";
	tabs.style.height = "200px";
	tabs.style.padding = "20px 10px 20px 20px";;
	tabs.style.borderWidth = "1px";
	tabs.style.borderColor = "#000";
	tabs.style.borderStyle = "solid";


	var newWin=window.open('','Print-Window');

	newWin.document.open();

	newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

	newWin.document.close();

	setTimeout(function(){newWin.close();},10);
	jQuery('.tb-close-icon').click();
}
