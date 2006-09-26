/*
 * A very simple script to filter a table according to search criteria
 *
 * http://leparlement.org/filterTable
 * See also http://www.vonloesch.de/node/23
 */
function filterTable(term, table) {
	dehighlight(table);
	var terms = term.value.toLowerCase().split(" ");

	for (var r = 1; r < table.rows.length; r++) {
		var display = '';
		for (var i = 0; i < terms.length; i++) {
			if (table.rows[r].innerHTML.replace(/<[^>]+>/g, "").toLowerCase()
				.indexOf(terms[i]) < 0) {
				display = 'none';
			} else {
				if (terms[i].length) highlight(terms[i], table.rows[r]);
			}
			table.rows[r].style.display = display;
		}
	}
}


/*
 * Transform back each
 * <span>preText <span class="highlighted">term</span> postText</span>
 * into its original
 * preText term postText
 */
function dehighlight(container) {
	for (var i = 0; i < container.childNodes.length; i++) {
		var node = container.childNodes[i];

		if (node.attributes && node.attributes['class']
			&& node.attributes['class'].value == 'highlighted') {
			node.parentNode.parentNode.replaceChild(
					document.createTextNode(
						node.parentNode.innerHTML.replace(/<[^>]+>/g, "")),
					node.parentNode);
			// Stop here and process next parent
			return;
		} else if (node.nodeType != 3) {
			// Keep going onto other elements
			dehighlight(node);
		}
	}
}

/*
 * Create a
 * <span>preText <span class="highlighted">term</span> postText</span>
 * around each search term
 */
function highlight(term, container) {
	for (var i = 0; i < container.childNodes.length; i++) {
		var node = container.childNodes[i];

		if (node.nodeType == 3) {
			// Text node
			var data = node.data;
			var data_low = data.toLowerCase();
			if (data_low.indexOf(term) >= 0) {
				//term found!
				
				var new_node = document.createElement('span');

				node.parentNode.replaceChild(new_node, node);

				var result;
				while ((result = data_low.indexOf(term)) != -1) {
					new_node.appendChild(document.createTextNode(
								data.substr(0, result)));
					new_node.appendChild(create_node(
								document.createTextNode(data.substr(
										result, term.length))));
					data = data.substr(result + term.length);
					data_low = data_low.substr(result + term.length);
				}
				new_node.appendChild(document.createTextNode(data));
			}
		} else {
			// Keep going onto other elements
			highlight(term, node);
		}
	}
}

function create_node(child) {
	var node = document.createElement('span');
	node.setAttribute('class', 'highlighted');
	node.attributes['class'].value = 'highlighted';
	node.appendChild(child);
	return node;
}



/*
 * Here is the code used to set a filter on all filterable elements, usually I
 * use the behaviour.js library which does that just fine
 */
tables = document.getElementsByTagName('table');
for (var t = 0; t < tables.length; t++) {
	element = tables[t];

	if (element.attributes['class']
		&& element.attributes['class'].value == 'filterable') {

		/* Here is dynamically created a form */
		var form = document.createElement('form');
		form.setAttribute('class', 'filter');
		// For ie...
		form.attributes['class'].value = 'filter';
		
		var input = document.createElement('input');
		input.onkeyup = function() {
			filterTable(input, element);
		}
		form.appendChild(input);
		element.parentNode.insertBefore(form, element);
	}
}

