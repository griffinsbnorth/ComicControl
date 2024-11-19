<?php //img-upload-js.php - outputs javascript for making AJAX requests for in-page image uploads ?>

<script>

//initiate request if file input is changed
$('#staticfile').on('change',function() {
	var $directoryName = "comicsstatic";
	var $type = "staticfile";
	var filearr = ($(this).val()).split("\\");
	var lastbit = filearr[filearr.length-1];
	filearr = lastbit.split("/");
	lastbit = filearr[filearr.length-1];

	//only run the request if a file was actually submitted
	if(lastbit){
		//set variables for specific divs
		$fileholder = $(this).parent();
		$imagearea = $fileholder.find('.filenameholder');

		var percent = $fileholder.find('.percent');		
		//submit the ajax request
		$('form').ajaxSubmit({
			
			//set progress bar before the request happens
			beforeSend: function() {
				var percentVal = '0%';
				percent.html(percentVal);
			},
			
			//change progress as the file is uplaoded
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				percent.html('<?=$lang['Uploading image']?>: ' + percentVal);
				console.log(percentVal, position, total);
			},
			
			//show image as processing until the image is done resizing
			success: function(data) {
				percent.html('<?=$lang['Processing image...']?>');
			},
			
			//on finish, put return data in proper place
			complete: function(xhr) {
				
				//get response text and parse it
				var data = JSON.parse(xhr.responseText);
				
				//put the final filename in the form as a hidden input
				var $finalfile = $('<input type="hidden" class="finalfile" name="image-' + $type + '" value="' + data.final + '" />');
				$imagearea.html('');
				$imagearea.append($finalfile);
				percent.html('Done!');
								
				//if not the image library, put the image in the page
				//remove old image if there
				$('.currentstaticfile').remove();
			},
			
			//set the data that's going to be set to the ajax script
			data: {
				fieldname: $type,
				dirname: $directoryName
			},
			url: "/<?=$ccsite->relativepath.$ccsite->ccroot;?>ajax/img-custom-uploader.php"
			
		}); 
	}

});

//initiate request if file input is changed
$('#extra1file').on('change',function() {
	var $directoryName = "comicsextra";
	var $type = "extra1file";
	var filearr = ($(this).val()).split("\\");
	var lastbit = filearr[filearr.length-1];
	filearr = lastbit.split("/");
	lastbit = filearr[filearr.length-1];

	//only run the request if a file was actually submitted
	if(lastbit){
		//set variables for specific divs
		$fileholder = $(this).parent();
		$imagearea = $fileholder.find('.filenameholder');

		var percent = $fileholder.find('.percent');		
		//submit the ajax request
		$('form').ajaxSubmit({
			
			//set progress bar before the request happens
			beforeSend: function() {
				var percentVal = '0%';
				percent.html(percentVal);
			},
			
			//change progress as the file is uplaoded
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				percent.html('<?=$lang['Uploading image']?>: ' + percentVal);
				console.log(percentVal, position, total);
			},
			
			//show image as processing until the image is done resizing
			success: function(data) {
				percent.html('<?=$lang['Processing image...']?>');
			},
			
			//on finish, put return data in proper place
			complete: function(xhr) {
				
				//get response text and parse it
				var data = JSON.parse(xhr.responseText);
				
				//put the final filename in the form as a hidden input
				var $finalfile = $('<input type="hidden" class="finalfile" name="image-' + $type + '" value="' + data.final + '" />');
				$imagearea.html('');
				$imagearea.append($finalfile);
				percent.html('Done!');
								
				//if not the image library, put the image in the page
				//remove old image if there
				$('.currentextra1file').remove();
			},
			
			//set the data that's going to be set to the ajax script
			data: {
				fieldname: $type,
				dirname: $directoryName
			},
			url: "/<?=$ccsite->relativepath.$ccsite->ccroot;?>ajax/img-custom-uploader.php"
			
		}); 
	}

});

