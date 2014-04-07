jQuery(document).ready(function($){
	$.ajaxSetup({cache:false});
	$('.clickable, .click_year').click(function(e){
		e.preventDefault();
		
		var id = $(this).attr('name');
		
		$('.work_description').hide();
		
		$('#'+id).show();
		
		$('.position').removeClass('position-active');
		$('#year'+id).addClass('position-active');

		
		$('.work_year').removeClass('year_active');
		$('#year2'+id).addClass('year_active').css('transition', 'all 0.5s ease');
		
	});

});