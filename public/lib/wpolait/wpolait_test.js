(function() {
document.addEventListener('DOMContentLoaded', function() {
	WPOLaitTest.Version().then(
		function(res) {
			console.log(res);
		},
		function(err) {
			console.error(err);
		}
	);
	WPOLaitTest.Add(3,5).then(
		function(res) {
			console.log("3 + 5 = ", res);
		},
		function(err) {
			console.error(err);
		}
	);
});
})();