class Parser {
	// Returns an array with tags separated by comma
	// Ex.
	// text = Hello this is a #test of #the function #worl
	// returns (string) 'test','the','world'
	tags(text) {
		var rgx = /#(\w+)\b/gi;
		var tag;
		var tags = [];
		while (tag = rgx.exec(text)){
			tags.push(tag[1])
		}
		// tags is an array, implode with comma ,
		return tags.join(",");
	}

	// Returns all characters after the hash # and space
	// Only the first match
	// text = # Hello World
	// returns (string) "Hello World"
	title(text) {
		var rgx = /# (.*)/;
		let title = rgx.exec(text);
		if (title) {
			return title[1].trim();
		}
		return "";
	}

	// Returns the text without the first line
	// The first line is removed only if the first line has a # Headline1
	removeFirstLine(text) {
		var lines = text.split("\n");
		if (lines) {
			// If the first line included # Headline1 then the line is removed
			if (lines[0].includes("# ")) {
				lines.splice(0,1);
			}
		}
		return lines.join("\n");
	}

	// Decode HTML
	// Ex.
	// &gt; convert to >
	decodeHtml(html) {
		var txt = document.createElement("textarea");
		txt.innerHTML = html;
		return txt.value;
	}
}