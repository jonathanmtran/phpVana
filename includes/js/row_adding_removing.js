function addRow(table, insideData) {
	$(table + ' > tr:last').append(insideData);
}

function removeRow(identifier) {
	$(identifier).remove();
}