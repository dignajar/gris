class Ajax {

	static async getPage(key) {
		let url = _apiURL+"pages/"+key+"?token="+_apiToken;
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
		let url = _apiURL+"pages";
		try {
			const response = await fetch(url, {
				credentials: 'same-origin',
				method: "POST",
				body: JSON.stringify({
					token: _apiToken,
					authentication: _authToken,
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

		let url = _apiURL+"pages/"+key
		return fetch(url, {
			credentials: 'same-origin',
			method: "PUT",
			body: JSON.stringify({
				token: _apiToken,
				authentication: _authToken,
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

		let url = _apiURL+"pages/"+key
		return fetch(url, {
			credentials: 'same-origin',
			method: "PUT",
			body: JSON.stringify({
				token: _apiToken,
				authentication: _authToken,
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

		let url = _apiURL+"pages/"+key
		return fetch(url, {
			credentials: 'same-origin',
			method: "PUT",
			body: JSON.stringify({
				token: _apiToken,
				authentication: _authToken,
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
		let url = _apiURL+"pages/"+key
		return fetch(url, {
			credentials: 'same-origin',
			method: "DELETE",
			body: JSON.stringify({
				token: _apiToken,
				authentication: _authToken
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
			token: _apiToken
		}
		let url = _apiURL+"tags?"+$.param(parameters);
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
			token: _apiToken
		}
		let url = _apiURL+"tags/"+key+"?"+$.param(parameters);
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
			token: _apiToken,
			untagged: true,
			published: true,
			draft: true,
			static: false,
			scheduled: false
		}
		let url = _apiURL+"pages?"+$.param(parameters);
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