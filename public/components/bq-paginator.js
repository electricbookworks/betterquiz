(function() {
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
          console.log('Clicked page ', n);
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
      lastPage = Math.ceil(totalItems / (1.0 * itemsPerPage) - 1);
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
    if ('function' === typeof this.attachShadowRoot) {
      this.shadowRoot = this.attachShadowRoot({
        mode: 'open'
      });
    } else {
      this.shadowRoot = this.createShadowRoot();
    }
    this.el = templateContent.cloneNode(true);
    this.$ = {};
    ref3 = this.el.querySelectorAll('[data-set]');
    for (j = 0, len = ref3.length; j < len; j++) {
      e = ref3[j];
      this.$[e.getAttribute("data-set")] = e;
    }
    this.paginator = new Paginator(this.$.ul, this);
    this.shadowRoot.appendChild(this.el);
  };

  attrs = {
    'current': function(oldVal, newVal) {
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
