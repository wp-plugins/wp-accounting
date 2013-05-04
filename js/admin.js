jQuery(function( $ ){
	$(document).ready(function(){
		if($('#wpasaledate').length){
			$('#wpasaledate').closest('tr').before('<tr id="totalsales"><td>Total Sales: </td><td>$0</td></tr>');
			$('input[name^="sales"]').keyup(function(){
				calcSales();
			});
			calcSales();
		}
	});
	
	function calcSales(){
			var sales = 0;
			$('input[name^="sales"]').each(function(){
				var val = $(this).val();
				if($.isNumeric(val)){
					val = parseFloat(val);
					if($(this).hasClass('less')){
						sales -= val;
					}else{
						sales += val;
					}
				}
			});
			$('#totalsales td:eq(1)').text('$'+sales.toFixed(2));
	}
});