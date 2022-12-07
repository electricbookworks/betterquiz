# wpolait
Wordpress plugin for adding JSON RPC to ones own plugins.

# Usage
First off, you need to include wpolait and activate it as a plugin in your Wordpress extension.

Then, create a class containing all the static methods you want to expose via JSON RPC.


	class MyMethods {
		public static function Add($x, $y) {
			return $x + $y;
		}
	}

Now, expose this class via wpolait:

	add_action('wpolait_register', function($wpolait) {
		$wpolait->register('MyMethods');
	}

To use this class from your Javascript, you access it with the global method:

	window.MyMethods.Add(4,5).then(
		function(result) {
			console.log("4 + 5 = ", result);
		}, 
		function(error) {
			console.error("AN ERROR OCCURRED: ", error);
		}
	);

The method call returns an ES6 Promise, hence the results are handled with the result, error function pair, or you can use the .catch method as well.

That's it.
