function disableShortcuts () {
	var editor = tinyMCE.editors[0];
	editor.paste_as_text = true;  

        //adding handlers crossbrowser
        if (tinymce.isOpera || /Firefox\/2/.test(navigator.userAgent)) {
            editor.onKeyDown.add(function (editor, e) {
                if (((tinymce.isMac ? e.metaKey : e.ctrlKey) && e.keyCode == 86) || (e.shiftKey && e.keyCode == 45))
                    editor.paste_as_text = true;
            });
        } else {            
            editor.onPaste.addToTop(function (editor, e) {
                editor.paste_as_text = true;
            });
        }
        editor.addShortcut("ctrl+b","nix","");
	//editor.addShortcut("ctrl+a","nix","");
	editor.addShortcut("ctrl+i","nix","");
	editor.addShortcut("ctrl+u","nix","");
	editor.addShortcut("ctrl+x","nix","");
	//editor.addShortcut("ctrl+c","nix","");
	//editor.addShortcut("ctrl+v","nix","");
};


/*
 * Attaches the image uploader to the input field
 */
 jQuery(document).ready(function($) {
    var $ = jQuery;
    // Instantiates the variable that holds the media library frame.
    var meta_image_frame;
//    var elem = $('#image-button');
//    if( elem.size() > 0 ) {
//        // Runs when the image button is clicked.
//        elem.click(function(e){
//
//            var self = $( this );
//
//            // Prevents the default action from occuring.
//            e.preventDefault();
//
//            // If the frame already exists, re-open it.
//            if ( typeof wp !== 'undefined' && wp.media && wp.media.editor )
//                wp.media.editor.open( 'image' );
//
//            // Sets up the media library frame
//            original_send = wp.media.editor.send.attachment;
//
//            // Runs when an image is selected.
//            wp.media.editor.send.attachment = function( a, b) {
//               console.log(b); // b has all informations about the attachment
//               if( b.url ) {
//                    var widthDiff = b.width - self.attr( 'data-width' );
//                    var heightDiff = b.height - self.attr( 'data-height' );
//                    if( widthDiff >= 0 && heightDiff >= 0 ) {
//                        $( 'input#' + self.attr( 'data-target' ) ).val(b.url);
//                        $( 'input#' + self.attr( 'data-target' ) + '-attachment-id' ).val(b.id);
//                    }
//                    else 
//                        alert( 'Image dimensions are not correct it should be ' + self.attr( 'data-width' ) + ' x ' + self.attr( 'data-height' ) );
//                }
//                wp.media.editor.send.attachment = original_send;
//               // or whatever you want to do with the data at this point
//               // original function makes an ajax call to retrieve the image html tag and does a little more
//            };
//            $('.media-modal-close').on('click', function(){ 
//                wp.media.editor.send.attachment = original_send;
//            });
//        });
//    }

    var selectedCoordinates = '';
    var currentImage        = '';
    var jcropApi;
    var boxWidthSize = 900;
    var makeThumbnailButton = $( '#post-image-upload-modal .make-thumbnail' );
    
    $('#image-button').on( 'click', function () {
        
        currentImage = '';
        makeThumbnailButton.hide();
        makeThumbnailButton.removeAttr( 'disabled' );
        if( jcropApi )
            jcropApi.destroy();
        $( '#post-preview-image img' ).attr('src', '').attr('style', '');
        
        
        $( '[data-modal-heading]' ).text("Step 1: Upload Header Image");
        $('#post-page-thumbnail').hide();
        $("#post-image-upload-form").show();
        $("#post-image-upload-modal").foundation('reveal', 'open');
    });
    
    var elem = $('#post-imageUpload');
    if( elem.size() > 0 ) {
        // Runs when the image button is clicked.
        elem.click(function(e){

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
                    var widthDiff = b.width - (self.attr( 'data-width' ) - 5);
                    var heightDiff = b.height - (self.attr( 'data-height' ) - 5);
                    if( widthDiff >= 0 && heightDiff >= 0 ) {
                        
                        $( '#post-preview-image img' ).attr("src", b.url);
                        var data = {'url':b.url, 'width':b.width, 'height': b.height, 'type': b.mime};
                        uploadSuccessfull( data );
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
    
//    $(document).ready(function(){  
//        new AjaxUpload('post-imageUpload', {
//                action: $('#post-image-upload-form').attr('action'),
//                name: 'image',
//                onSubmit: function(file, extension) {
//                    $("#post-image-upload-spinner").show();
//                },
//                onComplete: function(file, response) {
//                    $("#post-image-upload-spinner").hide();                  
//                    
//                    response = JSON.parse(response.replace( /(<\/?pre.*?>)/g, '' ));
//
//                    if( !response.success ) {
//                            alert( response.data );
//                            return;
//                    }
//                    uploadSuccessfull( response.data );
//                }
//        });
//    });
    
    makeThumbnailButton.on( 'click', function() {
        populateCoordinates();
        sendDataToServer();
    });
		
    
    function uploadSuccessfull( data ) {
        
        var CropImage = function( args ) {
    	    this.url            = args.url;
            this.type           = args.type;
            this.file           = args.file;
            this.post_id        = $( 'input#post_ID' ).val(),
    		this.originalHeight = '';
    		this.originalWidth  = '';
    		
    		this.step1x1        = '';
    		this.step1w         = '';
    		this.step1y1        = '';
    		this.step1h         = '';
    		
	};
        
	var minWidth  = 1200;
	var minHeight = 600;
        var tolerance = 5;
        currentImage = new CropImage( data );
        currentImage.originalWidth  = data.width;
        currentImage.originalHeight = data.height;
        if( currentImage.originalHeight < minHeight - tolerance || currentImage.originalWidth < minWidth - tolerance ) {
                alert('Image must be minimum of ' + minWidth + ' x ' + minHeight + ' dimensions.');
                $( '#post-preview-image img' ).attr( 'src', '' );
                resetToUploadStep();
                return;
        }
        $( '#post-preview-image img' ).attr('src', data.url);
        $( '[data-modal-heading]' ).text("Step 2: Crop(min 1200x600)");
        
        var percent = 350/currentImage.originalHeight;
        var per_width = currentImage.originalWidth*percent;
        boxWidthSize = per_width;
        
        $( '#post-image-upload-form' ).hide();
        $( '.make-thumbnail' ).show();

        $( '#post-preview-image img' ).one( 'load', function () {		
            $( '#post-preview-image' ).css("overflow", "hidden");  
            var minWidthHeight = Math.min( currentImage.originalHeight, currentImage.originalWidth );
            initJcrop( 2, minWidthHeight, minWidthHeight );
        });	
    }
    
    function initJcrop(aspectRatio, width, height) { 
        $( '#post-preview-image img' ).Jcrop({
            onSelect: showCoords,
            bgColor: 'black',
            keySupport: false,
            bgOpacity: .4,
            setSelect: [0, 0, width, height],
            minSize: [1200, 600],
            boxWidth: boxWidthSize,
            aspectRatio: aspectRatio
        }, function() {
            jcropApi = this;
        });
    }
    
    function showCoords(c) {
        selectedCoordinates = c;
    }
    
    function resetToUploadStep() {
        currentImage = '';
        step = 0;
        setMessage( step );
        $( '#image-upload-form' ).show();
        $( '.make-thumbnail' ).hide();
        $( '.make-thumbnail' ).removeAttr( 'disabled' );
        
        if( jcropApi )
            jcropApi.destroy();
        $( '#post-preview-image img' ).attr('src', '').attr('style', '');
    }
    
    function populateCoordinates() {
        currentImage.step1x1 = selectedCoordinates.x;
        currentImage.step1w = selectedCoordinates.w;
        currentImage.step1y1 = selectedCoordinates.y;
        currentImage.step1h = selectedCoordinates.h;
    }

    function sendDataToServer() {
        $.ajax({
            url: backend_object.ajax_url,
            data: {
                action: 'post_image_crop',
                image: currentImage,
            },
            method: 'POST',
            beforeSend: function() {
                $( '.crop-image-action span' ).css('display', 'inline-block');
                $( '.make-thumbnail' ).attr('disabled', 'true');
            }
        }).done(function(res) {
            $( '.crop-image-action span' ).removeAttr('style');
            $( '.make-thumbnail' ).removeAttr('disabled');
            
            if (res.success)
                imageSelectionDone(res);
            else
                alert(res.data);
        });
    }

    function imageSelectionDone(response) {
        var data = response.data;
        console.log(data);
        console.log(data.post_page_thumbnail);
        console.log(data.post_page_thumbnail.url);

        var container_width = parseInt( $('#post-page-thumbnail').parent().css("width") , 10);
        var percent = container_width/(data.post_page_thumbnail.width);
        var per_height = (data.post_page_thumbnail.height)*percent;
//        $('#post-page-thumbnail').attr('height', per_height);
        
        $('#post-page-thumbnail').attr('src', data.post_page_thumbnail.url).show();
        $('input#image-attachment-id').val(data.post_page_thumbnail.attachment_id);
        $('input#image').val(data.post_page_thumbnail.url);

        $("#post-image-upload-modal").foundation('reveal', 'close');
    }

    $('.close-reveal-modal').on('click', function() {
        $("#post-image-upload-modal").foundation('reveal', 'close');
        if( jcropApi )
            jcropApi.destroy();
        $( '#post-preview-image img' ).attr('src', '').attr('style', '');
    });
        
    // Adds limit to the title
    $( '#edit-slug-box' ).prepend( '<span><b>' + custom_editor.remaining_chars + ': </b></span><span data-show="remaining-chars">100</span>' );

    $( 'input[name="post_title"]' ).attr( 'maxlength', '100' ).on( 'keyup', function() {
        $( '#titlediv [data-show="remaining-chars"]' ).text( (100 - $( this ).val().length ) ); 
    }).trigger( 'keyup' );

    if( custom_editor.can_edit_tags == "" ) {
        $( '.jaxtag:first' ).hide();
        $(window).load( function() {
            $( '#link-post_tag' ).click().hide();
        });
        //$( '#tagcloud-post_tag' ).show();
    }
    
});
