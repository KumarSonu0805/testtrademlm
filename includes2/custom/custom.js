// JavaScript Document
$(document).ready(function(){
    if($('.notify').length>0){
        
			var state = $('.notify').data('status');
			Swal.fire({
				title: $('.notify').data('title'),
				text: $('.notify').text(),
				icon: state,
				confirmButtonText: 'OK'
			});
    }
});

