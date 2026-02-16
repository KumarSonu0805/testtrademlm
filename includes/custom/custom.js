// JavaScript Document
$(document).ready(function(){
    if($('.notify').length>0){
        
			var placementFrom = $('.notify').data('from');
			var placementAlign = $('.notify').data('align');
			var state = $('.notify').data('status');
			var content = {};

			content.message = $('.notify').text();
			content.title = $('.notify').data('title');;
			if (state == "success") {
				content.icon = 'fa fa-check';
			} else {
				content.icon = 'fa fa-times';
			}
			//content.url = 'index.html';
			//content.target = '_blank';

			$.notify(content,{
				type: state,
				placement: {
					from: placementFrom,
					align: placementAlign
				},
				time: 1000,
				delay: 5000,
			});
    }
});

