<?php defined('BLUDIT') or die('Bludit CMS.'); ?>
<div class="sidebar col-lg-2 p-0 pt-4">
	<ul id="menu" class="list-group list-group-flush">
		<li id="newPage" class="list-group-item"><i class="fa fa-edit"></i> New page</li>
	</ul>
	<ul id="currentTags" class="list-group list-group-flush pt-4">
	</ul>
</div>
<script>
// Array with all current tags in the system
// array[ (string) tagKey => (array) pagesKeys ]
var _currentTags = [];

// The tag key selected in the left menu
var _tagSelected = null;

// Display all the tags in the system
function displayTags() {
	// Get all tags
	ajax.getTags().then(function(tags) {
		// Log
		log('displayTags() => ajax.getTags => tags',tags);

		// Init array for current tags
		_currentTags = [];

		// Init <ul> element and include the untagged
		$("#currentTags").html('<li class="tagItem list-group-item" data-key=""><i class="fa fa-star-o"></i> Untagged</li>');

		if (tags.length > 0) {
			// Add all tags to the <ul> element
			tags.forEach(function(tag) {
				_currentTags[tag.key] = tag.list;
				$("#currentTags").append('<li class="tagItem list-group-item" data-key="'+tag.key+'"># '+tag.name+'</li>');
			});
		}

		// Highlight the tag selected
		$('.tagItem[data-key="'+_tagSelected+'"]').addClass("tagSelected");
	});
}

$(document).ready(function() {

	// Click on a tag
	$(document).on("click", "li.tagItem", function() {
		// Add class to the tag clicked
		$("li.tagItem").removeClass("tagSelected");
		$(this).addClass("tagSelected");

		// Get the tag key
		_tagSelected = $(this).data("key");

		// Log
		log('click li.tagItem => tagKey',_tagSelected);

		// Show the pages related to the tag
		displayPagesByTag(_tagSelected);
	});

	// Click on new page
	$(document).on("click", "#newPage", function() {
		createPage();
	});

	// Retrive and show the tags
	displayTags();
});
</script>

<div class="pages-list col-lg-2 p-0">
	<ul id="currentPages" class="list-group list-group-flush">
	</ul>
</div>
<script>
// Array with all pages filter by the tag selected
// array[ (string) pageKey => (array) { key, title, content, contentRaw, description, date } ]
var _currentPages = [];

// Display all the pages passed as argument
function displayPages(pages) {
	// Init array for current pages by tag
	_currentPages = [];
	// Remove all pages from the <ul>
	$("#currentPages").html("");
	pages.forEach(function(page) {
		_currentPages[page.key] = page;
		// Add all pages to the <ul>
		$("#currentPages").append('<li class="pageItem list-group-item" data-key="'+page.key+'"><div class="pageItemTitle">'+page.title+'</div><div class="pageItemContent">'+page.contentRaw.substring(0, 50)+'</div></li>');
	});
}

// Display all pages related to the tag key
// If the tagKey is empty display the pages untagged
function displayPagesByTag(tagKey) {
	if (tagKey.length > 0) {
		ajax.getTag(tagKey).then(function(tag) {
			displayPages(tag.pages);
		});

	} else {
		ajax.getPagesUntagged().then(function(pages) {
			displayPages(pages);
		});
	}
}

// Load the page selected to the editor
function loadPage(pageKey) {
	// Check the current key if the same as the page is editing
	if (_key == pageKey) {
		console.log("Page already loaded");
		return true;
	}
	console.log("Loading page by key: "+pageKey);

	// Get the page
	ajax.getPage(pageKey).then(function(page) {
		// Log
		log('loadPage() => ajax.getPage => page',page);

		// Set the current key
		_key = pageKey;

		// Set slug
		_slug = page.slug;

		// Set is draft or not
		setDraft(page.type=="draft");

		// Load content in the editor
		let content = "";
		if (page.title.trim()) {
			content += "# "+page.title.trim()+"\n";
		}
		content += parser.decodeHtml(page.contentRaw);
		editorInitialize(content);
	});
}

$(document).ready(function() {
	// Click on pages
	$(document).on("click", "li.pageItem", function() {
		// Add class to the page selected
		$("li.pageItem").removeClass("pageSelected");
		$(this).addClass("pageSelected");

		// Get the page key clicked
		var pageKey = $(this).data("key");
		log('click li.pageItem => pageKey',pageKey);

		// Retrive all titles of the pages and show
		loadPage(pageKey);
	});
});
</script>