<?php defined('BLUDIT') or die('Bludit CMS.'); ?>
<div class="sidebar col-lg-2 p-0 pt-4">
	<ul id="menu" class="list-group list-group-flush">
		<li id="website" class="list-group-item"><i class="fa fa-home"></i> <a target="_blank" href="<?php echo HTML_PATH_ROOT ?>">Website</a></li>
		<li id="newPage" class="list-group-item"><i class="fa fa-edit"></i> New page</li>
	</ul>
	<ul id="currentTags" class="list-group list-group-flush pt-4">
	</ul>
</div>
<script>
// Array with all current tags in the system
// array[ (string) tagKey => (array) pagesKeys ]
var _currentTags = [];

// The tag key selected in the left menu, by default untagged
var _tagSelected = "__untagged";

// Display all the tags in the system
function displayTags() {
	Ajax.getTags().then(function(tags) {
		console.log('displayTags() => Ajax.getTags => tags: '+tags);

		_currentTags = [];
		$("#currentTags").html('<li class="tagItem list-group-item" data-key="__untagged"><i class="fa fa-star-o"></i> Untagged</li>');
		if (tags.length > 0) {
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
		console.log('click li.tagItem => tagKey: '+_tagSelected);

		// Show the pages related to the tag
		displayPagesByCurrentTag();
	});

	// Click on "New page"
	$(document).on("click", "#newPage", function() {
		_page.create().then(function() {
			editorInitialize('# Title \n');
			uiSetPrivate(true);
			showMessage("New page created");
			displayPagesByCurrentTag();
		});
	});

	// Retrive and show the tags
	displayTags();
	// Retrive and show the pages untagged
	displayPagesByCurrentTag();
});
</script>

<div class="pages-list col-lg-2 p-0 h-100 overflow-auto">
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
		$("#currentPages").append('<li class="pageItem list-group-item'+(_page.key===page.key?" pageSelected":"")+'" data-key="'+page.key+'"><div class="pageItemTitle">'+page.title+'</div><div class="pageItemContent">'+page.contentRaw.substring(0, 50)+'</div><div class="pageItemData"><span class="status">'+(page.type==="draft"?"Private":"Public")+'</span><span class="date">'+page.date+'</span></div></li>');
	});
}

// Display all pages related to the tag key
function displayPagesByCurrentTag() {
	console.log('displayPagesByCurrentTag(), current tag selected: '+_tagSelected);
	if (_tagSelected==="__untagged") {
		Ajax.getPagesUntagged().then(function(pages) {
			displayPages(pages);
		});
	} else if (_tagSelected.length > 0) {
		Ajax.getPagesByTag(_tagSelected).then(function(tag) {
			displayPages(tag.pages);
		});
	}
}

$(document).ready(function() {
	// Click on pages
	$(document).on("click", "li.pageItem", function() {
		// Add class to the page selected
		$("li.pageItem").removeClass("pageSelected");
		$(this).addClass("pageSelected");

		// Get the page key clicked
		var pageKey = $(this).data("key");
		console.log('click li.pageItem => pageKey: '+pageKey);

		// Check the current key if the same as the page is editing
		if (_page.key===pageKey) {
			console.log("Page already loaded");
			return true;
		}

		console.log("Loading page by key: "+pageKey);

		_page.load(pageKey).then(function(result) {
			uiSetPrivate(_page.type==="draft");

			let content = "";
			if (_page.title.trim()) {
				content += "# "+_page.title.trim()+"\n";
			}

			if (_page.content.trim()) {
				content += Parser.decodeHtml(_page.content);
			}

			// if (_page.tags.trim()) {
			// 	content += "\n\n"+"#"+_page.tags.replace(","," #");
			// }

			editorInitialize(content);
		});
	});
});
</script>
