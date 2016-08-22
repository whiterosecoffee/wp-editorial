(function ($) {
	var step, cards, selectedCard, photobooth, addPhotoCanvas, previewCanvas, photoRaw, photoCanvasImage, photoInitialScale, finalImageUrl, resizedImage,
		emptyCard = kasra_eid_greeting.images_url + 'empty_img.jpg',
		cardsData = [
			{
				prefix: 'EidCard1',
				offsetX: 0,
				offsetY: 0,
				width: 340,
				height: 500,
				type: 'potrait',
				emptyImage: 'empty-card1.jpg'
			}, 
			{
				prefix: 'EidCard2',
				offsetX: 194,
				offsetY: 188,
				width: 290,
				height: 194,
				type: 'landscape',
				emptyImage: 'empty-card2.jpg'
			}, 
			{
				prefix: 'EidCard3',
				offsetX: 170,
				offsetY: 225,
				width: 316,
				height: 212,
				type: 'landscape',
				emptyImage: 'empty-card2.jpg'
			},
			{
				prefix: 'EidCard4',
				offsetX: 225,
				offsetY: 135,
				width: 260,
				height: 256,
				type: 'potrait',
				emptyImage: 'empty-card4.jpg'
			} 
		],
		canvasDimensions = {
			scaledWidth: 500,
			scaledHeight: 500,

			originalWidth: 1200,
			originalHeight: 1200, 

			scaleFactor: 1200 / 500
		},
		takePhotoButton = $('#take-photo-button'),
		uploadPhotoButton = $('#upload-photo-button'),
		finishButton = $('#finish-button'); 

	function Card(id, data) {
		this.id = id;
		this.name = data.prefix;

		this.offsetX = data.offsetX;
		this.offsetY = data.offsetY;
		this.width = data.width;
		this.height = data.height;
		this.type = data.type;
		this.emptyImage = data.emptyImage;
	}

	$.extend(Card.prototype, {
		getTemplatePath: function() { 
			return kasra_eid_greeting.images_url + this.name + "-template.png";
		},

		getThumbPath: function() {
			return kasra_eid_greeting.images_url + this.name + "-thumb.png";	
		},

		getThumbHtml: function() {
			return '<div class="card" data-card-id="' + this.id + '"><img src="' + this.getThumbPath() + '"/></div>';
		},

		attachHandler: function() {
			$('[data-card-id="' + this.id + '"]').on('click', $.proxy(this.selectCard, this));
		},

		selectCard: function() {
			selectedCard = this;
			gotoStep(++step);

			initTemplateCanvas();
		},

		getScaledPhotoDimensions: function (imgWidth, imgHeight) {
			var width_ratio, height_ratio, fw, fh;

			width_ratio  = unscale(this.width)  / imgWidth;
			height_ratio = unscale(this.height) / imgHeight;

			if (width_ratio > height_ratio) {
			    fw = imgWidth * width_ratio;
			    fh = imgHeight*fw/imgWidth;
			} else {
			    fh = imgHeight * height_ratio;
			    fw = imgWidth*fh/imgHeight;    
			}

			return { width: fw, height: fh };
		}
	});

	function unscale(dimen) {
		return dimen * canvasDimensions.scaleFactor;
	}

	function scale(dimen) {
		return dimen / canvasDimensions.scaleFactor;
	}

	function showLoading() {
		$('.loading-indicator').removeClass('hide');
	}

	function hideLoading() {
		$('.loading-indicator').addClass('hide');	
	}

	function drawEmptyImage() {
		fabric.Image.fromURL(kasra_eid_greeting.images_url + selectedCard.emptyImage, function(emptyImg) {
			var left = unscale(selectedCard.offsetX) + (unscale(selectedCard.width) / 2) - (emptyImg.width / 2);
			var top = unscale(selectedCard.offsetY) + (unscale(selectedCard.height) / 2) - (emptyImg.height / 2);

			emptyImg.set({ left:left, top: top, selectable: false});

			addPhotoCanvas.sendBackwards(emptyImg);

			addPhotoCanvas.add(emptyImg);
		});

		takePhotoButton.text(kasra_eid_greeting.take_a_photo);
	}

	function drawTemplate(cnvs) {
		showLoading();
		fabric.Image.fromURL(selectedCard.getTemplatePath(), function (img) {
			img.set({selectable: false});
			
			cnvs.add(img);
			hideLoading();
		});
	}

	/**
	* Updates the style of the canvas to scale it down, after initialized by fabric js.
	*/
	function updateCSSDimensions(cn, width, height) {
		var width = canvasDimensions.scaledWidth + 'px', height = canvasDimensions.scaledHeight + 'px';

		cn.upperCanvasEl.style.width = width;
		cn.upperCanvasEl.style.height = height;
		cn.lowerCanvasEl.style.width = width;
		cn.lowerCanvasEl.style.height = height;
		
		$('.canvas-container').css('width', width).css('height', height);
	}

	function initTemplateCanvas() {
		// If not intialized, initilize the canvas
		if(addPhotoCanvas) {
			addPhotoCanvas.clear();
		} else {
			addPhotoCanvas = new fabric.Canvas('upload-image-canvas');
			
			updateCSSDimensions(addPhotoCanvas);
			addPhotoCanvas.setBackgroundColor('#FFFFFF', addPhotoCanvas.renderAll.bind(addPhotoCanvas));
		}

		// Draw the empty (question) image
		drawEmptyImage();

		// Draw the template
		drawTemplate(addPhotoCanvas);

		$('#step2 .next-button').addClass('hide');
	}

	function initPreviewCanvas() {
		if(!previewCanvas) {
			previewCanvas = new fabric.Canvas('preview-image-canvas');
			updateCSSDimensions(previewCanvas)

			previewCanvas.setBackgroundColor('#FFFFFF', previewCanvas.renderAll.bind(previewCanvas));
		} else {
			previewCanvas.clear();
		}

		drawTemplate(previewCanvas);

		showLoading();
		drawPhoto(photoRaw, previewCanvas, function (img) {
			previewCanvasPhotoImg = img;

			photoInitialScale = {
				x: previewCanvasPhotoImg.scaleX,
				y: previewCanvasPhotoImg.scaleY
			};

			hideLoading();
		});
	}

	function gotoStep(st) {
		step = st;

		$('#tabs li a.selected').removeClass('selected');
		$('#tabs li a[href="#step' + step + '"]').addClass('selected');

		$('.tabContent').addClass('hide').filter('#step' + step).removeClass('hide');

		$('.app_container').trigger('step-change', step);
	}

	function selectTab() {
		return false;
	}

	function attachNavigationHandlers() {
		$('#tabs li a').on('click', selectTab);

		$('.back-button').on('click', function () {
			gotoStep(--step);
		});

		$('.next-button').on('click', function () {
			gotoStep(++step);
		});

		finishButton.on('click', uploadAndShowShareStep);

		$('.app_container').on('step-change', function (e, sp) {
			switch(sp) {
				case 1: 
					resetIfPhotoBoothOn();
					break;
				case 3: 
					initPreviewCanvas();
					break;
			}
		});
	}

	function resetIfPhotoBoothOn() {
		if(photobooth) {
			destroyPhotoBooth();
			drawEmptyImage();

			$('.image-placeholder').removeClass('hide');
			$('.webcam-preview, #allow-webcam-message').addClass('hide');	

			uploadPhotoButton.removeClass('hide');
		}
	}

	function initCards() {
		cards = [];

		for (var i = 0; i < cardsData.length; i++) {
			cards[i] = new Card(i, cardsData[i]);

			$('.tabContent#step1 .stack_holder').append(cards[i].getThumbHtml());

			cards[i].attachHandler();
		};
	}

	function initPhotoBooth() {
		$('.image-placeholder').addClass('hide');

		$('#step2 .stack_holder').addClass(selectedCard.type);

		$('#allow-webcam-message').removeClass('hide');
		photobooth = $('.webcam-preview').removeClass('hide potrait landscape').addClass(selectedCard.type).photobooth();

		takePhotoButton.text(kasra_eid_greeting.use_this_photo);
	}

	function destroyPhotoBooth() {
		$('#step2 .stack_holder').removeClass(selectedCard.type);

		photobooth.data('photobooth').destroy();
		photobooth = null;
	}

	function drawPhoto(photo, cnvs, cb) {
		showLoading();
		fabric.Image.fromURL(photo, function(img) {
			var scaledDimensions = selectedCard.getScaledPhotoDimensions(img.width, img.height);

			img.set({
				top: unscale(selectedCard.offsetY), 
				left: unscale(selectedCard.offsetX), 
				scaleX: scaledDimensions.width / img.width, 
				scaleY: scaledDimensions.height / img.height, 
				hasBorders: false, 
				hasControls: false
			});

			cnvs.add(img);
			cnvs.sendBackwards(img);
			cnvs.setActiveObject(img);

			cb(img);
		});
	}

	function addImageToCanvas(data) {
		photoRaw = data;

		// Remove previously added photo
		if(photoCanvasImage) {
			addPhotoCanvas.remove(photoCanvasImage);
		}

		showLoading();

		// Draw the currently taken/uploaded photo
		drawPhoto(photoRaw, addPhotoCanvas, function (img) {
			photoCanvasImage = img;

			hideLoading();
		});

		// Show the next button
		$('#step2 .next-button').removeClass('hide');
	}

	function takePhoto() {
		var photoData = photobooth.data('photobooth').takeImage();	

		destroyPhotoBooth();
		$('.image-placeholder').removeClass('hide');
		$('.webcam-preview, #allow-webcam-message').addClass('hide');

		takePhotoButton.text(kasra_eid_greeting.re_take_photo);

		addImageToCanvas(photoData);
	}

	function uploadFile(e) {
		var fd = new FormData();

		// If no file is selected
		if(!this.files.length) {
			return true;
		}

		// Add the file to form data
		fd.append("action", "upload_file");
		fd.append("photo-file", this.files[0]);

		$.ajax({
			url: kasra_eid_greeting.ajax_url,
			type: "POST",
			data: fd,
			processData: false,
			contentType: false,
			success: function(response) {

				if(response.success) {
					addImageToCanvas(response.data.url);	
				} else {
					alert(response.data.error);
				}
			},
			error: function(jqXHR, textStatus, errorMessage) {
				alert(errorMessage);
			},
			beforeSend: function() {
				showLoading();

				uploadPhotoButton.prop('disabled', 'disabled');
				takePhotoButton.prop('disabled', 'disabled');
			},
			complete: function() {
				hideLoading();

				uploadPhotoButton.prop('disabled', '');
				takePhotoButton.prop('disabled', '');
			}
		});
	}  

	function attachUploadHandlers() {
		// Take a photo button
		$('#take-photo-button').on('click', function () {
			if(!photobooth) {

				// Init photo booth
				initPhotoBooth();

				// Hide the next button
				$('#step2 .next-button').addClass('hide');

				// Hide upload photo button
				uploadPhotoButton.addClass('hide');
			} else {

				// Take the photo
				takePhoto();

				// Show the upload photo button
				uploadPhotoButton.removeClass('hide');
			}
		});

		// Upload photo button
		$('#upload-photo-button').on('click', function() {
			$('#file-select').click();
		});

		disableUploadButtonForIEAndSafari();

		// Watch for file selection, if selected upload it.
		$('#file-select').on('change', uploadFile);
	}

	/**
	*	Detects if the browser is Safari
	*/ 
	function isSafari() {
	    return /^((?!chrome).)*safari/i.test(navigator.userAgent);
	}

	/**
	*	Detects if the browser is IE
	*/ 
	function isIE() {
	    var ua = window.navigator.userAgent;
	    var msie = ua.indexOf('MSIE ');
	    var trident = ua.indexOf('Trident/');

	    if (msie > 0) {
	        // IE 10 or older => return version number
	        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
	    }

	    if (trident > 0) {
	        // IE 11 (or newer) => return version number
	        var rv = ua.indexOf('rv:');
	        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
	    }

	    // other browser
	    return false;
	}

	function disableUploadButtonForIEAndSafari() {
		if(isIE() || isSafari()) {
			takePhotoButton.hide();
		}
	}

	function rotateImage(val) {
		var value = val * 10;

		previewCanvasPhotoImg.rotate(value);
		previewCanvas.renderAll();
	}

	function scaleImage(val) {
		var scaleX = parseFloat(val) + photoInitialScale.x - 1;
		var scaleY = parseFloat(val) + photoInitialScale.y - 1;

		previewCanvasPhotoImg.setScaleX(scaleX);
		previewCanvasPhotoImg.setScaleY(scaleY);
		previewCanvas.renderAll();

	}

	function attachScaleRotateHandlers() {
		$('#scale-slider').on('input', function (val) {
			scaleImage($(this).val());
		});

		$('#rotate-slider').on('input', function (val) {
			rotateImage($(this).val());
		});
	}

	function attachHandlers() {
		attachNavigationHandlers();
		attachUploadHandlers();
		attachScaleRotateHandlers();

	}

	function uploadAndShowShareStep() {
		var imageData = previewCanvas.toDataURL('image/png');

		showLoading();

		finishButton.prop('disabled', 'disabled');

		$.ajax({
			url: kasra_eid_greeting.ajax_url,
			method: 'post',
			data: {
				action: 'save_file',
				imageData: imageData
			}
		})
		.done(function (response) {
			if(response.success) {
				finalImageUrl = response.data.url;
				resizedImage = response.data.resized_image;

				$('#share-card-preview').prop('src', finalImageUrl);
				$('#tabs').addClass('hide');

				gotoStep(++step);
			} else {
				alert(response.data.error);
			}
			

		})
		.fail(function (err) {
			console.log(err);
		})
		.always(function () {
			hideLoading();

			finishButton.prop('disabled', '');
		});

		$('#share-card-button').on('click', function() {
			publishOnFacebook(finalImageUrl);
		});
		
	}

	function makeUrl(relUrl) {
		return kasra_eid_greeting.card_url + relUrl;
	}

	function publishOnFacebook(imgUrl) {
		// FB.ui(
		// {
		// 	method: 'share_open_graph',
		// 	action_type: kasra_eid_greeting.fb_action_type,
		// 	action_properties: JSON.stringify({
		// 		type: 'eidcard',
		// 		locale: 'ar_AR',
		// 		url: 'http://kasra.co',
		// 		title: 'عيد',
		// 		description: 'ابتكر بطاقة تهنئة خاصة بك للعيد',
		// 		eidcard: window.location.href,
		// 		image: imgUrl
		// 	})
		// },
		// function(response) {
		// 	if(response && !response.error_code) {
		// 		location.href = '/'; // If response is success 
		// 	} else {
		// 		console.log(response);
		// 		alert('Unable to publish the story.');
		// 	}
		// }
		// );

		FB.ui({
			  method: 'feed',
			  link: window.location.href,
			  description: 'كسرة ستساعدك هذا العيد في صنع بطاقة تهنئة مميزة وخاصة بك بمناسبة عيد الأضحى والتي يمكنك مشاركتها مع عائلتك وأصحابك
',
			  picture: resizedImage,
			  name: 'عيد'
			}, function(response){
				console.log(response);
				if(response && response.post_id) {
					location.href = '/'; // If response is success 
				} else {
					alert('Unable to publish to facebook.');
				}
			}
			);

	}

	function init() {
		initCards();

		gotoStep(1);

		attachHandlers();

		$(document).on('ready', function () {
			$('li a[data-query-value="عيد"]').addClass('active');
		});

		$('.start_btn').on('click', function () {
			$('.container_bg').addClass('hide');
			$('.app_container').removeClass('hide');
		});
	}

	init();

})(jQuery);