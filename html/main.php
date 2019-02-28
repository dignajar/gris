<?php defined('BLUDIT') or die('Bludit CMS.'); ?>
<div id="toolbar" class="d-flex p-1">
	<i class="align-self-center fa fa-terminal pr-1"></i>
	<div id="message" class="mr-auto">Welcome to Bludit</div>
	<div id="draft-button" class="editor-button pr-2 selected">Draft</div>
	<div id="url-button" class="editor-button pr-2">URL</div>
	<div id="delete-button" class="editor-button pr-2">Delete</div>
</div>
<script>
var _options = {
	'alertTimeout': 5, // Second in dissapear the alert
	'autosaveTimeout': 1.5 // Second to activate before call the autosave
};

function showAlert(text) {
	$("#message").html(text).fadeIn();
	setTimeout(function(){
		$("#message").html("");
	},_options['alertTimeout']*1000);
}
</script>

<textarea id="editor"></textarea>
<script>
var _editor = null;
var _key = null; // Current page key in the editor
var _tags = []; // Current tags from the content
var _title = "";
var _content = ""; // Current content, this variable helps to know when the content was changed
var _autosaveTimer = null; // Timer object for the autosave
var _draft = true;
var _slug = null;

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
			if (page.title!==title) {
				page.setTitle(title).then(function() {
					displayPagesByCurrentTag();
					console.log("Title updated");
				});
			}

			// Get tags from the editor
			var tags = Parser.tags(editorValue);
			if (page.tags!==tags) {
				page.setTags(tags).then(function() {
					displayTags();
					console.log("Tag updated");
				});
			}

			// Remove the first line from the content
			// The first line supouse to be the title
			var cleanContent = Parser.removeFirstLine(editorValue);
			// Remove tags from the content
			cleanContent = cleanContent.replace(/#(\w+)\b/gi, '');
			// Remove empty lines at the end
			cleanContent = cleanContent.trim();
			if (page.content!==cleanContent) {
				page.setContent(cleanContent).then(function() {
					displayPagesByCurrentTag(_tagSelected);
					console.log("Content updated");
				});
			}
		}, _options["autosaveTimeout"]*1000);
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

// UI Set draft button
function uiSetDraft(value) {
	if (value) {
		$("#draft-button").addClass("selected");
	} else {
		$("#draft-button").removeClass("selected");
	}
}

// MAIN
$(document).ready(function() {
	// Click on draft button
	$(document).on("click", "#draft-button", function() {
		if (page.type==="draft") {
			page.setType("published").then(function() {
				uiSetDraft(false);
				showAlert("Page published");
			});
		} else {
			page.setType("draft").then(function() {
				uiSetDraft(true);
				showAlert("Page saved as draft");
			});
		}
	});

	// Click on url button
	$(document).on("click", "#url-button", function() {
		var newSlug = prompt("Friendly URL:", page.slug);
		if (newSlug) {
			if (newSlug!==page.slug) {
				page.setSlug(newSlug).then(function(){
					showAlert("URL changed for " + newSlug);
				});
			}
		} else {
			log("Event click #url-button", "User cancel or empty slug.");
		}
	});

	// Click on delete button
	$(document).on("click", "#delete-button", function() {
		if (confirm("Are you sure delete the current page ?")) {
			editorFinish();
			uiHideEditorButtons();
			page.delete();
			// Retrive and show the tags
			displayTags();
		}
	});
});

</script>