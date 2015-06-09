(function() {(function() {
  var BqPaginatorElementPrototype, PageLink, Paginator, attrs, currentScript, propertyToAttribute, shimShadowStyles;

  currentScript = document._currentScript || document.currentScript;

  PageLink = (function() {
    function PageLink(paginator, text, n, current, enabled) {
      if (enabled == null) {
        enabled = true;
      }
      this.el = document.createElement('li');
      if (current) {
        this.el.className = 'current';
      }
      if (!enabled) {
        this.el.className = this.el.className + " unavailable";
      }
      this.el.innerHTML = '<a href="#">' + text + '</a>';
      this.el.addEventListener('click', (function(_this) {
        return function(evt) {
          evt.stopPropagation();
          evt.preventDefault();
          if (!current && enabled) {
            evt = new CustomEvent('bq-page', {
              'bubbles': true,
              'cancelable': true,
              'detail': {
                'n': n
              }
            });
            return _this.el.dispatchEvent(evt);
          }
        };
      })(this));
    }

    return PageLink;

  })();

  Paginator = (function() {
    function Paginator(ul, paginator1) {
      this.ul = ul;
      this.paginator = paginator1;
      this.setup();
    }

    Paginator.prototype.setup = function() {
      var i, j, lastPage, maxPage, minPage, p, ref, ref1, ref2;
      while (this.ul.firstChild) {
        this.ul.removeChild(this.ul.firstChild);
      }
      this.pagesToShow = parseInt(this.paginator.getAttribute("pages-to-show"));
      this.current = parseInt(this.paginator.getAttribute("current"));
      if (typeof console.log === "function") {
        console.log("In Paginator::setup - @current = " + this.current);
      }
      this.totalItems = parseInt(this.paginator.getAttribute("total-items"));
      this.itemsPerPage = parseInt(this.paginator.getAttribute("items-per-page"));
      ref = this.calculatePages(this.current, this.pagesToShow, this.totalItems, this.itemsPerPage), minPage = ref[0], maxPage = ref[1], lastPage = ref[2];
      p = new PageLink(this, "&laquo;", this.current - 1, false, 0 < this.current);
      this.ul.appendChild(p.el);
      for (i = j = ref1 = minPage, ref2 = maxPage; ref1 <= ref2 ? j <= ref2 : j >= ref2; i = ref1 <= ref2 ? ++j : --j) {
        p = new PageLink(this, i + 1, i, this.current === i, true);
        this.ul.appendChild(p.el);
      }
      p = new PageLink(this, "&raquo;", this.current + 1, false, this.current + 1 < lastPage);
      this.ul.appendChild(p.el);
      return this.ul.addEventListener('bq-page', function(evt) {
        return console.log('Paginator::bq-page : pg=', evt.detail.n);
      });
    };

    Paginator.prototype.calculatePages = function(current, pagesToShow, totalItems, itemsPerPage) {
      var lastPage, maxPage, minPage;
      console.log(arguments);
      minPage = Math.max(0, current - Math.floor(pagesToShow / 2.0));
      console.log("minPage = " + minPage);
      lastPage = Math.ceil(totalItems / (1.0 * itemsPerPage) - 1);
      console.log("lastPage = " + lastPage);
      if ((minPage + pagesToShow) > lastPage) {
        minPage = Math.max(0, lastPage - pagesToShow);
      }
      maxPage = Math.min(minPage + pagesToShow, lastPage);
      return [minPage, maxPage, lastPage];
    };

    return Paginator;

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

  BqPaginatorElementPrototype = Object.create(window.HTMLElement.prototype);

  BqPaginatorElementPrototype.createdCallback = function() {
    var e, importDoc, j, len, ref, ref1, ref2, ref3, templateContent;
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
    templateContent = importDoc.querySelector('#bq-paginator-template').content;
    shimShadowStyles(templateContent.querySelectorAll('style'), 'bq-paginator');
    this.shadowRoot = this.createShadowRoot();
    this.el = templateContent.cloneNode(true);
    this.$ = {};
    ref3 = this.el.querySelectorAll('[data-set]');
    for (j = 0, len = ref3.length; j < len; j++) {
      e = ref3[j];
      this.$[e.getAttribute("data-set")] = e;
    }
    this.paginator = new Paginator(this.$.ul, this);
    this.shadowRoot.appendChild(this.el);
    console.log("Completed bq-paginator createdCallback");
  };

  attrs = {
    'current': function(oldVal, newVal) {
      console.log("this = ", this);
      return this.paginator.setup();
    }
  };

  BqPaginatorElementPrototype.attributeChangedCallback = function(attr, oldVal, newVal) {
    var ref;
    return (ref = attrs[attr]) != null ? typeof ref.call === "function" ? ref.call(this, oldVal, newVal) : void 0 : void 0;
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

  Object.defineProperties(BqPaginatorElementPrototype, {
    'current': propertyToAttribute('current')
  });

  window.BqPaginatorElement = document.registerElement('bq-paginator', {
    prototype: BqPaginatorElementPrototype
  });

}).call(this);

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
    var attaches, fn, i, importDoc, j, ref, ref1, ref2, ref3, templateContent;
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
    this.shadowRoot = this.createShadowRoot();
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
    document.addEventListener('readystatechange', (function(_this) {
      return function() {
        var pg;
        if ('complete' !== document.readyState) {
          return;
        }
        if (_this.qs.has(_this.param)) {
          pg = parseInt(_this.qs.get(_this.param));
          _this.paginator.setAttribute('current', pg);
        }
        return _this.paginator.addEventListener('bq-page', function(evt) {
          _this.qs.set(_this.param, evt.detail.n);
          return window.location = _this.qs.url();
        });
      };
    })(this));
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

})();