//initiate request if file input is changed
$('#extra2file').on('change',function() {
	var $directoryName = "comicsextra";
	var $type = "extra2file";
	var filearr = ($(this).val()).split("\\");
	var lastbit = filearr[filearr.length-1];
	filearr = lastbit.split("/");
	lastbit = filearr[filearr.length-1];

	//only run the request if a file was actually submitted
	if(lastbit){
		//set variables for specific divs
		$fileholder = $(this).parent();
		$imagearea = $fileholder.find('.filenameholder');

		var percent = $fileholder.find('.percent');		
		//submit the ajax request
		$('form').ajaxSubmit({
			
			//set progress bar before the request happens
			beforeSend: function() {
				var percentVal = '0%';
				percent.html(percentVal);
			},
			
			//change progress as the file is uplaoded
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				percent.html('<?=$lang['Uploading image']?>: ' + percentVal);
				console.log(percentVal, position, total);
			},
			
			//show image as processing until the image is done resizing
			success: function(data) {
				percent.html('<?=$lang['Processing image...']?>');
			},
			
			//on finish, put return data in proper place
			complete: function(xhr) {
				
				//get response text and parse it
				var data = JSON.parse(xhr.responseText);
				
				//put the final filename in the form as a hidden input
				var $finalfile = $('<input type="hidden" class="finalfile" name="image-' + $type + '" value="' + data.final + '" />');
				$imagearea.html('');
				$imagearea.append($finalfile);
				percent.html('Done!');
								
				//if not the image library, put the image in the page
				//remove old image if there
				$('.currentextra2file').remove();
			},
			
			//set the data that's going to be set to the ajax script
			data: {
				fieldname: $type,
				dirname: $directoryName
			},
			url: "/<?=$ccsite->relativepath.$ccsite->ccroot;?>ajax/img-custom-uploader.php"
			
		}); 
	}

});

//initiate request if file input is changed
$('#extra3file').on('change',function() {
	var $directoryName = "comicsextra";
	var $type = "extra3file";
	var filearr = ($(this).val()).split("\\");
	var lastbit = filearr[filearr.length-1];
	filearr = lastbit.split("/");
	lastbit = filearr[filearr.length-1];

	//only run the request if a file was actually submitted
	if(lastbit){
		//set variables for specific divs
		$fileholder = $(this).parent();
		$imagearea = $fileholder.find('.filenameholder');

		var percent = $fileholder.find('.percent');		
		//submit the ajax request
		$('form').ajaxSubmit({
			
			//set progress bar before the request happens
			beforeSend: function() {
				var percentVal = '0%';
				percent.html(percentVal);
			},
			
			//change progress as the file is uplaoded
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				percent.html('<?=$lang['Uploading image']?>: ' + percentVal);
				console.log(percentVal, position, total);
			},
			
			//show image as processing until the image is done resizing
			success: function(data) {
				percent.html('<?=$lang['Processing image...']?>');
			},
			
			//on finish, put return data in proper place
			complete: function(xhr) {
				
				//get response text and parse it
				var data = JSON.parse(xhr.responseText);
				
				//put the final filename in the form as a hidden input
				var $finalfile = $('<input type="hidden" class="finalfile" name="image-' + $type + '" value="' + data.final + '" />');
				$imagearea.html('');
				$imagearea.append($finalfile);
				percent.html('Done!');
								
				//if not the image library, put the image in the page
				//remove old image if there
				$('.currentextra3file').remove();
			},
			
			//set the data that's going to be set to the ajax script
			data: {
				fieldname: $type,
				dirname: $directoryName
			},
			url: "/<?=$ccsite->relativepath.$ccsite->ccroot;?>ajax/img-custom-uploader.php"
			
		}); 
	}

});

//initiate request if file input is changed
$('#thumbfile').on('change',function() {
	var $directoryName = "comicsthumbs";
	var $type = "thumbfile";
	var filearr = ($(this).val()).split("\\");
	var lastbit = filearr[filearr.length-1];
	filearr = lastbit.split("/");
	lastbit = filearr[filearr.length-1];

	//only run the request if a file was actually submitted
	if(lastbit){
		//set variables for specific divs
		$fileholder = $(this).parent();
		$imagearea = $fileholder.find('.filenameholder');

		var percent = $fileholder.find('.percent');		
		//submit the ajax request
		$('form').ajaxSubmit({
			
			//set progress bar before the request happens
			beforeSend: function() {
				var percentVal = '0%';
				percent.html(percentVal);
			},
			
			//change progress as the file is uplaoded
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				percent.html('<?=$lang['Uploading image']?>: ' + percentVal);
				console.log(percentVal, position, total);
			},
			
			//show image as processing until the image is done resizing
			success: function(data) {
				percent.html('<?=$lang['Processing image...']?>');
			},
			
			//on finish, put return data in proper place
			complete: function(xhr) {
				
				//get response text and parse it
				var data = JSON.parse(xhr.responseText);
				
				//put the final filename in the form as a hidden input
				var $finalfile = $('<input type="hidden" class="finalfile" name="image-' + $type + '" value="' + data.thumb + '" />');
				$imagearea.html('');
				$imagearea.append($finalfile);
				percent.html('Done!');
								
				//if not the image library, put the image in the page
				//remove old image if there
				$('.currentthumbfile').remove();
			},
			
			//set the data that's going to be set to the ajax script
			data: {
				fieldname: $type,
				dirname: $directoryName
			},
			url: "/<?=$ccsite->relativepath.$ccsite->ccroot;?>ajax/img-thumbnail-uploader.php"
			
		}); 
	}

});

</script>