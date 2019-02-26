class Ajax {

	constructor(apiURL, apiToken, userAuth) {
		this.apiURL = new URL(apiURL);
		this.token = apiToken;
		this.authentication = userAuth;
	}

	static async getPage(key) {
		let url = this.apiURL+"pages/"+key+"?token="+this.token;
		try {
			const response = await fetch(url, {
				method: "GET"
			});

			const json = await response.json();
			return json.data;
		}
		catch (err) {
			console.log(err);
			return false;
		}
	}

	static async createPage() {
		let url = this.apiURL+"pages";
		try {
			const response = await fetch(url, {
				credentials: 'same-origin',
				method: "POST",
				body: JSON.stringify({
					token: this.token,
					authentication: this.authentication,
					type: "draft"
				}),
				headers: new Headers({
					'Content-Type': 'application/json'
				}),
			});
			const json = await response.json();
			return json.data.key;
		}
		catch (err) {
			console.log(err);
			return true;
		}
	}

	static updatePage(key, title, content, tags) {
		log('this.updatePage()', key);

		let url = this.apiURL+"pages/"+key
		return fetch(url, {
			credentials: 'same-origin',
			method: "PUT",
			body: JSON.stringify({
				token: this.token,
				authentication: this.authentication,
				title: title,
				content: content,
				tags: tags
			}),
			headers: new Headers({
				'Content-Type': 'application/json'
			}),
		})
		.then(function(response) {
			return response.json();
		})
		.then(function(json) {
			return json.data.key;
		})
		.catch(err => {
			console.log(err);
			return true;
		});
	}

	// Update the type of the page
	// Returns the page key
	static updatePageType(key, type) {
		log('this.updatePageType()', key);

		let url = this.apiURL+"pages/"+key
		return fetch(url, {
			credentials: 'same-origin',
			method: "PUT",
			body: JSON.stringify({
				token: this.token,
				authentication: this.authentication,
				type: type
			}),
			headers: new Headers({
				'Content-Type': 'application/json'
			}),
		})
		.then(function(response) {
			return response.json();
		})
		.then(function(json) {
			return json.data.key;
		})
		.catch(err => {
			console.log(err);
			return true;
		});
	}

	// Update the slug of the page
	// Returns the page key
	static updatePageSlug(key, slug) {
		log('this.updatePageSlug(), key', key);
		log('this.updatePageSlug(), slug', slug);

		let url = this.apiURL+"pages/"+key
		return fetch(url, {
			credentials: 'same-origin',
			method: "PUT",
			body: JSON.stringify({
				token: this.token,
				authentication: this.authentication,
				slug: slug
			}),
			headers: new Headers({
				'Content-Type': 'application/json'
			}),
		})
		.then(function(response) {
			return response.json();
		})
		.then(function(json) {
			return json.data.key;
		})
		.catch(err => {
			console.log(err);
			return true;
		});
	}

	static deletePage(key) {
		log('this.deletePage()', key);
		let url = this.apiURL+"pages/"+key
		return fetch(url, {
			credentials: 'same-origin',
			method: "DELETE",
			body: JSON.stringify({
				token: this.token,
				authentication: this.authentication
			}),
			headers: new Headers({
				'Content-Type': 'application/json'
			}),
		})
		.then(function(response) {
			return response.json();
		})
		.then(function(json) {
			return true;
		})
		.catch(err => {
			console.log(err);
			return true;
		});
	}

	// Returns the list of tags in the system
	static async getTags() {
		let parameters = {
			token: this.token
		}
		let url = this.apiURL+"tags?"+$.param(parameters);
		try {
			const response = await fetch(url, {
				method: "GET"
			});
			const json = await response.json();
			return json.data;
		}
		catch (err) {
			console.log(err);
			return false;
		}
	}

	// Returns the pages related to the tag
	static async getTag(key) {
		let parameters = {
			token: this.token
		}
		let url = this.apiURL+"tags/"+key+"?"+$.param(parameters);
		try {
			const response = await fetch(url, {
				method: "GET"
			});
			const json = await response.json();
			return json.data;
		}
		catch (err) {
			console.log(err);
			return false;
		}
	}

	static async getPagesUntagged() {
		let parameters = {
			token: this.token,
			untagged: true,
			published: true,
			draft: true,
			static: false,
			scheduled: false
		}
		let url = this.apiURL+"pages?"+$.param(parameters);
		try {
			const response = await fetch(url, {
				method: "GET"
			});
			const json = await response.json();
			return json.data;
		}
		catch (err) {
			console.log(err);
			return true;
		}
	}
}
export default Ajax