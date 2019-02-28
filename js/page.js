class Page {

	constructor() {
		this.slug = "";
		this.key = "";
		this.content = "";
		this.title = "";
		this.tags = "";
		this.type = "";
	}

	async create() {
		let self = this;
		await Ajax.createPage().then(function(key) {
			console.log(key);
			self.key = key;
			self.type = "draft";
		});
	}

	async delete() {
		let self = this;
		await Ajax.deletePage(this.key).then(function(key) {
			self.key = "";
		});
	}

	async load(key) {
		let self = this;
		await Ajax.getPage(key).then(function(data) {
			self.slug 	= data.slug;
			self.key 	= data.key;
			self.title 	= data.title;
			self.tags 	= data.tags;
			self.type 	= data.type;
			self.content 	= data.contentRaw;
		});
	}

	async setSlug(slug) {
		let self = this;
		Ajax.updatePageSlug(this.key, slug).then(function(key) {
			self.key = key;
			self.slug = slug;
		});
	}

	async setType(type) {
		let self = this;
		Ajax.updatePageType(this.key, type).then(function(key) {
			self.key = key;
			self.type = type;
		});
	}

	async setTitle(title) {
		let self = this;
		Ajax.updatePageTitle(this.key, title).then(function(key) {
			self.key = key;
			self.title = title;
		});
	}

	async setTags(tags) {
		let self = this;
		Ajax.updatePageTags(this.key, tags).then(function(key) {
			self.key = key;
			self.tags = tags;
		});
	}

	async setContent(content) {
		let self = this;
		Ajax.updatePageContent(this.key, content).then(function(key) {
			self.key = key;
			self.content = content;
		});
	}

}