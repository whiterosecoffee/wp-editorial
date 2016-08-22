

// User Profile page changes
(function ( $ ) {

	function isValidEmailAddress( emailAddress ) {
		if( emailAddress === '' )
			return true;

	    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	    return pattern.test(emailAddress);
	};

	function isValidTwitterUsername( sn ) {
	    return /^[a-zA-Z0-9_]{1,15}$/.test(sn);
	}

	function isValidFacebookProfileUrl( profile ) {
		return /^((http|https):\/\/)?(www[.])?facebook.com\/.+/.test( profile );
	}

	function isValidGooglePlusProfileUrl( profile ) {
		return /^https?:\/\/plus\.google\.com\//.test( profile );
	}

	$( 'form#your-profile' ).on('submit', function() {
		var prefEmailAddress = $('input#pref_email').val();
		var twitterUsername = $( 'input#twitter' ).val();
		var facebookProfileUrl = $( 'input#facebook' ).val();
		var googlePlusProfile = $( 'input#googleplus' ).val();

		if( !isValidEmailAddress( prefEmailAddress ) ) {
			alert( "Invalid preferred email address." );
			return false;
		}

		if( twitterUsername != '' && !isValidTwitterUsername( twitterUsername ) ) {
			alert( "Invalid Twitter username." );
			return false;
		}

		if( facebookProfileUrl != '' && !isValidFacebookProfileUrl( facebookProfileUrl ) ) {
			alert( "Invalid facebook profile URL." );
			return false;
		}

		if( googlePlusProfile != '' && !isValidGooglePlusProfileUrl( googlePlusProfile ) ) {
			alert( "Invalid Google Plus profile URL." );
			return false;
		}

	});

    $(document).ready(function() {

    	if( !$( '.profile-php, .user-edit-php' ).size() ) {
    		return;
    	}

        $("#email").parent().parent().hide();

        $("#rich_editing").parent().parent().parent().hide();
        $("#color-picker").parent().parent().hide();
        $("#pass1").parent().parent().hide();
        $("#pass2").parent().parent().hide();

        // Removes personal options from profile edit screen.
        $( '#comment_shortcuts' ).closest( 'table' ).hide().prev('h3').hide();

        // Moves preferred email to top
        var prefEmail = $( '#pref_email' ).closest( 'tr' ).html();
        var tbody = $( '#pref_email' ).closest( 'tbody' );
        $( '#pref_email' ).closest( 'tr' ).remove();
        tbody.prepend( prefEmail );

        if( $( '#pref_email' ).val() === '' ) {
        	$( '#pref_email' ).val( $( '#email' ).val() );
        }

        // Removed website field from edit profile.
        $( '#url' ).closest( 'tr' ).hide();

        // Remove facebook section from edit profile.
        $( '#facebook-info' ).hide().prev( 'h3' ).hide();

        $( 'input#twitter' ).parent().prepend('@');
        $( 'input#twitter' ).prop( 'placeholder', 'username' );
        $( 'input#facebook' ).prop( 'placeholder', 'http://facebook.com/username' );
        $( 'input#googleplus' ).prop( 'placeholder', 'https://plus.google.com/user-id' );

        $( '#profile-avatar-section' ).prependTo( 'form#your-profile' );

        //Maintaining Hiarachy in Contact Info
        $( '#pref_email' ).parent().parent().append( $( '#twitter' ).parent().parent());
        $( '#pref_email' ).parent().parent().append( $( '#facebook' ).parent().parent());
        $( '#pref_email' ).parent().parent().append( $( '#googleplus' ).parent().parent());
        $( '#pref_email' ).parent().parent().append( $( '#nationality' ).parent().parent());

        $( 'label[for="nickname"] span.description' ).remove();

        // Scrum Item # 318 - Disable Display Name Publicily as for Non-Admins
        if( backend_object.current_user_role !== 'administrator' ) {
        	$('select[name="display_name"]').prop('disabled', true);
        }
    });
})( jQuery );

