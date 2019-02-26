import Ajax from 'ajax.js'

class Page {

	constructor() {
		this.slug = "";
		this.key = "";
	}

	setSlug() {
		var newSlug = prompt("Friendly URL:", this.slug);
		if (newSlug.trim()) {
			this.slug = newSlug;
			Ajax.updatePageSlug(this.key, this.slug).then(function(key) {
				this.key = key;
				showAlert("URL changed for " + this.slug);
			});
		} else {
			log("class Page, setSlug()", "User cancel or empty slug.");
		}
	}

	// This function save the page as draft or published
	// If value is TRUE the page is saved as draft
	// If value is FALSE the page is published
	setType(type) {
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

}