function _arrayLikeToArray(arr, len) {
    if (len == null || len > arr.length) len = arr.length;
    for(var i = 0, arr2 = new Array(len); i < len; i++)arr2[i] = arr[i];
    return arr2;
}
function _arrayWithHoles(arr) {
    if (Array.isArray(arr)) return arr;
}
function _assertThisInitialized(self) {
    if (self === void 0) {
        throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    }
    return self;
}
function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
        throw new TypeError("Cannot call a class as a function");
    }
}
function isNativeReflectConstruct() {
    if (typeof Reflect === "undefined" || !Reflect.construct) return false;
    if (Reflect.construct.sham) return false;
    if (typeof Proxy === "function") return true;
    try {
        Date.prototype.toString.call(Reflect.construct(Date, [], function() {}));
        return true;
    } catch (e) {
        return false;
    }
}
function _construct(Parent, args, Class) {
    if (isNativeReflectConstruct()) {
        _construct = Reflect.construct;
    } else {
        _construct = function _construct(Parent, args, Class) {
            var a = [
                null
            ];
            a.push.apply(a, args);
            var Constructor = Function.bind.apply(Parent, a);
            var instance = new Constructor();
            if (Class) _setPrototypeOf(instance, Class.prototype);
            return instance;
        };
    }
    return _construct.apply(null, arguments);
}
function _defineProperties(target, props) {
    for(var i = 0; i < props.length; i++){
        var descriptor = props[i];
        descriptor.enumerable = descriptor.enumerable || false;
        descriptor.configurable = true;
        if ("value" in descriptor) descriptor.writable = true;
        Object.defineProperty(target, descriptor.key, descriptor);
    }
}
function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
}
function _getPrototypeOf(o) {
    _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
        return o.__proto__ || Object.getPrototypeOf(o);
    };
    return _getPrototypeOf(o);
}
function _inherits(subClass, superClass) {
    if (typeof superClass !== "function" && superClass !== null) {
        throw new TypeError("Super expression must either be null or a function");
    }
    subClass.prototype = Object.create(superClass && superClass.prototype, {
        constructor: {
            value: subClass,
            writable: true,
            configurable: true
        }
    });
    if (superClass) _setPrototypeOf(subClass, superClass);
}
function _isNativeFunction(fn) {
    return Function.toString.call(fn).indexOf("[native code]") !== -1;
}
function _iterableToArrayLimit(arr, i) {
    var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"];
    if (_i == null) return;
    var _arr = [];
    var _n = true;
    var _d = false;
    var _s, _e;
    try {
        for(_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true){
            _arr.push(_s.value);
            if (i && _arr.length === i) break;
        }
    } catch (err) {
        _d = true;
        _e = err;
    } finally{
        try {
            if (!_n && _i["return"] != null) _i["return"]();
        } finally{
            if (_d) throw _e;
        }
    }
    return _arr;
}
function _nonIterableRest() {
    throw new TypeError("Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}
function _possibleConstructorReturn(self, call) {
    if (call && (_typeof(call) === "object" || typeof call === "function")) {
        return call;
    }
    return _assertThisInitialized(self);
}
function _setPrototypeOf(o, p) {
    _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
        o.__proto__ = p;
        return o;
    };
    return _setPrototypeOf(o, p);
}
function _slicedToArray(arr, i) {
    return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest();
}
var _typeof = function(obj) {
    "@swc/helpers - typeof";
    return obj && typeof Symbol !== "undefined" && obj.constructor === Symbol ? "symbol" : typeof obj;
};
function _unsupportedIterableToArray(o, minLen) {
    if (!o) return;
    if (typeof o === "string") return _arrayLikeToArray(o, minLen);
    var n = Object.prototype.toString.call(o).slice(8, -1);
    if (n === "Object" && o.constructor) n = o.constructor.name;
    if (n === "Map" || n === "Set") return Array.from(n);
    if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}
function _wrapNativeSuper(Class) {
    var _cache = typeof Map === "function" ? new Map() : undefined;
    _wrapNativeSuper = function _wrapNativeSuper(Class) {
        if (Class === null || !_isNativeFunction(Class)) return Class;
        if (typeof Class !== "function") {
            throw new TypeError("Super expression must either be null or a function");
        }
        if (typeof _cache !== "undefined") {
            if (_cache.has(Class)) return _cache.get(Class);
            _cache.set(Class, Wrapper);
        }
        function Wrapper() {
            return _construct(Class, arguments, _getPrototypeOf(this).constructor);
        }
        Wrapper.prototype = Object.create(Class.prototype, {
            constructor: {
                value: Wrapper,
                enumerable: false,
                writable: true,
                configurable: true
            }
        });
        return _setPrototypeOf(Wrapper, Class);
    };
    return _wrapNativeSuper(Class);
}
function _isNativeReflectConstruct() {
    if (typeof Reflect === "undefined" || !Reflect.construct) return false;
    if (Reflect.construct.sham) return false;
    if (typeof Proxy === "function") return true;
    try {
        Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function() {}));
        return true;
    } catch (e) {
        return false;
    }
}
function _createSuper(Derived) {
    var hasNativeReflectConstruct = _isNativeReflectConstruct();
    return function _createSuperInternal() {
        var Super = _getPrototypeOf(Derived), result;
        if (hasNativeReflectConstruct) {
            var NewTarget = _getPrototypeOf(this).constructor;
            result = Reflect.construct(Super, arguments, NewTarget);
        } else {
            result = Super.apply(this, arguments);
        }
        return _possibleConstructorReturn(this, result);
    };
}
(function() {
    var Eventify = // Eventify.js
    function Eventify(el, object) {
        var eventLink = function eventLink(el2, event, object2, method) {
            el2.addEventListener(event, function(evt) {
                object2[method].call(object2, evt);
            });
        };
        var eventify = function eventify(e) {
            var pairs = e.getAttribute("data-event").split(";");
            var _iteratorNormalCompletion = true, _didIteratorError = false, _iteratorError = undefined;
            try {
                var _loop = function() {
                    var p = _step.value;
                    var _p_trim_split_map = _slicedToArray(p.trim().split(":").map(function(m) {
                        return m.trim();
                    }), 2), event = _p_trim_split_map[0], method = _p_trim_split_map[1];
                    var _iteratorNormalCompletion = true, _didIteratorError = false, _iteratorError = undefined;
                    try {
                        for(var _iterator = event.split(",").map(function(et) {
                            return et.trim();
                        })[Symbol.iterator](), _step1; !(_iteratorNormalCompletion = (_step1 = _iterator.next()).done); _iteratorNormalCompletion = true){
                            var evt = _step1.value;
                            if (!method) {
                                method = evt;
                            }
                            eventLink(e, evt, object, method);
                        }
                    } catch (err) {
                        _didIteratorError = true;
                        _iteratorError = err;
                    } finally{
                        try {
                            if (!_iteratorNormalCompletion && _iterator.return != null) {
                                _iterator.return();
                            }
                        } finally{
                            if (_didIteratorError) {
                                throw _iteratorError;
                            }
                        }
                    }
                };
                for(var _iterator = pairs[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true)_loop();
            } catch (err) {
                _didIteratorError = true;
                _iteratorError = err;
            } finally{
                try {
                    if (!_iteratorNormalCompletion && _iterator.return != null) {
                        _iterator.return();
                    }
                } finally{
                    if (_didIteratorError) {
                        throw _iteratorError;
                    }
                }
            }
        };
        if ("function" == typeof el.hasAttribute && el.hasAttribute("data-event")) {
            eventify(el);
        }
        var _iteratorNormalCompletion = true, _didIteratorError = false, _iteratorError = undefined;
        try {
            for(var _iterator = el.querySelectorAll("[data-event]")[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true){
                var e = _step.value;
                eventify(e);
            }
        } catch (err) {
            _didIteratorError = true;
            _iteratorError = err;
        } finally{
            try {
                if (!_iteratorNormalCompletion && _iterator.return != null) {
                    _iterator.return();
                }
            } finally{
                if (_didIteratorError) {
                    throw _iteratorError;
                }
            }
        }
    };
    // InstallTemplates.js
    var InstallTemplates = function() {
        var templates = {
            "BQInstall": "<template>\n	<style>:host {\n  display: flex;\n  box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);\n  padding: 1em 2em;\n  border: 1px solid #ccc;\n  flex-direction: row;\n  justify-content: space-between;\n  align-items: stretch;\n  width: 100%; }\n</style>\n	<bq-wizard>\n		<bq-wizard-database-config> </bq-wizard-database-config>\n		<bq-wizard-bulk-sms-config> </bq-wizard-bulk-sms-config>\n	</bq-wizard>\n</template>\n",
            "BQStatus": '<template>\n	<style>:host {\n  display: flex;\n  flex-direction: column;\n  justify-content: flex-start;\n  align-items: stretch;\n  width: 100%;\n  font-size: 0.8em;\n  margin: 0.6em 0;\n  padding: 0.6em 0; }\n\n:host(.ok) #ok {\n  display: block; }\n:host(.ok) #error {\n  display: none; }\n\n:host(.error) #ok {\n  display: none; }\n:host(.error) #error {\n  display: block; }\n\n#ok, #error {\n  padding: 0.6em; }\n\n#ok {\n  border: 1px solid green;\n  color: #060;\n  background-color: #dfd; }\n\n#error {\n  border: 1px solid red;\n  color: red;\n  background-color: #fff0f0; }\n\n#explain {\n  color: #666; }\n</style>\n	<div id="ok">\n	</div>\n	<div id="error">\n	</div>\n	<div id="explain">\n	</div>\n</template>\n',
            "BQWizard": '<template>\n	<style>:host {\n  --color-wizard: #6cf;\n  display: flex;\n  flex-direction: column;\n  justify-content: top;\n  align-items: stretch;\n  width: 100%; }\n\n#header {\n  display: flex;\n  flex-direction: row;\n  justify-content: flex-start;\n  align-items: stretch; }\n  #header > div {\n    background-color: #eee;\n    padding: 0.3em 1em;\n    border-top: 3px solid transparent; }\n    #header > div.display {\n      border-color: var(--color-wizard, #6cf);\n      background-color: white; }\n\nfooter {\n  display: grid;\n  grid-template-columns: 1fr repeat(2, auto);\n  grid-column-gap: 1em;\n  margin-top: 1em;\n  border-top: 1px solid #eee;\n  padding: 0.4em; }\n  footer button {\n    background-color: white;\n    border: 1px solid var(--color-wizard, #6cf);\n    padding: 0.4em 1em;\n    color: var(--color-wizard, #6cf); }\n    footer button:not([disabled]):hover {\n      background-color: var(--color-wizard, #6cf);\n      color: white; }\n    footer button:first-child {\n      justify-self: start; }\n    footer button[disabled] {\n      border-color: #666;\n      color: #666; }\n\n#main > *:not(.display) {\n  display: none; }\n</style>\n	<header id="header">\n	</header>\n	<slot id="main">\n	</slot>\n	<footer>\n		<button id="cancel" data-event="click:cancel">Cancel</button>\n		<button id="previous" data-event="click:previous" disabled="">Previous</button>\n		<button id="next" data-event="click:next">Next</button>\n	</footer>\n</template>\n',
            "BQWizardBulkSmsConfig": "<template>\n	This is the bulk sms configuration\n</template>\n",
            "BQWizardDatabaseConfig": '<template>\n	<style>:host {\n  display: flex;\n  flex-direction: column;\n  justify-content: flex-start;\n  align-items: stretch; }\n\nlabel {\n  margin-top: 0.6em;\n  margin-bottom: -0.2em;\n  font-size: 0.7em;\n  color: #666; }\n</style>	\n	<label for="server">Server</label>\n	<input type="text" id="server" placeholder="localhost" value="localhost"/>\n	<label for="username">Username</label>\n	<input type="text" id="username" placeholder="user" value="betterquiz"/>\n	<label for="password">Password</label>\n	<input-password id="password" placeholder="password" value="betterquiz"> </input-password>\n	<label for="database">Database</label>\n	<input type="text" id="database" placeholder="betterquiz" value="betterquiz"/>\n	<bq-status id="status" ok="So far so good" explain="We got some issues.">\n	</bq-status>\n</template>\n',
            "InputPassword": '<template>\n	<style>:host {\n  border: 0px solid red;\n  position: relative;\n  display: flex;\n  flex-direction: row;\n  justify-content: space-betweeen;\n  align-items: stretch;\n  gap: 0.4em; }\n\ninput {\n  flex-grow: 1; }\n\nbutton {\n  tranform: translate(-5em);\n  border-width: 0;\n  background-color: transparent;\n  color: #777; }\n  button > svg {\n    width: 1.4em; }\n  button.show svg#eye-off {\n    display: none; }\n  button.show svg#eye {\n    display: block; }\n  button:not(.show) svg#eye-off {\n    display: block; }\n  button:not(.show) svg#eye {\n    display: none; }\n</style>\n	<input id="input" placeholder="placeholder"/>\n	\n	<button id="show">\n		<svg stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" id="eye" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>\n		<svg stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" id="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>\n	</button>\n</template>\n'
        };
        var mk = function(k, html) {
            var el = document.createElement("div");
            el.innerHTML = html;
            var c = el.firstElementChild;
            while(null != c && Node.ELEMENT_NODE != c.nodeType){
                c = c.nextSibling;
            }
            if (null == c) {
                console.error("FAILED TO FIND ANY ELEMENT CHILD OF ", k, ":", el);
                return mk("error", "<em>No child elements in template " + k + "</em>");
            }
            el = c;
            if ("function" == typeof el.querySelector) {
                var et = el.querySelector('[data-set="this"]');
                if (null != et) {
                    el = et;
                    el.removeAttribute("data-set");
                }
            }
            return el;
        };
        return function(t) {
            var dest = arguments.length > 1 && arguments[1] !== void 0 ? arguments[1] : {};
            var n = templates[t];
            if ("string" == typeof n) {
                n = mk(t, n);
                templates[t] = n;
            }
            if ("undefined" == typeof n) {
                console.error("Failed to find template " + t);
                return [
                    false,
                    false
                ];
            }
            if (n.content) {
                n = n.content.cloneNode(true);
            } else {
                n = n.cloneNode(true);
            }
            try {
                for(var _i = 0, _iter = [
                    "id",
                    "data-set"
                ]; _i < _iter.length; _i++){
                    var attr = _iter[_i];
                    var nodes = Array.from(n.querySelectorAll("[" + attr + "]"));
                    if ("function" == typeof n.hasAttribute && n.hasAttribute(attr)) {
                        nodes.unshift(n);
                    }
                    var _iteratorNormalCompletion = true, _didIteratorError = false, _iteratorError = undefined;
                    try {
                        for(var _iterator = nodes[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true){
                            var el = _step.value;
                            var a = el.getAttribute(attr);
                            if (a.substr(0, 1) == "$") {
                                a = a.substr(1);
                                el = jQuery(el);
                                el.setAttribute(attr, a);
                            }
                            dest[a] = el;
                        }
                    } catch (err) {
                        _didIteratorError = true;
                        _iteratorError = err;
                    } finally{
                        try {
                            if (!_iteratorNormalCompletion && _iterator.return != null) {
                                _iterator.return();
                            }
                        } finally{
                            if (_didIteratorError) {
                                throw _iteratorError;
                            }
                        }
                    }
                }
            } catch (err1) {
                console.error("ERROR in DTemplate(" + t + "): ", err1);
                debugger;
            }
            return [
                n,
                dest
            ];
        };
    }();
    // BQStatus.js
    var BQStatus = /*#__PURE__*/ function(HTMLElement1) {
        "use strict";
        _inherits(BQStatus, HTMLElement1);
        var _super = _createSuper(BQStatus);
        function BQStatus() {
            _classCallCheck(this, BQStatus);
            var _this;
            _this = _super.call(this);
            var ref;
            ref = _slicedToArray(InstallTemplates("BQStatus"), 2), _this.el = ref[0], _this.$ = ref[1], ref;
            _this.attachShadow({
                mode: "open"
            });
            _this.shadowRoot.appendChild(_this.el);
            return _this;
        }
        _createClass(BQStatus, [
            {
                key: "connectedCallback",
                value: function connectedCallback() {
                    this.setError(this.getAttribute("error"));
                    this.setOk(this.getAttribute("ok"));
                    this.$.explain.innerText = this.getAttribute("explain");
                }
            },
            {
                key: "setError",
                value: function setError(error) {
                    if (!error) return;
                    console.log("BQStauts::setError(".concat(error, ")"));
                    this.classList.remove("ok");
                    this.classList.add("error");
                    this.$.error.innerText = error;
                    this.$.ok.innerText = "";
                }
            },
            {
                key: "setOk",
                value: function setOk(ok) {
                    if (!ok) return;
                    console.log("BQStatus.setOk(".concat(ok, ")"));
                    this.classList.remove("error");
                    this.classList.add("ok");
                    this.$.ok.innerText = ok;
                }
            },
            {
                key: "attributeChangedCallback",
                value: function attributeChangedCallback(attr, oldValue, newValue) {
                    switch(attr){
                        case "explain":
                            this.$.explain.innerText = newValue;
                            break;
                        case "error":
                            this.setError(newValue);
                            break;
                        case "ok":
                            this.setOk(newValue);
                            break;
                    }
                }
            }
        ], [
            {
                key: "observedAttributes",
                get: function get() {
                    return [
                        "explain",
                        "error",
                        "ok"
                    ];
                }
            }
        ]);
        return BQStatus;
    }(_wrapNativeSuper(HTMLElement));
    customElements.define("bq-status", BQStatus);
    // BQWizard.js
    var WizardTab = /*#__PURE__*/ function() {
        "use strict";
        function WizardTab(tab, titleEl) {
            _classCallCheck(this, WizardTab);
            this.tab = tab;
            this.titleEl = titleEl;
            this._display = this.tab.style.display;
        }
        _createClass(WizardTab, [
            {
                key: "display",
                value: function display(bDisplay) {
                    if (bDisplay) {
                        this.tab.style.display = this._display;
                    } else {
                        this._display = this.tab.style.display;
                        this.tab.style.display = "none";
                    }
                    var method = bDisplay ? "add" : "remove";
                    this.titleEl.classList[method]("display");
                }
            },
            {
                key: "dispatchEvent",
                value: function dispatchEvent(ce) {
                    this.tab.dispatchEvent(ce);
                }
            },
            {
                key: "id",
                get: function get() {
                    return this.tab.getId();
                }
            }
        ]);
        return WizardTab;
    }();
    var BQWizard = /*#__PURE__*/ function(HTMLElement1) {
        "use strict";
        _inherits(BQWizard1, HTMLElement1);
        var _super = _createSuper(BQWizard1);
        function BQWizard1() {
            _classCallCheck(this, BQWizard1);
            var _this;
            _this = _super.call(this);
            var ref;
            ref = _slicedToArray(InstallTemplates("BQWizard"), 2), _this.el = ref[0], _this.$ = ref[1], ref;
            _this.attachShadow({
                mode: "open"
            });
            _this.shadowRoot.appendChild(_this.el);
            _this.addEventListener("wizard-add-tab", function(evt) {
                return _this.addTab(evt);
            });
            _this.addEventListener("wizard-enable-tab", function(evt) {
                return _this.enableTab(evt);
            });
            _this.addEventListener("wizard-show-tab", function(evt) {
                return _this.showTab(evt);
            });
            _this.addEventListener("wizard-set-next-text", function(evt) {
                return _this.setNextText(evt);
            });
            _this.addEventListener("wizard-disable-buttons", function(evt) {
                return _this.disableButtons(evt);
            });
            _this.addEventListener("wizard-set-working", function(evt) {
                return _this.setWorking(evt);
            });
            _this.tabs = [];
            _this.activeTab = false;
            Eventify(_this.shadowRoot, _assertThisInitialized(_this));
            return _this;
        }
        _createClass(BQWizard1, [
            {
                key: "connectedCallback",
                value: function connectedCallback() {}
            },
            {
                key: "addTab",
                value: function addTab(evt) {
                    evt.stopPropagation();
                    var tab = evt.detail;
                    var title = document.createElement("div");
                    var titleContent = tab.getTitleText();
                    if ("string" == typeof titleContent) {
                        title.innerText = titleContent;
                    } else {
                        title = document.createElement("slot");
                        title.appendChild(titleContent);
                    }
                    this.$.header.appendChild(title);
                    var wizTab = new WizardTab(tab, title);
                    if (!this.activeTab) {
                        this.activeTab = wizTab;
                        wizTab.display(true);
                    } else {
                        wizTab.display(false);
                    }
                    this.tabs.push(wizTab);
                }
            },
            {
                key: "enableTab",
                value: function enableTab(evt) {
                    evt.stopPropagation();
                }
            },
            {
                key: "showTab",
                value: function showTab(evt) {
                    evt.stopPropagation();
                    var id = evt.detail.id;
                    console.log("BQWizard: Going to show Tab ".concat(id));
                    this.tabs.map(function(t) {
                        return console.log("tab has id ".concat(t.id));
                    });
                    var nextTab = this.tabs.filter(function(t) {
                        return t.id == id;
                    });
                    if (0 == nextTab.length) {
                        console.error("Requested tab with id ".concat(id, ", but no such tab found"));
                        return;
                    }
                    this.activeTab.display(false);
                    this.activeTab = nextTab[0];
                    this.activeTab.display(true);
                }
            },
            {
                key: "setWorking",
                value: function setWorking(evt) {
                    var working = evt.detail.working;
                    console.log("BQWizard::setWorking w working=".concat(working));
                }
            },
            {
                key: "setNextText",
                value: function setNextText(evt) {
                    evt.stopPropagation();
                    var next = evt.detail;
                    if ("string" == typeof next) {
                        this.$.next.innerText = next;
                    } else {
                        console.warn("setNextText() called with non-string parameter ", next);
                        this.$.next.innerHTML = "";
                        this.$.next.appendChild(next);
                    }
                }
            },
            {
                key: "disableButtons",
                value: function disableButtons(evt) {
                    evt.stopPropagation();
                    var disable = function(btn, disable2) {
                        if (disable2) {
                            btn.setAttribute("disabled", "disabled");
                        } else {
                            btn.removeAttribute("disabled");
                        }
                    };
                    disable(this.$.next, evt.detail.next);
                    disable(this.$.canccel, evt.detail.cancel);
                    disable(this.$.previous, evt.detail.previous);
                }
            },
            {
                key: "previous",
                value: function previous(evt) {
                    evt.stopPropagation();
                    evt.preventDefault();
                    console.log("previous clicked");
                }
            },
            {
                key: "cancel",
                value: function cancel(evt) {
                    evt.stopPropagation();
                    evt.preventDefault();
                    console.log("cancel clicked");
                }
            },
            {
                key: "next",
                value: function next(evt) {
                    evt.stopPropagation();
                    evt.preventDefault();
                    this.activeTab.dispatchEvent(new CustomEvent("wizard-tab-next", {
                        bubbles: true,
                        composed: true,
                        cancelable: true,
                        detail: this
                    }));
                }
            }
        ], [
            {
                key: "showTag",
                value: function showTag(el, tagId) {
                    el.dispatchEvent(new CustomEvent("wizard-show-tab", {
                        bubbles: true,
                        composed: true,
                        cancelable: true,
                        detail: {
                            source: el,
                            id: tagId
                        }
                    }));
                }
            },
            {
                key: "sendSetWorking",
                value: function sendSetWorking(el, working) {
                    el.dispatchEvent(new CustomEvent("wizard-set-working", {
                        bubbles: true,
                        composed: true,
                        cancelable: true,
                        detail: {
                            source: el,
                            working: working
                        }
                    }));
                }
            },
            {
                key: "startWorking",
                value: function startWorking(el) {
                    BQWizard.sendSetWorking(el, true);
                }
            },
            {
                key: "stopWorking",
                value: function stopWorking(el) {
                    BQWizard.sendSetWorking(el, false);
                }
            }
        ]);
        return BQWizard1;
    }(_wrapNativeSuper(HTMLElement));
    customElements.define("bq-wizard", BQWizard);
    // BQWizardDatabaseConfig.js
    var BQWizardDatabaseConfig = /*#__PURE__*/ function(HTMLElement1) {
        "use strict";
        _inherits(BQWizardDatabaseConfig, HTMLElement1);
        var _super = _createSuper(BQWizardDatabaseConfig);
        function BQWizardDatabaseConfig() {
            _classCallCheck(this, BQWizardDatabaseConfig);
            var _this;
            _this = _super.call(this);
            var ref;
            ref = _slicedToArray(InstallTemplates("BQWizardDatabaseConfig"), 2), _this.el = ref[0], _this.$ = ref[1], ref;
            _this.attachShadow({
                mode: "open"
            });
            _this.shadowRoot.appendChild(_this.el);
            _this.addEventListener("wizard-tab-next", function(evt) {
                return _this.nextPressed(evt);
            });
            return _this;
        }
        _createClass(BQWizardDatabaseConfig, [
            {
                key: "connectedCallback",
                value: function connectedCallback() {
                    console.log("BQWizardDatabaseConfig::connectedCallback()");
                    this.dispatchEvent(new CustomEvent("wizard-add-tab", {
                        composed: true,
                        bubbles: true,
                        cancelable: true,
                        detail: this
                    }));
                }
            },
            {
                key: "getTitleText",
                value: function getTitleText() {
                    return "Database";
                }
            },
            {
                key: "getId",
                value: function getId() {
                    return "database";
                }
            },
            {
                key: "nextPressed",
                value: function nextPressed(evt) {
                    var _this = this;
                    evt.stopPropagation();
                    BQWizard.startWorking(this);
                    var server = this.$.server.value;
                    var username = this.$.username.value;
                    var password = this.$.password.value;
                    var database = this.$.database.value;
                    window.BQApi.CheckConfigureDatabase(server, username, password, database).then(function(res) {
                        console.log("BQApi.ConfigDatabase = ", res);
                        if (res.error_code) {
                            _this.$.status.setAttribute("explain", "These settings don't seem to be right. Please check them and try again.");
                            _this.$.status.setAttribute("error", res.error);
                        } else {
                            _this.$.status.setAttribute("ok", "We've configured the database.");
                            BQWizard.showTag(_this, "bulksms");
                        }
                    }).finally(function() {
                        BQWizard.stopWorking(_this);
                    });
                    console.log("BQWizardDatabaseConfig: nextPressed");
                }
            }
        ]);
        return BQWizardDatabaseConfig;
    }(_wrapNativeSuper(HTMLElement));
    customElements.define("bq-wizard-database-config", BQWizardDatabaseConfig);
    // BQWizardBulkSmsConfig.js
    var BQWizardBulkSmsConfig = /*#__PURE__*/ function(HTMLElement1) {
        "use strict";
        _inherits(BQWizardBulkSmsConfig, HTMLElement1);
        var _super = _createSuper(BQWizardBulkSmsConfig);
        function BQWizardBulkSmsConfig() {
            _classCallCheck(this, BQWizardBulkSmsConfig);
            var _this;
            _this = _super.call(this);
            var ref;
            ref = _slicedToArray(InstallTemplates("BQWizardBulkSmsConfig"), 2), _this.el = ref[0], _this.$ = ref[1], ref;
            _this.attachShadow({
                mode: "open"
            });
            _this.shadowRoot.appendChild(_this.el);
            return _this;
        }
        _createClass(BQWizardBulkSmsConfig, [
            {
                key: "connectedCallback",
                value: function connectedCallback() {
                    this.dispatchEvent(new CustomEvent("wizard-add-tab", {
                        composed: true,
                        bubbles: true,
                        cancelable: true,
                        detail: this
                    }));
                }
            },
            {
                key: "getTitleText",
                value: function getTitleText() {
                    return "BulkSMS";
                }
            },
            {
                key: "getId",
                value: function getId() {
                    return "bulksms";
                }
            }
        ]);
        return BQWizardBulkSmsConfig;
    }(_wrapNativeSuper(HTMLElement));
    customElements.define("bq-wizard-bulk-sms-config", BQWizardBulkSmsConfig);
    // InputPassword.js
    var InputPassword = /*#__PURE__*/ function(HTMLElement1) {
        "use strict";
        _inherits(InputPassword, HTMLElement1);
        var _super = _createSuper(InputPassword);
        function InputPassword() {
            _classCallCheck(this, InputPassword);
            var _this;
            _this = _super.call(this);
            _this.setAttribute("type", "password");
            var ref;
            ref = _slicedToArray(InstallTemplates("InputPassword"), 2), _this.el = ref[0], _this.$ = ref[1], ref;
            _this.attachShadow({
                mode: "open"
            });
            _this.shadowRoot.appendChild(_this.el);
            _this.$.show.addEventListener("click", function(evt) {
                return _this.toggleShow(evt);
            });
            _this.show(true);
            return _this;
        }
        _createClass(InputPassword, [
            {
                key: "connectedCallback",
                value: function connectedCallback() {
                    this.$.input.setAttribute("placeholder", this.getAttribute("placeholder"));
                    this.$.input.value = this.getAttribute("value");
                }
            },
            {
                key: "value",
                get: function get() {
                    return this.$.input.value;
                }
            },
            {
                key: "show",
                value: function show() {
                    var hide = arguments.length > 0 && arguments[0] !== void 0 ? arguments[0] : false;
                    this.$.input.setAttribute("type", hide ? "password" : "input");
                    this.$.show.classList[hide ? "add" : "remove"]("show");
                }
            },
            {
                key: "isShown",
                value: function isShown() {
                    console.log("this.$.input.getAttribute('type')==".concat(this.$.input.getAttribute("type")));
                    return "input" == this.$.input.getAttribute("type");
                }
            },
            {
                key: "toggleShow",
                value: function toggleShow(evt) {
                    if (evt) {
                        evt.preventDefault();
                    }
                    this.show(this.isShown());
                }
            }
        ]);
        return InputPassword;
    }(_wrapNativeSuper(HTMLElement));
    customElements.define("input-password", InputPassword);
    // BQInstall.js
    var BQInstall = /*#__PURE__*/ function(HTMLElement1) {
        "use strict";
        _inherits(BQInstall, HTMLElement1);
        var _super = _createSuper(BQInstall);
        function BQInstall() {
            _classCallCheck(this, BQInstall);
            var _this;
            _this = _super.call(this);
            var ref;
            ref = _slicedToArray(InstallTemplates("BQInstall"), 2), _this.el = ref[0], _this.$ = ref[1], ref;
            _this.attachShadow({
                mode: "open"
            });
            _this.shadowRoot.appendChild(_this.el);
            return _this;
        }
        _createClass(BQInstall, [
            {
                key: "connectedCallback",
                value: function connectedCallback() {
                    if (!window.BQApi) {
                        console.error("BQApi is not defined");
                    } else {
                        window.BQApi.CheckDatabase().then(function(res) {
                            console.log("BQApi.CheckDatabase = ", res);
                        });
                        window.BQApi.Panacea().then(function(res) {
                            console.log("BQApi.Panacea = ", res);
                        });
                    }
                }
            },
            {
                key: "API",
                value: function API() {
                    return window.BQApi;
                }
            }
        ]);
        return BQInstall;
    }(_wrapNativeSuper(HTMLElement));
    customElements.define("bq-install", BQInstall);
})(); //# sourceMappingURL=BQInstall.es6.js.map


//# sourceMappingURL=BQInstall.js.map