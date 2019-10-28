<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<!-- Editor buttons -->
<div id="toolbar" class="d-flex p-1">
	<i class="align-self-center fa fa-terminal pr-1"></i>
	<div id="message" class="mr-auto">Welcome to Bludit</div>
	<div id="private-button" class="editor-button pr-2 selected">Private</div>
	<div id="tags-button" class="editor-button pr-2">Tags</div>
	<div id="url-button" class="editor-button pr-2">URL</div>
	<div id="delete-button" class="editor-button pr-2">Delete</div>
</div>
<script>
var _messageTimeout = 5; // Second for hide the message

function showMessage(text) {
	$("#message").html(text).fadeIn();
	setTimeout(function(){
		$("#message").html("");
	},_messageTimeout*1000);
}
</script>

<!-- Editor -->
<textarea id="editor"></textarea>
<script>
var _editor = null;
var _autosaveTimer = null; // Timer object for the autosave
var _autosaveTimeout = 1.5 // Second before call the autosave

function editorInitialize(content) {
	// Clean up the editor if already initialized
	editorFinish();

	// Start a new editor
	_editor = new EasyMDE({
		autofocus: true,
		toolbar: false,
		spellChecker: false,
		status: false,
		tabSize: 4,
		initialValue: content,
		autoDownloadFontAwesome: false
	});

	// Display editor buttons
	uiShowEditorButtons();

	// Editor event change
	_editor.codemirror.on("change", function(){
		// Reset timer
		if (_autosaveTimer!=null) {
			clearTimeout(_autosaveTimer);
		}
		// Activate timer
		_autosaveTimer = setTimeout(function() {
			var editorValue = _editor.value();

			// Get title from the editor
			var title = Parser.title(editorValue);
			if (_page.title!==title) {
				_page.setTitle(title).then(function() {
					displayPagesByCurrentTag();
					console.log("Title updated: ",title);
				});
			}

			// Get tags from the editor
			// var tags = Parser.tags(editorValue);
			// if (_page.tags!==tags) {
			// 	_page.setTags(tags).then(function() {
			// 		displayTags();
			// 		console.log("Tags updated: ", tags);
			// 	});
			// }

			// Remove the first line from the content
			// The first line supouse to be the title
			var cleanContent = Parser.removeFirstLine(editorValue);
			// Remove tags from the content
			//cleanContent = cleanContent.replace(/#(\w+)\b/gi, '');
			// Remove empty lines at the end
			cleanContent = cleanContent.trim();
			if (_page.content!==cleanContent) {
				_page.setContent(cleanContent).then(function() {
					displayPagesByCurrentTag();
					console.log("Content updated");
				});
			}
		}, _autosaveTimeout*1000);
	});
}

function editorFinish() {
	if (_editor) {
		_editor.toTextArea();
		_editor = null;
	}
}

function editorGetContent() {
	return _editor.value();
}

function uiHideEditorButtons() {
	$(".editor-button").hide();
	$("#editor").hide();
}

function uiShowEditorButtons() {
	$(".editor-button").show();
}

function uiSetPrivate(value) {
	if (value) {
		$("#private-button").addClass("selected");
		$('li[data-key="'+_page.key+'"] > div.pageItemData > span.status').html("Private");
	} else {
		$("#private-button").removeClass("selected");
		$('li[data-key="'+_page.key+'"] > div.pageItemData > span.status').html("Public");
	}
}

// MAIN
$(document).ready(function() {
	// Click on private button
	$(document).on("click", "#private-button", function() {
		if (_page.type==="draft") {
			_page.setType("published").then(function() {
				uiSetPrivate(false);
				showMessage("Page set as public");
			});
		} else {
			_page.setType("draft").then(function() {
				uiSetPrivate(true);
				showMessage("Page set as private");
			});
		}
	});

	// Click on url button
	$(document).on("click", "#url-button", function() {
		var newSlug = prompt("Friendly URL:", _page.slug);
		if (newSlug) {
			if (newSlug!==_page.slug) {
				_page.setSlug(newSlug).then(function(){
					showMessage("URL changed for " + newSlug);
				});
			}
		} else {
			console.log("Event click #url-button", "User cancel or empty slug.");
		}
	});

	// Click on "tags" button
	$(document).on("click", "#tags-button", function() {
		var tags = prompt("Tags, please write tags separated by commas.", _page.tags);
		if (tags) {
			if (tags!==_page.tags) {
				_page.setTags(tags).then(function() {
					displayTags();
					console.log("Tags updated: ", tags);
				});
			}
		} else {
			console.log("Event click #tags-button", "User cancel or empty tags.");
		}
	});

	// Click on delete button
	$(document).on("click", "#delete-button", function() {
		if (confirm("Are you sure delete the current page ?")) {
			editorFinish();
			uiHideEditorButtons();
			_page.delete();
			// Retrive and show the tags
			displayTags();
		}
	});
});

</script>