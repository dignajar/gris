class Page {

	constructor() {
		this.slug = "";
		this.key = "";
		this.content = "";
		this.title = "";
		this.tags = "";
		this.type = "draft";
	}

	async load(key) {
		Ajax.getPage(key).then(function(data) {
			this.slug = data.slug;
			this.key = data.key;
			this.title = data.title;
			this.tags = data.tags;
			this.type = data.type;
			this.content = data.contentRaw;

			return true;
		});
	}

	setSlug(slug) {
		this.slug = slug;
		Ajax.updatePageSlug(this.key, this.slug).then(function(key) {
			this.key = key;
			showAlert("URL changed for " + this.slug);
		});
	}

	// This function save the page as draft or published
	// If value is TRUE the page is saved as draft
	// If value is FALSE the page is published
	setType(type) {
		Ajax.updatePageType(this.key, type).then(function(key) {
			this.key = key;
			this.type = type;
			if (this.type==="draft") {
				showAlert("Page saved as draft");
			} else {
				showAlert("Page published");
			}
		});
	}

	setContent(content) {
		var tags = Parser.tags(content);
		var title = Parser.title(content);

		// Remove the first line from the content
		// The first line supouse to be the title
		var parsedContent = Parser.removeFirstLine(content);
		// Remove tags from the content
		parsedContent = parsedContent.replace(/#(\w+)\b/gi, '');
		// Remove empty lines at the end
		parsedContent = parsedContent.trim();

		// Update the page
		Ajax.updatePage(this.key, title, parsedContent, tags).then(function(key) {
			this.key = key;
			showAlert("Saved");
		});

		// Check if the title was changed
		if (this.title != title) {
			log('Title changed', '');
			this.title = title;
			displayPagesByTag(true);
		}

		// Check if the content was changed
		if (this.content != parsedContent) {
			log('Content changed', '');
			this.content = parsedContent;
			displayPagesByTag(true);
		}

		// Check if there are new tags in the editor
		// If there are new tags get the new tags for the sidebar
		if (this.tags != tags) {
			log('Tags changed', '');
			this.tags = tags;
			displayTags();
		}
	}

	create() {
		Ajax.createPage().then(function(key) {
			console.log(key);
			this.key = key;
			showAlert("New page created");
		});
	}

	delete() {
		Ajax.deletePage(this.key);
		this.key = "";
		showAlert("Page deleted");
	}

}