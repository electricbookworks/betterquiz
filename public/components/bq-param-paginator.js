(function() {
  var BqParamPaginatorElementPrototype, MyUrl, attrs, currentScript, propertyToAttribute, shimShadowStyles;

  currentScript = document._currentScript || document.currentScript;

  MyUrl = (function() {
    function MyUrl(a) {
      var fn, j, len, pair;
      if (a == null) {
        a = false;
      }
      if (false === a) {
        a = window.location.search.substr(1).split('&');
      }
      if ("" === a) {
        return {};
      }
      this.qs = {};
      fn = function(pair, b) {
        var p;
        p = pair.split('=', 2);
        if (1 === p.length) {
          return b[p[0]] = "";
        } else {
          return b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
      };
      for (j = 0, len = a.length; j < len; j++) {
        pair = a[j];
        fn(pair, this.qs);
      }
    }

    MyUrl.prototype.get = function(key) {
      return this.qs[key];
    };

    MyUrl.prototype.has = function(key) {
      return this.qs[key] != null;
    };

    MyUrl.prototype.set = function(key, value) {
      return this.qs[key] = value;
    };

    MyUrl.prototype.url = function() {
      var a, b, qry;
      qry = (function() {
        var ref, results;
        ref = this.qs;
        results = [];
        for (a in ref) {
          b = ref[a];
          results.push(encodeURIComponent(a) + "=" + encodeURIComponent(b));
        }
        return results;
      }).call(this);
      return window.location.origin + window.location.pathname + "?" + qry.join("&");
    };

    return MyUrl;

  })();

  if (typeof shimShadowStyles === "undefined" || shimShadowStyles === null) {
    shimShadowStyles = function(styles, tag) {
      var fn, j, len, style;
      if (!Platform.ShadowCSS) {
        return;
      }
      fn = function(style) {
        var cssText;
        cssText = Platform.ShadowCSS.shimStyle(style, tag);
        Platform.ShadowCSS.addCssToDocument(cssText);
        style.remove();
      };
      for (j = 0, len = styles.length; j < len; j++) {
        style = styles[j];
        fn(style);
      }
    };
  }

  BqParamPaginatorElementPrototype = Object.create(window.HTMLElement.prototype);

  BqParamPaginatorElementPrototype.createdCallback = function() {
    var attaches, f, fn, i, importDoc, j, ref, ref1, ref2, ref3, templateContent;
    if ((ref = window.HTMLElement) != null) {
      if ((ref1 = ref.prototype) != null) {
        if ((ref2 = ref1.createdCallback) != null) {
          if (typeof ref2.call === "function") {
            ref2.call(this);
          }
        }
      }
    }
    importDoc = currentScript.ownerDocument;
    templateContent = importDoc.querySelector('#bq-param-paginator-template').content;
    shimShadowStyles(templateContent.querySelectorAll('style'), 'bq-param-paginator');
    if ('function' === typeof this.attachShadowRoot) {
      this.shadowRoot = this.attachShadowRoot({
        mode: 'open'
      });
    } else {
      this.shadowRoot = this.createShadowRoot();
    }
    this.el = templateContent.cloneNode(true);
    this.$ = {};
    attaches = this.el.querySelectorAll('[data-set]');
    fn = (function(_this) {
      return function(i) {
        var e;
        e = attaches.item(i);
        _this.$[e.getAttribute("data-set")] = e;
      };
    })(this);
    for (i = j = 0, ref3 = attaches.length; 0 <= ref3 ? j < ref3 : j > ref3; i = 0 <= ref3 ? ++j : --j) {
      fn(i);
    }
    this.qs = new MyUrl();
    this.param = this.getAttribute("parameter");
    this.paginator = document.getElementById(this.getAttribute('paginator'));
    f = (function(_this) {
      return function() {
        var pg;
        if ('complete' !== document.readyState) {
          return false;
        }
        if (_this.qs.has(_this.param)) {
          pg = parseInt(_this.qs.get(_this.param));
          _this.paginator.setAttribute('current', pg);
        }
        _this.paginator.addEventListener('bq-page', function(evt) {
          _this.qs.set(_this.param, evt.detail.n);
          return window.location = _this.qs.url();
        });
        return true;
      };
    })(this);
    if (!f()) {
      document.addEventListener('readystatechange', (function(_this) {
        return function() {
          return f();
        };
      })(this));
    }
  };

  BqParamPaginatorElementPrototype.attributeChangedCallback = function(attr, oldVal, newVal) {
    var ref;
    return (ref = attrs[attr]) != null ? typeof ref.call === "function" ? ref.call(this, oldVal, newVal) : void 0 : void 0;
  };

  attrs = {
    'example': function(oldVal, newVal) {}
  };

  propertyToAttribute = function(attr) {
    return {
      'get': function() {
        return this.getAttribute(attr);
      },
      'set': function(v) {
        this.setAttribute(attr, v);
      }
    };
  };

  Object.defineProperties(BqParamPaginatorElementPrototype, {
    'example': propertyToAttribute('example')
  });

  window.BqParamPaginatorElement = document.registerElement('bq-param-paginator', {
    prototype: BqParamPaginatorElementPrototype
  });

}).call(this);