(function($) {

	// Adds character limit to profile description.
	$( '#your-profile label[for="description"], #ghost-author-fields-table label[for="user_description"]' ).parent().append( '<div><b>' + backend_object.remaining_chars + ': </b></div><span data-show="remaining-chars">350</span>' );

	$( '#your-profile textarea#description, #ghost-author-fields-table textarea#user_description' ).attr( 'maxlength', '350' ).on( 'keyup', function() {
	    $( this ).closest( 'tr' ).find( '[data-show="remaining-chars"]' ).text( (350 - $( this ).val().length ) );
	}).trigger( 'keyup' );

	function handleYoutubeResponse( url, message, embeddable ) {
		var count = parseInt( localStorage.getItem( 'videos' ) );
		var errors = localStorage.getItem( 'video-errors' );
		count--;

		if( !errors )
			errors = [];

		if( !embeddable ) {
			if( typeof errors === 'string' ) {
			    errors = [ errors ];
			}
			errors.push( url + ' - ' + message );
		}

		localStorage.setItem( 'videos', count );
		localStorage.setItem( 'video-errors', errors );


		if( count == 0 ) {
			$( '#publishing-action .spinner' ).hide();
			$( '#publishing-action input[type="submit"]' ).removeAttr( 'disabled' );
			localStorage.removeItem( 'video-errors' );
			localStorage.removeItem( 'videos', count );
			if( errors.length == 0 ) {
				$('#publishing-action input[type="submit"]').trigger( 'click', 'fixed' );
			} else {
				alert( 'Some videos can\'t be embedded \n' + errors.join( '\n' ) );
			}
		} else {
			localStorage.setItem( 'video-errors', errors );
			localStorage.setItem( 'videos', count );
		}

	}

	function validateYoutube() {
		var youtubeRegex = /(https?:\/\/www\.youtube\.com\/watch\?v=[%&=#\w-]*)/g;
		var content = tinyMCE.editors[0].getContent();
		var youtubeVideos = content.match( youtubeRegex );

		if( !youtubeVideos ) {
			youtubeVideos = [];
		}


		localStorage.setItem( 'videos', youtubeVideos.length );
		youtubeVideos.forEach( function( videoUrl ) {
			$('#publishing-action input[type="submit"]').attr( 'disabled', true );
			$( '#publishing-action .spinner' ).show();
			var regexMatches = videoUrl.match( /^.*?watch\?v=(.*)/i );
			var id = '';
			if( regexMatches.length > 1 )
				id = regexMatches[1];
			$.ajax( {
				url: 'https://www.googleapis.com/youtube/v3/videos?id=' + id + '&key=AIzaSyCw9jxYQ720SPtXFBjB8vqMzlNaGfjnOlk&part=status',
				success: function( response ) {
					try {
						if( response.items[0].status.embeddable ) {
							handleYoutubeResponse( videoUrl, 'Embedding allowed', true );
						} else {
							handleYoutubeResponse( videoUrl, 'Embedding not allowed', false );
						}
					} catch (err) {
						handleYoutubeResponse( videoUrl, 'Video is either unavailable or private.', false );
					}
				},
				error: function (error) {
					handleYoutubeResponse( videoUrl, 'Failed to verify the youtube video', false );
				}
			} );
		});

		return youtubeVideos.length > 0;
	}

	function areAnyTitlesEmpty() {
		var anyEmpty = false;
		$('#title-repository-table tr:not(.admin-only) input[type=text]').each(function() {
			if(!$(this).val()) {
				anyEmpty = true;
			}
		});

		return anyEmpty;
	}


	function validateBasics(full) {
		var title = $( 'input[name="post_title"]' ).val();
		var category = $('[name^="radio_tax_input[category][]"]:checked');

		var mood_tag = $('[name^="radio_tax_input[mood][]"]:checked');
		var mediaType = $('[name^="radio_tax_input[media-type-taxonomy][]"]:checked');
		var contentType = $('[name^="radio_tax_input[content-type-taxonomy][]"]:checked');
		var authorship = $('[name^="radio_tax_input[authorship-taxonomy][]"]:checked');
		var source = $('[name^="radio_tax_input[source-taxonomy][]"]:checked');
		var geography = $('[name^="tax_input[geography-taxonomy][]"]:checked');
		var focusKeyword= $('input#yoast_wpseo_focuskw').val();
		var errors = [];

		if( title == '' ) {
			errors.push( 'Title can\'t be left empty.' );
		}
		if( focusKeyword == '' ) {
			errors.push( 'Focus Keyword can\'t be left empty.' );
		}
		if( category.length == 0 ) {
			errors.push( 'Category is not selected.' );
		}

		if( mood_tag.length == 0 ) {
			errors.push( 'Mood tag is not selected.' );
		}
		if( mediaType.length == 0) {
			errors.push( 'Media Type is not added.' );
		}
		if( contentType.length == 0) {
			errors.push( 'Content Type is not added.' );
		}
		if( authorship.length == 0) {
			errors.push( 'Authorship is not added.' );
		}
		if( source.length == 0) {
			errors.push( 'Source is not added.' );
		}
		if( geography.length == 0) {
			errors.push( 'Geography is not selected.' );
		}

		if (full){return errors;}
		else{return errors.join( '\n' );}

	}
	function validateDraft(full) {
		errors = validateBasics(true);

		var body = tinyMCE.editors[0].getContent();
		var read_duration = $( 'input[name="read-duration"]' ).val();

		if( body == '' ) {
			errors.push( 'Article body can\'t be left empty.' );
		}
		if( read_duration == '' ) {
			errors.push( 'Read duration can\'t be left empty' );
		}
		if( areAnyTitlesEmpty() ) {
			errors.push( 'Please enter all alternative titles.' );
		}
		if (full){return errors;}
		else{return errors.join( '\n' );}
	}

	function validateFull(full) {
		errors = validateDraft(true);
		var image_attachment = $( 'input[name="image-attachment-id"]' ).val();
		if( image_attachment == '' ) {
			errors.push( 'Header image is not selected.' );
		}
		return errors.join( '\n' );
	}



    //BASIC
    $('#save-post').on( 'click', function ( e, d ) {
    	if ($('select#post_status').val() !=="in-progress" && $('select#post_status').val() !== "draft" && $('select#post_status').val() !== "publish" && $('select#post_status').val() !== "scheduled"){

			console.log(111,$('select#post_status').val());
			if( !d || ( d && d != 'fixed' ) ) {
	        var errors = validateBasics();
	        if( ( errors !== '' || validateYoutube() ) && ( backend_object.type_now == 'post' ) ) {
	        	if( errors != '' )
	        		alert(errors);
	        	return false;
		        }
			}
		}
    });

    //DRAFT
    $('#save-post').on( 'click', function ( e, d ) {
    	if ($('select#post_status').val() == "draft"){

			if( !d || ( d && d != 'fixed' ) ) {
	        var errors = validateDraft();
	        if( ( errors !== '' || validateYoutube() ) && ( backend_object.type_now == 'post' ) ) {
	        	if( errors != '' )
	        		alert(errors);
	        	return false;
		        }
			}
		}
    });

	//FULL
    $('#save-post').on( 'click', function ( e, d ) {
		if ($('select#post_status').val() == "publish" || $('select#post_status').val() == "scheduled"){

			if( !d || ( d && d != 'fixed' ) ) {
		        var errors = validateFull(true);
		        if( ( errors !== '' || validateYoutube() ) && ( backend_object.type_now == 'post' ) ) {
		        	if( errors != '' )
	        			alert(errors);
		        	return false;
		        }
			}
		}
    });


})(jQuery);

(function ($) {
    var file_frame;

    $('#avatar-image-button').on('click', function( event ){
        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $( this ).data( 'uploader_title' ),
            button: {
                text: $( this ).data( 'uploader_button_text' ),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            console.log(attachment.url);
            $('#profile_picture').val(attachment.url);
            $('#profile_picture_img').attr("src", attachment.url).css("display", "block");
            // Do something with attachment.id and/or attachment.url here
        });

        // Finally, open the modal
        file_frame.open();
    });
})(jQuery);


// New Ghost Author
(function ($) {

	function GhostAuthor() {
		this.firstName                = '';
		this.lastName                 = '';
		this.email                    = '';
		this.username                 = '';
		this.twitter                  = '';
		this.facebook                 = '';
		this.googleplus               = '';
		this.description              = '';
		this.profilePicture           = '';
		this.profilePictureTeamPage   = '';
		this.profilePictureAuthorPage = '';
		this.profilePictureThumbnail  = '';
	}

	GhostAuthor.setError = function ( selector ) {
		if( selector ) {
			$selector = $( selector );
			$selector.css( 'border-color', 'red' );
			$selector.addClass( 'error' );
		}
	}

	GhostAuthor.prototype.validate = function ( confirm ) {
		if( !confirm )
			return true;
		var errors = [];
		$( '#ghost-author-fields-table .error' ).removeAttr( 'style' );
		$( '#ghost-author-fields-table .error' ).removeClass( 'error' );
		if( this.username == '' || !/^[0-9a-z-A-Z]+$/.test( this.username ) ) {
			GhostAuthor.setError( '#username' );
		}
		if( this.firstName == '' ) {
			GhostAuthor.setError( '#user_first_name' );
		}
		if( this.lastName == '' ) {
			GhostAuthor.setError( '#user_last_name' );
		}
		if( this.email == '' || !/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test( this.email ) ) {
			GhostAuthor.setError( '#user_email' );
		}
		if( this.twitter != '' && !/^[A-Za-z0-9_]+$/.test( this.twitter ) ) {
			GhostAuthor.setError( '#user_twitter' );
		}
		if( this.facebook != '' && !/^https?:\/\/(www.)?facebook.com\/.*/.test( this.facebook ) ) {
			GhostAuthor.setError( '#user_facebook' );
		}
		if( this.googleplus != '' && !/^https?:\/\/(www.)?plus.google.com.*/.test( this.googleplus ) ) {
			GhostAuthor.setError( '#user_google_plus' );
		}

		return $( '#ghost-author-fields-table .error' ).size() == 0;
	}

	$( '[data-target="open-image-upload-modal"]' ).on( 'image-upload-complete', function() {
		$( '.ghost-author-profile-thumbnail' ).show();
	});

	GhostAuthor.prototype.clear = function ( confirm ) {
		if( !confirm )
			return;
		$( '#user_first_name' ).val('');
		$( '#user_last_name' ).val('');
		$( '#user_email' ).val('');
		$( '#username' ).val('');
		$( '#user_twitter' ).val('');
		$( '#user_facebook' ).val('');
		$( '#user_google_plus' ).val('');
		$( '#user_description' ).val('');
		$( '#profile_picture' ).val('');
		$( 'input#profile_picture_author_page' ).val( '' );
		$( 'input#profile_picture_team_page' ).val( '' );
		$( 'input#profile_picture_thumbnail' ).val( '' );
		$( '.author-page-thumbnail-preview' ).attr( 'src', '' );
		$( '.team-page-thumbnail-preview' ).attr( 'src', '' );
		$( '.ghost-author-profile-thumbnail' ).hide();
	}

	$( '#create-ghost-author-submit' ).on( 'click', function ( e ) {
		var ghostAuthor = new GhostAuthor();

		ghostAuthor.firstName                = $( '#user_first_name' ).val().trim();
		ghostAuthor.lastName                 = $( '#user_last_name' ).val().trim();
		ghostAuthor.email                    = $( '#user_email' ).val().trim();
		ghostAuthor.username                 = $( '#username' ).val().trim();
		ghostAuthor.twitter                  = $( '#user_twitter' ).val().trim();
		ghostAuthor.facebook                 = $( '#user_facebook' ).val().trim();
		ghostAuthor.googleplus               = $( '#user_google_plus' ).val().trim();
		ghostAuthor.description              = $( '#user_description' ).val().trim();
		ghostAuthor.profilePicture           = $( '#profile_picture' ).val();
		ghostAuthor.profilePictureTeamPage   = $( 'input#profile_picture_team_page' ).val();
		ghostAuthor.profilePictureAuthorPage = $( 'input#profile_picture_author_page' ).val();
		ghostAuthor.profilePictureThumbnail  = $( 'input#profile_picture_thumbnail' ).val();

		if( ghostAuthor.validate( true ) ) {
			$( '#ghost-author-form-error' ).hide();
			$.ajax( {
				method: 'post',
				url: backend_object.ajax_url,
				data: { action: 'new-ghost-author', details: ghostAuthor },
				beforeSend: function () {
					$( '#create-ghost-author-spinner' ).show();
				},
				complete: function() {
					$( '#create-ghost-author-spinner' ).hide();
				},
				success: function ( response ) {
					if( response.success ) {
						tb_remove();
						var display_name = response.data.details.display_name;
						var user_id = response.data.user_id;
						$( "select#ghost-author :selected" ).prop('selected', false);
						$( '<option value="' + user_id + '" selected="true">' + display_name + '</option>' ).appendTo('select#ghost-author');
						ghostAuthor.clear( true );
					} else {
						alert( response.data );
					}
				},
			} );
		} else {
			$( '#ghost-author-form-error' ).show();
		}
		e.preventDefault();
	});
	// Fix for removing height and width from thick box
	$( '#add-new-ghost-author' ).click( function() {
		setTimeout(function() {
			$('body').addClass('thickbox');
			$( '#TB_window' ).css( 'height', '' ).css( 'width', '' );
		}, 100);
	});

	$(window).on('tb_unload', function() {
		$('body').removeClass('thickbox');
	});

})( jQuery );

// Copy Editor Role Custom Permissions
(function ( $ ) {

	var currentUserRole = backend_object.current_user_role;
	var typeNow = backend_object.type_now;

	if( currentUserRole != 'copy_editor' || typeNow != 'post' )
		return ;

	// For all articles, copy editors can not change status. They can only save articles.
	function hideStatusEditOption() {
		$( 'a[href="#post_status"]' ).wrap( '<div></div>' ).parent().hide();
		$( '#delete-action' ).hide();
		$( 'a[href="#visibility"]' ).hide();
		$( 'a[href="#edit_timestamp"]' ).hide();
	}

	function hideSaveWhenPitchStatus() {
		if( $( '#post-status-display' ).text() == 'In Progress' ) {
			$( '#save-post' ).hide();
			$( 'input#publish' ).hide();
		}
	}
	function showPublishButtonWhenDraft() {
		if( $( '#post-status-display' ).text() == 'Draft' ) {
			$( 'input#publish' ).hide();
		}
	}


	$( window ).load( function() {
		hideStatusEditOption();
		hideSaveWhenPitchStatus();
		showPublishButtonWhenDraft();
	});

})( jQuery );

// Profile Image && ghost author uploader
(function ($) {

	var minWidth  = 630;
	var minHeight = 630;

	var selectedCoordinates = '';
	var step                = 0;
	var currentImage        = '';
	var jcropApi;

	var messages = [
		'Step 1: Select an image for your profile.',
		'Step 2: Select the desired area and press Crop Image',
		'Step 3: Almost done... Please select an area and press Crop Image.'
	];
	var minDimensionsError = 'Image must be minimum of ' + minWidth + ' x ' + minHeight + ' dimensions.';

	// HTML Elements
	var imagePreview           = $( '#preview-image img' );
	var cropButton             = $( '#image-upload-modal .make-thumbnail' );
	var uploadForm             = $( '#image-upload-form' );
	var imageUploadModal       = $( '#image-upload-modal' );
	var imageUploadSpinner     = $( '#image-upload-spinner' );
	var cropImageButtonSpinner = $( '.crop-image-action span' );
	var modalHeading           = $( '[data-modal-heading]' );
	var modalOpenButton        = $( '[data-target="open-image-upload-modal"]' );

	var CropImage = function( url ) {
		this.url            = url;
		this.originalHeight = '';
		this.originalWidth  = '';

		this.step1x1        = '';
		this.step1w         = '';
		this.step1y1        = '';
		this.step1h         = '';

		this.step2x1        = '';
		this.step2w         = '';
		this.step2y1        = '';
		this.step2h         = '';

	}

	function openModal() {
		imageUploadModal.foundation('reveal', 'open');
	}

	function closeModal() {
		imageUploadModal.foundation('reveal', 'close');
	}

	function resetToUploadStep() {
		currentImage = '';
		step = 0;
		setMessage( step );
		uploadForm.show();
		cropButton.hide();
		cropButton.removeAttr( 'disabled' );
		if( jcropApi )
			jcropApi.destroy();
		imagePreview.attr('src', '').attr('style', '');
	}

	function initJcrop( aspectRatio, width, height ) {

                var boxWidthSize = 900;
                var percent = 550/height;
                var per_width = width*percent;
                boxWidthSize = per_width;

		imagePreview.Jcrop({
                    onSelect:    showCoords,
                    bgColor:     'black',
                    keySupport:  false,
                    bgOpacity:   .4,
                    setSelect:   [ 0, 0, width, height ],
                    minSize: [630, 630],
                    boxWidth: boxWidthSize,
                    aspectRatio: aspectRatio
                }, function() {
                    jcropApi = this;
                });
	}

	function populateCoordinates() {
		switch( step ) {
			case 1:
				currentImage.step1x1 = selectedCoordinates.x;
				currentImage.step1w  = selectedCoordinates.w;
				currentImage.step1y1 = selectedCoordinates.y;
				currentImage.step1h  = selectedCoordinates.h;
				break;
			case 2:
				currentImage.step2x1 = selectedCoordinates.x;
				currentImage.step2w  = selectedCoordinates.w;
				currentImage.step2y1 = selectedCoordinates.y;
				currentImage.step2h  = selectedCoordinates.h;
				break;
		}
	}

	function sendDataToServer() {
		$.ajax( {
	 		url: backend_object.ajax_url,
	 		data: {
				action : 'image_crop',
				image  : currentImage,
				step   : step,
	 		},
	 		method: 'POST',
	 		beforeSend: function() {
	 			cropImageButtonSpinner.css( 'display', 'inline-block' );
	 			cropButton.attr( 'disabled', 'true' );
	 		}
	 	} ).done( function (res) {
	 		cropImageButtonSpinner.removeAttr( 'style' );
	 		cropButton.removeAttr( 'disabled' );

	 		if( res.success )
	 			imageSelectionDone( res );
	 		else
	 			alert( res.data );
	 	});
	}


	function imageSelectionDone( response ) {
		var data = response.data;

		$( '#author-page-thumbnail' ).attr( 'src', data.author_page.url ).show();
		$( '#team-page-thumbnail' ).attr( 'src', data.team_page.url ).show();

		$( 'input#profile_picture' ).val( 'custom' );
		$( 'input#profile_picture_author_page' ).val( data.author_page.url );
		$( 'input#profile_picture_team_page' ).val( data.team_page.url );
		$( 'input#profile_picture_thumbnail' ).val( data.thumbnail.url );

		modalOpenButton.trigger( 'image-upload-complete' );
		closeModal();
	}

	function chooseNext() {
		$('body').scrollTop( modalHeading.offset().top - 50 );
		setMessage( ++step );

		var thumbHeight = 0;
		var thumbWidth = 0;

		if( currentImage.originalWidth >= currentImage.originalHeight ) {
			if( currentImage.originalHeight >= (currentImage.originalWidth / 2)  ) {
				thumbHeight = currentImage.originalWidth / 2;
				thumbWidth  = currentImage.originalWidth;
			} else {
				thumbHeight = currentImage.originalHeight;
				thumbWidth  = currentImage.originalHeight * 2;
			}
		} else {
			thumbHeight = currentImage.originalWidth / 2;
			thumbWidth  = currentImage.originalWidth;
		}

		initJcrop( 2 / 1, thumbWidth, thumbHeight );
	}

	function setMessage( index ) {
		modalHeading.text( messages[ index ] );
	}


	function showCoords(c) {
	  selectedCoordinates = c;
	}


	function uploadSuccessfull( data ) {

		currentImage = new CropImage( data.url );
		currentImage.originalWidth  = data.width;
		currentImage.originalHeight = data.height;

		if( currentImage.originalHeight < minHeight || currentImage.originalWidth < minWidth ) {
			alert( minDimensionsError );
			imagePreview.attr( 'src', '' );
			resetToUploadStep();
			return;
		}

		imagePreview.attr('src', data.url);

		setMessage( ++step );
		uploadForm.hide();
		cropButton.show();

		imagePreview.on( 'load', function () {
			var minWidthHeight = Math.min( currentImage.originalHeight, currentImage.originalWidth );
			initJcrop( 1, minWidthHeight, minWidthHeight );
		});
	}

	cropButton.on( 'click', function() {

		populateCoordinates();

		if( step != 2 ) {
			chooseNext();
			return;
		}

	 	sendDataToServer();
	});

	modalOpenButton.on( 'click', function () {
		resetToUploadStep();
		openModal();
	});

	$('.close-reveal-modal').on('click', function() {
		closeModal();
	});

	$(document).ready(function(){
		new AjaxUpload('imageUpload', {
			action: $('#image-upload-form').attr('action'),
			name: 'image',
			onSubmit: function(file, extension) {
				imageUploadSpinner.show();
			},
			onComplete: function(file, response) {
				imageUploadSpinner.hide();
				response = JSON.parse(response.replace( /(<\/?pre.*?>)/g, '' ));

				if( !response.success ) {
					alert( response.data );
					return;
				}
				uploadSuccessfull( response.data );
			}
		});
	});

})( jQuery );

(function ($) {

	// Remove select all tags button from Tags and Categories.
	$(window).on('load', function() {
		$('[id*="taxonomy-"] #genesis-category-checklist-toggle').remove();
	});


    function seasonalTagChildOnClick() {
        var $this = $(this);

        $this.parent().siblings('ul').slideToggle();
        $this.closest('li').siblings('li').find(':checked').prop('checked', false);

        if ($this.is(':checked')) {
            $this.closest('ul').siblings('label').find('input[type="checkbox"]').prop('checked', true);
            $this.parents('ul:eq(1)').siblings('label').find('input[type="checkbox"]').prop('checked', true);
            $this.parents('li:eq(1)').siblings('li').find('input[type="checkbox"]').prop('checked', false);
            $this.parents('li:eq(2)').siblings('li').find('input[type="checkbox"]').prop('checked', false);
        } else {
            $this.parents('ul:eq(1)').siblings('label').find('input[type="checkbox"]').prop('checked', false);
            $this.closest('ul').siblings('label').find('input[type="checkbox"]').prop('checked', false);
        }
    }

    function seasonalTagOnClick() {

        var $this = $(this);

        $this.closest('li').siblings('li').find(':checked').prop('checked', false);

        if (!$this.is(':checked')) {
            $this.closest('li').find('input[type="checkbox"]:checked').prop('checked', false);
        }
    }

    $('[data-wp-lists="list:seasonal"] li:has(ul.children) input:not(li ul.children input)').hide();
    $('[data-wp-lists="list:seasonal"] .children li input[type="checkbox"]').parent().siblings('ul').hide();
    $('[data-wp-lists="list:seasonal"] .children li input[type="checkbox"]').on('click', seasonalTagChildOnClick);
    $('[data-wp-lists="list:seasonal"] li label input:not(ul.children li label input)').on('click', seasonalTagOnClick);

	//$('#menu-posts li a[href*="seasonal"]').closest('li').hide();
	$('[href="#seasonal-add"]').closest('h4').hide();

	$( '[data-wp-lists="list:seasonal"] li' ).has( '.children' ).find( 'label:first input[type="checkbox"]' ).off( 'click', seasonalTagOnClick).on( 'click', function() { return false; } );

})( jQuery );

(function ($) {

	function onChildTagClick() {
        var $this = $(this);

        if ($this.is(':checked')) {
            $this.closest('ul').siblings('label').find('input[type="checkbox"]').prop('checked', true);
            $this.parents('ul:eq(1)').siblings('label').find('input[type="checkbox"]').prop('checked', true);
        } else {
            $this.parents('ul:eq(1)').siblings('label').find('input[type="checkbox"]').prop('checked', false);

            // If no other children tags are checked then uncheck the parent
            if(!$this.closest('ul.children').find('li input:checked').length) {
            	$this.closest('ul').siblings('label').find('input[type="checkbox"]').prop('checked', false);
            }
        }
    }

    $('[data-wp-lists*="list:test-"] li:has(ul.children) > ul').slideToggle();

    $('[data-wp-lists*="list:test-"] li:has(ul.children) > label').on('click', function(e) {
    	var $this = $(this);

    	$this.siblings().slideToggle();

    	e.preventDefault();
    });

	// Hide parent tags & categories
	$('[data-wp-lists*="list:test-"] li:has(ul.children) > label > input').hide();

	$('[data-wp-lists*="list:test-"] .children li input[type="checkbox"]').on('click', onChildTagClick);


})(jQuery);

(function ($) {

	$( '#post_read_duration' ).hide();

	$(document).on('wpcountwords', function() {
		setTimeout(function() {
			var wordCount = parseInt( $( '.word-count:first' ).text() );
			var factor = 120;

			var readDuration = Math.ceil( wordCount / factor );

			$( 'input[name="read-duration"]' ).val( readDuration );

		}, 100);
	});
})( jQuery );

(function ($) {

	var currentUserRole = backend_object.current_user_role;

	$('.admin-only input[type="checkbox"]').attr('disabled', 'disabled');
	$('.admin-only input[type!="checkbox"]').attr('readonly', 'readonly');

	switch(currentUserRole) {
		case 'editor':
			$('.admin-checkbox').attr('disabled', 'disabled');
			$('[data-max-title-selection]').attr('data-max-title-selection', 3);
			break;
		case 'administrator':
			$('.admin-checkbox').attr('disabled', 'disabled');
			$('.manager-checkbox:checked').closest('tr').find('.admin-checkbox').removeAttr('disabled');

			$('.admin-only input').removeAttr('disabled').removeAttr('readonly');

			$('.manager-checkbox').attr('disabled', 'disabled');
			$('[data-max-title-selection]').attr('data-max-title-selection', 2);
			break;
		default:
			$('.admin-checkbox, .manager-checkbox').attr('disabled', 'disabled');
			$('[data-max-title-selection]').attr('data-max-title-selection', 0);
			break;
	}

	$('.admin-checkbox, .manager-checkbox').on('click', function() {
		var $this = $(this);
		var maxSelection = $('[data-max-title-selection]').attr('data-max-title-selection');

		if($this.hasClass('manager-checkbox')) {
			var checkedLength = $('.manager-checkbox:checked').length;

			if(checkedLength > maxSelection) {
				alert("You can't select more than " + maxSelection + " titles.");
				return false;
			}
		} else if($this.hasClass('admin-checkbox')) {
			var checkedLength = $('.admin-checkbox:checked').length;

			if(checkedLength > maxSelection) {
				alert("You can't select more than " + maxSelection + " titles.");
				return false;
			}
		}

	});


	
	//Add Pitch Link to Posts Menu
	$('#menu-posts ul.wp-submenu li:nth-child(3)').append('<a href="#" id="pitchIdeaPopup">Pitch Idea</a>' );
	$('#pitchIdeaPopup').click(function() {
	 	alert("Pitch idea:"+"\n"+"Source link(s):"+"\n"+"Keyword volume:"+"\n"+"If time sensitive, please describe:"+"\n"+"Does the article mention any of the following: politics, religion, sex, hate, illegal activity, violence?"+"\n"+"If yes, please describe:");

	});
})(jQuery);
