<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<div id="filesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="filesModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div id="filesModalBody" class="modal-body">
				<!-- Cover image -->
				<div class="card">
					<img id="coverImagePreview" class="card-img" alt="Cover image preview" src="<?php echo HTML_PATH_CORE_IMG.'default.svg' ?>"/>
					<div class="card-img-overlay bg-dark-transparent text-right">
						<button id="remove-cover-image-button" type="button" class="btn btn-primary">Remove cover image</button>
					</div>
				</div>

				<!-- Files table -->
				<table id="filesTable" class="table"></table>
			</div>
		</div>
	</div>
</div>

<!-- Form for upload files -->
<form name="filesForm" id="filesForm" class="d-none" enctype="multipart/form-data">
	<input name="filesToUpload[]" id="filesToUpload" type="file" multiple>
</form>

<script>

// Show the files in the table
function displayFiles(files) {
	if (!Array.isArray(files)) {
		return false;
	}

	// if (files.length == 0) {
	// 	$('#filesModalBody').html("There are no files yet.");
	// 	return true;
	// }

	// Clean table
	$('#filesTable').empty();

	// Regenerate the table
	$.each(files, function(key, file) {
		tableRow = '<tr id="js'+file.filename+'">'+
				'<td style="width:80px">Thumbnail</td>'+
				'<td>'+
					'<div>'+file.filename+'</div>'+
				'</td>'+
			'</tr>';

		// Append row in table
		$('#filesTable').append(tableRow);
	});

	return true;
}

function uploadFiles() {
	var filesToUpload = $("#filesToUpload")[0].files;
	for (var i=0; i < filesToUpload.length; i++) {
		var filename = filesToUpload[i].name;
		var fileSize = filesToUpload[i].size;

		// Check file size and compare with PHP upload_max_filesize
		if (fileSize > UPLOAD_MAX_FILESIZE) {
			showAlert("Max file size reached. Filename: "+filename);
			return false;
		}

		// Create the a new form with one file
		var formData = new FormData();
		formData.append("token", _apiToken);
		formData.append("authentication", _authToken);
		formData.append("file", filesToUpload[i]);

		$.ajax({
			url: _apiURL+"files/"+_page.key,
			type: "POST",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			xhr: function() {
				var xhr = $.ajaxSettings.xhr();
				if (xhr.upload) {
					xhr.upload.addEventListener("progress", function(e) {
						if (e.lengthComputable) {
							var percentComplete = (e.loaded / e.total)*100;
							showAlert("Uploading file "+filename+" ("+percentComplete+"%)");
						}
					}, false);
				}
				return xhr;
			}
		}).done(function(data) {
			if (data.status == "0") {
				var link = '['+data.filename+']('+data.absoluteURL+')'
				editorAddContent(link);
				showAlert("File uploaded");
			} else {
				showAlert("Server error: "+data.message);
			}
		});

	};

}

$(document).ready(function() {
	// Select image event
	$("#filesToUpload").on("change", function(e) {
		uploadFiles();
	});

	// Drag and drop image
	$(window).on("dragover dragenter", function(e) {
		e.preventDefault();
		e.stopPropagation();
	});

	// Drag and drop image
	$(window).on("drop", function(e) {
		e.preventDefault();
		e.stopPropagation();
		if (_page.key) {
			$("#filesToUpload").prop("files", e.originalEvent.dataTransfer.files);
			uploadFiles();
		} else {
			showAlert("Please select a page before upload a file");
		}

	});
});
</script>