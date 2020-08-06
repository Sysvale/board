export default function (value, c = 2, d = ',', t = '.') {
	if (typeof value === 'string') {
		value = value.ToFloat();
	}

	let n = value;
	let s = n < 0 ? "-" : "";
	let i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "";
	let j = (j = i.length) > 3 ? j % 3 : 0;

	c = isNaN(c = Math.abs(c)) ? 2 : c;
	d = d == undefined ? "," : d;
	t = t == undefined ? "." : t;

	return s + (j ? i.substr(0, j) + t : "") +
		i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
		(c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}
