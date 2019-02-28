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
		let self = this;
		await Ajax.getPage(key).then(function(data) {
			self.slug = data.slug;
			self.key = data.key;
			self.title = data.title;
			self.tags = data.tags;
			self.type = data.type;
			self.content = data.contentRaw;
		});
	}

	setSlug(slug) {
		let self = this;
		this.slug = slug;
		Ajax.updatePageSlug(this.key, this.slug).then(function(key) {
			self.key = key;
			showAlert("URL changed for " + this.slug);
		});
	}

	// This function save the page as draft or published
	// If value is TRUE the page is saved as draft
	// If value is FALSE the page is published
	setType(type) {
		let self = this;
		Ajax.updatePageType(this.key, type).then(function(key) {
			self.key = key;
			self.type = type;
			if (self.type==="draft") {
				showAlert("Page saved as draft");
			} else {
				showAlert("Page published");
			}
		});
	}

	async setTitle(title) {
		let self = this;
		await Ajax.updatePageTitle(this.key, title).then(function(key) {
			self.key = key;
			self.title = title;
		});
	}

	async setTags(tags) {
		let self = this;
		await Ajax.updatePageTags(this.key, tags).then(function(key) {
			self.key = key;
			self.tags = tags;
		});
	}

	async setContent(content) {
		let self = this;
		await Ajax.updatePageContent(this.key, content).then(function(key) {
			self.key = key;
			self.content = content;
		});
	}

	create() {
		let self = this;
		Ajax.createPage().then(function(key) {
			console.log(key);
			self.key = key;
			showAlert("New page created");
		});
	}

	delete() {
		Ajax.deletePage(this.key);
		this.key = "";
		showAlert("Page deleted");
	}

}