class Page {

	constructor(ajax) {
		// Object Ajax
		this.ajax = ajax;

		// Variables for the page
		this.slug = "";
		this.key = "";
	}

	setSlug() {
		var newSlug = prompt("Friendly URL:", this.slug);
		if (newSlug.trim()) {
			this.slug = newSlug;
			this.ajax.updatePageSlug(this.key, this.slug).then(function(key) {
				this.key = key;
				showAlert("URL changed for " + this.slug);
			});
		} else {
			log("class Page, setSlug()", "User cancel or empty slug.");
		}
	}

}