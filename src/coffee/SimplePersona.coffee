class SimplePersona
	constructor: ()->
		el = document.getElementById('persona')		
		loggedin = el.hasAttribute('data-user-email')
		email = if loggedin then el.getAttribute('data-user-email') else null
		navigator.id.watch({
			loggedInUser: email
			onlogin: (assert)->
				document.location = "login_assert.php?assertion=" + encodeURIComponent(assert) +
					"&audience=" + document.location.origin
			onlogout: ()->
				document.location = "login.php"
		})
		el.addEventListener('click', ()->
			if loggedin
				navigator.id.logout()
			else
				navigator.id.request()
		)
		
