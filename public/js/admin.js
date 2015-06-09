(function() {
var SimplePersona;

SimplePersona = (function() {
  function SimplePersona() {
    var el, email, loggedin;
    el = document.getElementById('persona');
    loggedin = el.hasAttribute('data-user-email');
    email = loggedin ? el.getAttribute('data-user-email') : null;
    navigator.id.watch({
      loggedInUser: email,
      onlogin: function(assert) {
        return document.location = "login_assert.php?assertion=" + encodeURIComponent(assert) + "&audience=" + document.location.origin;
      },
      onlogout: function() {
        return document.location = "login.php";
      }
    });
    el.addEventListener('click', function() {
      if (loggedin) {
        return navigator.id.logout();
      } else {
        return navigator.id.request();
      }
    });
  }

  return SimplePersona;

})();

jQuery(function($) {
  Foundation.global.namespace = '';
  $(document).foundation();
  return new SimplePersona();
});

})();