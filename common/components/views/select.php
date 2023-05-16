<?php

$js = "

$('#".$formModalName."-".$labelAttribute."').on('click', function() {

			$('#modal-". $modalName ."').remove();
	
			$.ajax({
				url: '". $action ."',
				dataType: 'html',
				beforeSend: function() {
					$('#button-".$valueAttribute." i').replaceWith('<i class=\"glyphicon glyphicon-refresh glyphicon-spin\"></i>');
					$('#button-".$valueAttribute."').prop('disabled', true);
				},
				complete: function() {
					$('#button-".$valueAttribute." i').replaceWith('<i class=\"glyphicon glyphicon-upload\"></i>');
					$('#button-".$valueAttribute."').prop('disabled', false);
				},
				success: function(html) {
					$('body').append('<div id=\"modal-". $modalName ."\" class=\"modal\">' + html + '</div>');
	                
					$('#modal-". $modalName ."').modal('show');
				}
			});
		});
	
	$(document).delegate('#modal-". $modalName ." .item', 'click', function(e) {
	    
	    var target;
	    
	    if($(e.target).hasClass('item')) {
	        target = $(e.target);
	    } else {
	        target = $(e.target).parent();
	    }
	    
	    $('#".$formModalName."-".$valueAttribute."').val(target.data('key'));
	    $('#".$formModalName."-".$labelAttribute."').val(target.data('value'));
        $('#modal-". $modalName ."').modal('hide');
    });
    
    $(document).delegate('#modal-". $modalName ." .pagination a', 'click', function(e) {
        e.preventDefault();
        e.stopPropagation();
    
    	var url = $(e.target).attr('href');

    	url += url.includes('?')? '&fromPager=1': '?fromPager=1';

        $.ajax({
				url: url,
				dataType: 'html',
				success: function(html) {
					$('#modal-". $modalName ." .list-wrapper').html(html);
				}
		});
	});
	
    $(document).delegate('#modal-". $modalName ." form', 'submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
    
        const data = new FormData(event.target);

        const value = Object.fromEntries(data.entries());

		var url = '". $action ."';

    	url += url.includes('?') ? '&fromPager=1': '?fromPager=1';

        $.ajax({
				url: url,
				dataType: 'html',
				data: value,
				success: function(html) {
					$('#modal-". $modalName ." .list-wrapper').html(html);
				}
		});
	});
	
";

$this->registerJs($js);

echo $form->field($formModal, $labelAttribute)->textInput ();

echo $form->field($formModal, $valueAttribute)->hiddenInput()->label(false);
