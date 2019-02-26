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
	if (_editor) {
		_editor.toTextArea();
		_editor = null;
	}

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
	$(".editor-button").show();

	// Editor event change
	_editor.codemirror.on("change", function(){
		// Reset timer
		if (_autosaveTimer != null) {
			clearTimeout(_autosaveTimer);
		}

		// Activate timer
		_autosaveTimer = setTimeout(function() {
			updatePage();
		}, _options["autosaveTimeout"]*1000);
	});
}

function editorGetContent() {
	return _editor.value();
}

function updatePage() {
	log('Updating page...', '');
	var rawContent = editorGetContent();
	var tags = parser.tags(rawContent);
	var title = parser.title(rawContent);

	// Remove the first line from the content
	// The first line supouse to be the title
	var parsedContent = parser.removeFirstLine(rawContent);
	// Remove tags from the content
	parsedContent = parsedContent.replace(/#(\w+)\b/gi, '');
	// Remove empty lines at the end
	parsedContent = parsedContent.trim();

	// Update the page
	ajax.updatePage(_key, title, parsedContent, tags).then(function(key) {
		_key = key;
		showAlert("Saved");
	});

	// Check if the title was changed
	if (_title != title) {
		log('Title changed', '');
		_title = title;
		displayPagesByTag(_tagSelected);
	}

	// Check if the content was changed
	if (_content != parsedContent) {
		log('Content changed', '');
		_content = parsedContent;
		displayPagesByTag(_tagSelected);
	}

	// Check if there are new tags in the editor
	// If there are new tags get the new tags for the sidebar
	if (_tags != tags) {
		log('Tags changed', '');
		_tags = tags;
		displayTags();
	}
}

function createPage() {
	// New pages is draft by default
	setDraft(true);

	let response = ajax.createPage();
	response.then(function(key) {
		_key = key;
		editorInitialize('# Title \n');
		// Log
		log('createPage() => ajax.createPage => key',key);
	});

	showAlert("New page created");
}

function deletePage() {
	ajax.deletePage(_key);
	_key = null;

	_editor.toTextArea();
	_editor = null;

	// Hide editor buttons
	$(".editor-button").hide();
	$("#editor").hide();

	showAlert("Page deleted");
}

// This function set the variable _draft and add the class to the button Draft
function setDraft(value) {
	if (value) {
		_draft = true;
		$("#draft-button").addClass("selected");
	} else {
		_draft = false;
		$("#draft-button").removeClass("selected");
	}
}

// This function save the page as draft or published
// If value is TRUE the page is saved as draft
// If value is FALSE the page is published
function setPageType(type) {
	let message = "";
	if (type=="draft") {
		setDraft(true);
		message = "Page saved as draft";
	} else {
		setDraft(false);
		message = "Page published";
	}

	ajax.updatePageType(_key, type).then(function(key) {
		_key = key;
		showAlert(message);
	});
}


// MAIN
$(document).ready(function() {
	// Click on draft button
	$(document).on("click", "#draft-button", function() {
		if (_draft) {
			setPageType("published");
		} else {
			setPageType("draft");
		}
	});

	// Click on url button
	$(document).on("click", "#url-button", function() {
		setPageSlug();
	});

	// Click on delete button
	$(document).on("click", "#delete-button", function() {
		if (confirm("Are you sure delete the current page ?")) {
			// Delete the current page
			deletePage();
			// Retrive and show the tags
			displayTags();
		}
	});
});

</script>