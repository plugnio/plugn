
$(document).ready(function() {


	// enable fileuploader plugin
	$('input[class="files-limited"]').fileuploader({
		limit: 1,
		fileMaxSize: 20,
		extensions: ['image/*'],
		addMore: true,
        thumbnails: {
            onItemShow: function(item) {
                // add sorter button to the item html
                item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-sort" title="Sort"><i class="fileuploader-icon-sort"></i></button>');

								if (!item.html.find('.fileuploader-action-edit').length)
										item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-popup fileuploader-action-edit" title="Edit"><i class="fileuploader-icon-edit"></i></button>');
            }
        },
		sorter: {
			selectorExclude: null,
			placeholder: null,
			scrollContainer: window,
			onSort: function(list, listEl, parentEl, newInputEl, inputEl) {
                // onSort callback
			}
		},
		editor: {
			cropper: {
				ratio: '1:1',
				minWidth: 100,
				minHeight: 100,
				showGrid: true
			}
		}
	});




		// enable fileuploader plugin
		$('input[class="files"]').fileuploader({
			limit: 1,
			fileMaxSize: 20,
			extensions: ['image/*'],
			addMore: true,
	        thumbnails: {
	            onItemShow: function(item) {
	                // add sorter button to the item html
	                item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-sort" title="Sort"><i class="fileuploader-icon-sort"></i></button>');

									if (!item.html.find('.fileuploader-action-edit').length)
											item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-popup fileuploader-action-edit" title="Edit"><i class="fileuploader-icon-edit"></i></button>');
	            }
	        },
			sorter: {
				selectorExclude: null,
				placeholder: null,
				scrollContainer: window,
				onSort: function(list, listEl, parentEl, newInputEl, inputEl) {
	                // onSort callback
				}
			},
			editor: {
				cropper: {
					ratio: '1:1',
					minWidth: 100,
					minHeight: 100,
					showGrid: true
				}
			}
		});


});
