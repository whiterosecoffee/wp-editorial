
(function($) {

    $.Jcrop.component.Selection.prototype.getActual = function() {
    	return this.core.unscale(this.get());
    };

	// Open Modal when clicked on Upload Image Button
	$( '#upload-image-button' ).on( 'click', function() {
		$('body').addClass('overflow-hidden');

		 $("#post-image-upload-modal").data('css-top', 50);

		$("#post-image-upload-modal").foundation('reveal', 'open');

	});

	$('.close-reveal-modal').on('click', closeModal);

	$('.switch-selection').on('click', switchSelection);

	$('.crop-image').on('click', save);

	var addImageButton = $('#add-image-button');
    if( addImageButton.size() > 0 ) {
        // Runs when the image button is clicked.
        addImageButton.click(function(e){

            var self = $( this );

            // Prevents the default action from occuring.
            e.preventDefault();

            // If the frame already exists, re-open it.
            if ( typeof wp !== 'undefined' && wp.media && wp.media.editor )
                wp.media.editor.open( 'image' );

            // Sets up the media library frame
            original_send = wp.media.editor.send.attachment;

            // Runs when an image is selected.
            wp.media.editor.send.attachment = function( a, b) {
               console.log(b); // b has all informations about the attachment
               if( b.url ) {
                    var widthDiff = b.width - self.attr( 'data-width' );
                    var heightDiff = b.height - self.attr( 'data-height' );
                    if( widthDiff >= 0 && heightDiff >= 0 ) {
                        
                        $( '#uploaded-image img' ).attr("src", b.url);
                        var data = {
                        	'url':b.url, 
                        	'width':b.width, 
                        	'height': b.height, 
                        	'attachment_id': b.id, 
                        	'mime_type': b.mime, 
                        	'post_id': $('input#post_ID').val()
                        };

                        $('#post-image-upload-form').hide();
                        $('.crop-image-action').removeClass('element-hidden');

                        initializeCrop( data );
                    }
                    else 
                        alert( 'Image dimensions are not correct it should be ' + self.attr( 'data-width' ) + ' x ' + self.attr( 'data-height' ) );
                }
                wp.media.editor.send.attachment = original_send;
               // or whatever you want to do with the data at this point
               // original function makes an ajax call to retrieve the image html tag and does a little more
            };
            $('.media-modal-close').on('click', function(){ 
                wp.media.editor.send.attachment = original_send;
            });
        });
	}

	/**
	 * Datasource for fetching data from the server.
	 */
	function DataSource() { }
	/**
	 * TODO: To populate category with details fetched from backend
	 */
	DataSource.getCategory = function() {
		var category = new ImageCategory('post');

		var sizes = {};
		sizes['header'] = [1200, 300, 4];
		sizes['preview'] = [400, 200, 2];

		var scaledSizes = {};
		scaledSizes['header'] = [
			{
				label: 'mobile',
				width: 480,
				height: 120,
			}
		];


		for(var size in sizes) {
			if(sizes.hasOwnProperty(size)) {
				var dimens = sizes[size];
				var image = new ImageDetail(size);

				image.setMinCoordinates(dimens[0], dimens[1]);
				image.setAspectRatio(dimens[2]); 

				if( scaledSizes[size] ) {
					$.each(scaledSizes[size], function (ind, val) {
						image.addScaledSize(new ScaledSize(val.label, val.width, val.height));
					});
				}

				category.addImage(image);	
			}
			
		}

		return category;
	}


	/**
	 * Defines width and height for scaled down versions for eg. mobile, tablet
	 */
	function ScaledSize(label, width, height) {
		
		this.label = label;
		this.width = width;
		this.height = height;

	}

	/**
	 * Defines labels and dimensions of the image.
	 */
	function ImageDetail(label) {

		this.label = label;
		this.minWidth = '';
		this.minHeight = '';
		this.aspectRatio = '';

		this.scaledSizes = [];

		this.selection = {};

	}
	ImageDetail.prototype.setMinCoordinates = function(width, height) {
		this.minWidth = width;
		this.minHeight = height;
		this.aspectRatio = width / height;
	}
	ImageDetail.prototype.setAspectRatio = function(ratio) {
		this.aspectRatio = ratio;
		this.minHeight = this.minWidth / ratio;
	}
	ImageDetail.prototype.addScaledSize = function(scaledSize) {
		this.scaledSizes.push(scaledSize);
	}
	ImageDetail.prototype.setSelection = function(selection) {
		this.selection = selection;

		this.selection.update(
			$.Jcrop.wrapFromXywh([ 0, 0, this.minWidth, this.minHeight ])
		);
	}
	ImageDetail.prototype.setSelectionOptions = function(xs, ys) {
		this.selection.setOptions({
			setSelect:   [ 0, 0, this.minWidth, this.minHeight ],
			minSize: [this.minWidth/ xs, this.minHeight / ys],
			aspectRatio: this.aspectRatio,
		});

		this.selection.element.addClass("selection-" + this.label);
	}
	ImageDetail.prototype.getLabel = function() {
		return this.label;
	}
	ImageDetail.prototype.getUrl = function() {
		return this.url + "?" + Math.random();
	}
	ImageDetail.prototype.getHtml = function() {
		return '<div> \
				<div><b>' + this.label + '</b></div> \
				<div><img src="' + this.getUrl() + '" /></div> \
				</div>';
	}
	ImageDetail.prototype.updateData = function(data) {
		// TODO data can be used to send additional information like attachment id
		this.url = data.url;
	}
	ImageDetail.prototype.getBackendData = function() {
		return {
			label: this.label,
			coordinates: this.selection.getActual(),
			scaled_sizes: this.scaledSizes,
			width: this.minWidth,
			height: this.minHeight
		};
	}

	/**
	 * Consists images for the category
	 */
	function ImageCategory(name) {
		
		this.name = name;
		this.url = '';
		this.attachmentId = '';
		this.postId = '';
		this.mimeType = '';
		this.images = [];

		this.xScale = '';
		this.yScale = '';

	}
	ImageCategory.prototype.addImage = function (image) {
		this.images.push(image);
	}
	ImageCategory.prototype.getImageCount = function() {
		return this.images.length;
	}
	ImageCategory.prototype.setImageUrl = function(url) {
		this.url = url;
	}
	ImageCategory.prototype.setImageAttachmentId = function(attachmentId) {
		this.attachmentId = attachmentId;
	}
	ImageCategory.prototype.setMimeType = function(mimeType) {
		this.mimeType = mimeType;
	}
	ImageCategory.prototype.setPostId = function(postId) {
		this.postId = postId;
	}
	ImageCategory.prototype.initSelections = function() {
		var xs = this.xScale;
		var ys = this.yScale;
		this.images.forEach(function(image) {
			image.setSelection(jcropApi.newSelection());
			image.setSelectionOptions(xs, ys);
		});
	}
	ImageCategory.prototype.updateImageData = function(label, data) {
		for (var i = 0; i < this.images.length; i++) {
			if(this.images[i].getLabel() === label) {
				this.images[i].updateData(data);
				return this.images[i];
			}
		}
		return null;
	}
	ImageCategory.prototype.getDataForBackend = function() {
		var data = {};

		data.attachmentId = this.attachmentId;
		data.url = this.url;
		data.postId = this.postId;
		data.mimeType = this.mimeType;

		data.images = [];

		this.images.forEach(function(image) {
			data.images.push(image.getBackendData());
		});

		return data;
	}
	ImageCategory.prototype.setXYScale = function(x, y) {
		this.xScale = x;
		this.yScale = y;
	}

	/**
	 * Initializes and sends request to server for generating images.
	 */

	var	uploadedImageElement = $('#uploaded-image img'),
	url                  = {},
	attachmentId         = {},
	jcropApi             = {},
	imageCategory        = {}
	selectedIndex        = 0;


	function onSelect(c) {
		console.log(c);
	}

	function initializeCrop(data) {

		var percent = 350 / data.height;
        var boxWidthSize = data.width * percent;

		url                 = data.url,
		attachmentId         = data.attachment_id,
		jcropApi             = {},
		imageCategory        = DataSource.getCategory()
		selectedIndex        = 0;

		imageCategory.setImageUrl(url);
		imageCategory.setImageAttachmentId(attachmentId);
		imageCategory.setMimeType(data.mime_type);
		imageCategory.setPostId(data.post_id);

		uploadedImageElement.Jcrop({	
			keySupport:  false,
			multi: true,
			multiMax: imageCategory.getImageCount(),
			boxWidth: boxWidthSize,
	    }, function() {
	        jcropApi = this;
	        imageCategory.setXYScale(jcropApi.opt.xscale, jcropApi.opt.yscale);
	        imageCategory.initSelections();
	    });

	} 

	function switchSelection() {
		var nextSelectedIndex = (selectedIndex + 1) % jcropApi.ui.multi.length;

		// If we have completed the loop and again starting with the first then 
		// reverse because the jcropApi.ui.multi array is sorted when the selection
		// is changed and the selected items becomes first.
		if(nextSelectedIndex === 0)
			jcropApi.ui.multi = jcropApi.ui.multi.reverse();

		jcropApi.ui.multi[nextSelectedIndex].focus();
		selectedIndex = nextSelectedIndex;
	}

	function save() {
		var $this = $(this);

		$('.crop-image-action .spinner').show();

		$this.prop('disabled', 'true');
		$.post( 
			backend_object.ajax_url , 
			{
				action: 'save_and_resize_image',
				crop_data: imageCategory.getDataForBackend() 
			}
		).done(function(response) {		
			var data = response.data;	

			$this.prop('disabled', '');

			$('#post-page-thumbnail').attr('src', data.header + "?" + Math.random()).show();
			$('input#image-attachment-id').val(data.header_attachment_id);

			$('#postimagediv .inside').html('<img width="266" height="133" src="' + data.preview + '?' + Math.random() + '" class="attachment-post-thumbnail" alt="wal-400x200">');

			console.log(data);

			closeModal();

			resetModal();
		}).fail(function(error) {
			alert(error);
	    	console.log(error);
	  	}).always(function() {
	  		$('.crop-image-action .spinner').hide();
	  	});
	}

	function closeModal() {
		$('body').removeClass('overflow-hidden');
		$("#post-image-upload-modal").foundation('reveal', 'close');
	}

	function resetModal() {
		jcropApi.destroy();
		
		uploadedImageElement.attr('src', '').removeAttr('style');

		$('#post-image-upload-form').show();
		$('.crop-image-action').addClass('element-hidden');
	}

})(jQuery);