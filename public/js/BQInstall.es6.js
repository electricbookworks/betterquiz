(() => {
  // InstallTemplates.js
  var InstallTemplates = function() {
    let templates = { "BQInstall": "<template>\n	<style>:host {\n  display: flex;\n  box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);\n  padding: 1em 2em;\n  border: 1px solid #ccc;\n  flex-direction: row;\n  justify-content: space-between;\n  align-items: stretch;\n  width: 100%; }\n</style>\n	<bq-wizard>\n		<bq-wizard-database-config> </bq-wizard-database-config>\n		<bq-wizard-bulk-sms-config> </bq-wizard-bulk-sms-config>\n	</bq-wizard>\n</template>\n", "BQStatus": '<template>\n	<style>:host {\n  display: flex;\n  flex-direction: column;\n  justify-content: flex-start;\n  align-items: stretch;\n  width: 100%;\n  font-size: 0.8em;\n  margin: 0.6em 0;\n  padding: 0.6em 0; }\n\n:host(.ok) #ok {\n  display: block; }\n:host(.ok) #error {\n  display: none; }\n\n:host(.error) #ok {\n  display: none; }\n:host(.error) #error {\n  display: block; }\n\n#ok, #error {\n  padding: 0.6em; }\n\n#ok {\n  border: 1px solid green;\n  color: #060;\n  background-color: #dfd; }\n\n#error {\n  border: 1px solid red;\n  color: red;\n  background-color: #fff0f0; }\n\n#explain {\n  color: #666; }\n</style>\n	<div id="ok">\n	</div>\n	<div id="error">\n	</div>\n	<div id="explain">\n	</div>\n</template>\n', "BQWizard": '<template>\n	<style>:host {\n  --color-wizard: #6cf;\n  display: flex;\n  flex-direction: column;\n  justify-content: top;\n  align-items: stretch;\n  width: 100%; }\n\n#header {\n  display: flex;\n  flex-direction: row;\n  justify-content: flex-start;\n  align-items: stretch; }\n  #header > div {\n    background-color: #eee;\n    padding: 0.3em 1em;\n    border-top: 3px solid transparent; }\n    #header > div.display {\n      border-color: var(--color-wizard, #6cf);\n      background-color: white; }\n\nfooter {\n  display: grid;\n  grid-template-columns: 1fr repeat(2, auto);\n  grid-column-gap: 1em;\n  margin-top: 1em;\n  border-top: 1px solid #eee;\n  padding: 0.4em; }\n  footer button {\n    background-color: white;\n    border: 1px solid var(--color-wizard, #6cf);\n    padding: 0.4em 1em;\n    color: var(--color-wizard, #6cf); }\n    footer button:not([disabled]):hover {\n      background-color: var(--color-wizard, #6cf);\n      color: white; }\n    footer button:first-child {\n      justify-self: start; }\n    footer button[disabled] {\n      border-color: #666;\n      color: #666; }\n\n#main > *:not(.display) {\n  display: none; }\n</style>\n	<header id="header">\n	</header>\n	<slot id="main">\n	</slot>\n	<footer>\n		<button id="cancel" data-event="click:cancel">Cancel</button>\n		<button id="previous" data-event="click:previous" disabled="">Previous</button>\n		<button id="next" data-event="click:next">Next</button>\n	</footer>\n</template>\n', "BQWizardBulkSmsConfig": "<template>\n	This is the bulk sms configuration\n</template>\n", "BQWizardDatabaseConfig": '<template>\n	<style>:host {\n  display: flex;\n  flex-direction: column;\n  justify-content: flex-start;\n  align-items: stretch; }\n\nlabel {\n  margin-top: 0.6em;\n  margin-bottom: -0.2em;\n  font-size: 0.7em;\n  color: #666; }\n</style>	\n	<label for="server">Server</label>\n	<input type="text" id="server" placeholder="localhost" value="localhost"/>\n	<label for="username">Username</label>\n	<input type="text" id="username" placeholder="user" value="betterquiz"/>\n	<label for="password">Password</label>\n	<input-password id="password" placeholder="password" value="betterquiz"> </input-password>\n	<label for="database">Database</label>\n	<input type="text" id="database" placeholder="betterquiz" value="betterquiz"/>\n	<bq-status id="status" ok="So far so good" explain="We got some issues.">\n	</bq-status>\n</template>\n', "InputPassword": '<template>\n	<style>:host {\n  border: 0px solid red;\n  position: relative;\n  display: flex;\n  flex-direction: row;\n  justify-content: space-betweeen;\n  align-items: stretch;\n  gap: 0.4em; }\n\ninput {\n  flex-grow: 1; }\n\nbutton {\n  tranform: translate(-5em);\n  border-width: 0;\n  background-color: transparent;\n  color: #777; }\n  button > svg {\n    width: 1.4em; }\n  button.show svg#eye-off {\n    display: none; }\n  button.show svg#eye {\n    display: block; }\n  button:not(.show) svg#eye-off {\n    display: block; }\n  button:not(.show) svg#eye {\n    display: none; }\n</style>\n	<input id="input" placeholder="placeholder"/>\n	\n	<button id="show">\n		<svg stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" id="eye" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>\n		<svg stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" id="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>\n	</button>\n</template>\n' };
    let mk = function(k, html) {
      let el = document.createElement("div");
      el.innerHTML = html;
      let c = el.firstElementChild;
      while (null != c && Node.ELEMENT_NODE != c.nodeType) {
        c = c.nextSibling;
      }
      if (null == c) {
        console.error("FAILED TO FIND ANY ELEMENT CHILD OF ", k, ":", el);
        return mk("error", "<em>No child elements in template " + k + "</em>");
      }
      el = c;
      if ("function" == typeof el.querySelector) {
        let et = el.querySelector('[data-set="this"]');
        if (null != et) {
          el = et;
          el.removeAttribute("data-set");
        }
      }
      return el;
    };
    return function(t, dest = {}) {
      let n = templates[t];
      if ("string" == typeof n) {
        n = mk(t, n);
        templates[t] = n;
      }
      if ("undefined" == typeof n) {
        console.error("Failed to find template " + t);
        return [false, false];
      }
      if (n.content) {
        n = n.content.cloneNode(true);
      } else {
        n = n.cloneNode(true);
      }
      try {
        for (let attr of ["id", "data-set"]) {
          let nodes = Array.from(n.querySelectorAll("[" + attr + "]"));
          if ("function" == typeof n.hasAttribute && n.hasAttribute(attr)) {
            nodes.unshift(n);
          }
          for (let el of nodes) {
            let a = el.getAttribute(attr);
            if (a.substr(0, 1) == "$") {
              a = a.substr(1);
              el = jQuery(el);
              el.setAttribute(attr, a);
            }
            dest[a] = el;
          }
        }
      } catch (err) {
        console.error("ERROR in DTemplate(" + t + "): ", err);
        debugger;
      }
      return [n, dest];
    };
  }();

  // BQStatus.js
  var BQStatus = class extends HTMLElement {
    constructor() {
      super();
      [this.el, this.$] = InstallTemplates(`BQStatus`);
      this.attachShadow({ mode: `open` });
      this.shadowRoot.appendChild(this.el);
    }
    connectedCallback() {
      this.setError(this.getAttribute(`error`));
      this.setOk(this.getAttribute(`ok`));
      this.$.explain.innerText = this.getAttribute(`explain`);
    }
    setError(error) {
      if (!error)
        return;
      console.log(`BQStauts::setError(${error})`);
      this.classList.remove(`ok`);
      this.classList.add(`error`);
      this.$.error.innerText = error;
      this.$.ok.innerText = ``;
    }
    setOk(ok) {
      if (!ok)
        return;
      console.log(`BQStatus.setOk(${ok})`);
      this.classList.remove(`error`);
      this.classList.add(`ok`);
      this.$.ok.innerText = ok;
    }
    attributeChangedCallback(attr, oldValue, newValue) {
      switch (attr) {
        case `explain`:
          this.$.explain.innerText = newValue;
          break;
        case `error`:
          this.setError(newValue);
          break;
        case `ok`:
          this.setOk(newValue);
          break;
      }
    }
    static get observedAttributes() {
      return [`explain`, `error`, `ok`];
    }
  };
  customElements.define(`bq-status`, BQStatus);

  // Eventify.js
  function Eventify(el, object) {
    let eventLink = function(el2, event, object2, method) {
      el2.addEventListener(event, (evt) => {
        object2[method].call(object2, evt);
      });
    };
    let eventify = function(e) {
      let pairs = e.getAttribute(`data-event`).split(`;`);
      for (let p of pairs) {
        let [event, method] = p.trim().split(`:`).map((m) => m.trim());
        for (let evt of event.split(`,`).map((et) => et.trim())) {
          if (!method) {
            method = evt;
          }
          eventLink(e, evt, object, method);
        }
      }
    };
    if (`function` == typeof el.hasAttribute && el.hasAttribute(`data-event`)) {
      eventify(el);
    }
    for (let e of el.querySelectorAll(`[data-event]`))
      eventify(e);
  }

  // BQWizard.js
  var WizardTab = class {
    constructor(tab, titleEl) {
      this.tab = tab;
      this.titleEl = titleEl;
      this._display = this.tab.style.display;
    }
    display(bDisplay) {
      if (bDisplay) {
        this.tab.style.display = this._display;
      } else {
        this._display = this.tab.style.display;
        this.tab.style.display = `none`;
      }
      let method = bDisplay ? "add" : "remove";
      this.titleEl.classList[method](`display`);
    }
    dispatchEvent(ce) {
      this.tab.dispatchEvent(ce);
    }
    get id() {
      return this.tab.getId();
    }
  };
  var BQWizard = class extends HTMLElement {
    constructor() {
      super();
      [this.el, this.$] = InstallTemplates(`BQWizard`);
      this.attachShadow({ mode: "open" });
      this.shadowRoot.appendChild(this.el);
      this.addEventListener(`wizard-add-tab`, (evt) => this.addTab(evt));
      this.addEventListener(`wizard-enable-tab`, (evt) => this.enableTab(evt));
      this.addEventListener(`wizard-show-tab`, (evt) => this.showTab(evt));
      this.addEventListener(`wizard-set-next-text`, (evt) => this.setNextText(evt));
      this.addEventListener(`wizard-disable-buttons`, (evt) => this.disableButtons(evt));
      this.addEventListener(`wizard-set-working`, (evt) => this.setWorking(evt));
      this.tabs = [];
      this.activeTab = false;
      Eventify(this.shadowRoot, this);
    }
    connectedCallback() {
    }
    addTab(evt) {
      evt.stopPropagation();
      let tab = evt.detail;
      let title = document.createElement(`div`);
      let titleContent = tab.getTitleText();
      if (`string` == typeof titleContent) {
        title.innerText = titleContent;
      } else {
        title = document.createElement(`slot`);
        title.appendChild(titleContent);
      }
      this.$.header.appendChild(title);
      let wizTab = new WizardTab(tab, title);
      if (!this.activeTab) {
        this.activeTab = wizTab;
        wizTab.display(true);
      } else {
        wizTab.display(false);
      }
      this.tabs.push(wizTab);
    }
    enableTab(evt) {
      evt.stopPropagation();
    }
    showTab(evt) {
      evt.stopPropagation();
      let id = evt.detail.id;
      console.log(`BQWizard: Going to show Tab ${id}`);
      this.tabs.map((t) => console.log(`tab has id ${t.id}`));
      let nextTab = this.tabs.filter((t) => t.id == id);
      if (0 == nextTab.length) {
        console.error(`Requested tab with id ${id}, but no such tab found`);
        return;
      }
      this.activeTab.display(false);
      this.activeTab = nextTab[0];
      this.activeTab.display(true);
    }
    static showTag(el, tagId) {
      el.dispatchEvent(new CustomEvent(`wizard-show-tab`, {
        bubbles: true,
        composed: true,
        cancelable: true,
        detail: { source: el, id: tagId }
      }));
    }
    static sendSetWorking(el, working) {
      el.dispatchEvent(new CustomEvent(`wizard-set-working`, {
        bubbles: true,
        composed: true,
        cancelable: true,
        detail: { source: el, working }
      }));
    }
    static startWorking(el) {
      BQWizard.sendSetWorking(el, true);
    }
    static stopWorking(el) {
      BQWizard.sendSetWorking(el, false);
    }
    setWorking(evt) {
      let working = evt.detail.working;
      console.log(`BQWizard::setWorking w working=${working}`);
    }
    setNextText(evt) {
      evt.stopPropagation();
      let next = evt.detail;
      if ("string" == typeof next) {
        this.$.next.innerText = next;
      } else {
        console.warn(`setNextText() called with non-string parameter `, next);
        this.$.next.innerHTML = ``;
        this.$.next.appendChild(next);
      }
    }
    disableButtons(evt) {
      evt.stopPropagation();
      let disable = (btn, disable2) => {
        if (disable2) {
          btn.setAttribute(`disabled`, `disabled`);
        } else {
          btn.removeAttribute(`disabled`);
        }
      };
      disable(this.$.next, evt.detail.next);
      disable(this.$.canccel, evt.detail.cancel);
      disable(this.$.previous, evt.detail.previous);
    }
    previous(evt) {
      evt.stopPropagation();
      evt.preventDefault();
      console.log(`previous clicked`);
    }
    cancel(evt) {
      evt.stopPropagation();
      evt.preventDefault();
      console.log(`cancel clicked`);
    }
    next(evt) {
      evt.stopPropagation();
      evt.preventDefault();
      this.activeTab.dispatchEvent(new CustomEvent(`wizard-tab-next`, {
        bubbles: true,
        composed: true,
        cancelable: true,
        detail: this
      }));
    }
  };
  customElements.define(`bq-wizard`, BQWizard);

  // BQWizardDatabaseConfig.js
  var BQWizardDatabaseConfig = class extends HTMLElement {
    constructor() {
      super();
      [this.el, this.$] = InstallTemplates(`BQWizardDatabaseConfig`);
      this.attachShadow({ mode: `open` });
      this.shadowRoot.appendChild(this.el);
      this.addEventListener(`wizard-tab-next`, (evt) => this.nextPressed(evt));
    }
    connectedCallback() {
      console.log(`BQWizardDatabaseConfig::connectedCallback()`);
      this.dispatchEvent(new CustomEvent(`wizard-add-tab`, {
        composed: true,
        bubbles: true,
        cancelable: true,
        detail: this
      }));
    }
    getTitleText() {
      return `Database`;
    }
    getId() {
      return `database`;
    }
    nextPressed(evt) {
      evt.stopPropagation();
      BQWizard.startWorking(this);
      let server = this.$.server.value;
      let username = this.$.username.value;
      let password = this.$.password.value;
      let database = this.$.database.value;
      window.BQApi.CheckConfigureDatabase(server, username, password, database).then(
        (res) => {
          console.log(`BQApi.ConfigDatabase = `, res);
          if (res.error_code) {
            this.$.status.setAttribute("explain", `These settings don't seem to be right. Please check them and try again.`);
            this.$.status.setAttribute("error", res.error);
          } else {
            this.$.status.setAttribute(`ok`, `We've configured the database.`);
            BQWizard.showTag(this, `bulksms`);
          }
        }
      ).finally(() => {
        BQWizard.stopWorking(this);
      });
      console.log(`BQWizardDatabaseConfig: nextPressed`);
    }
  };
  customElements.define(`bq-wizard-database-config`, BQWizardDatabaseConfig);

  // BQWizardBulkSmsConfig.js
  var BQWizardBulkSmsConfig = class extends HTMLElement {
    constructor() {
      super();
      [this.el, this.$] = InstallTemplates(`BQWizardBulkSmsConfig`);
      this.attachShadow({ mode: `open` });
      this.shadowRoot.appendChild(this.el);
    }
    connectedCallback() {
      this.dispatchEvent(new CustomEvent(`wizard-add-tab`, {
        composed: true,
        bubbles: true,
        cancelable: true,
        detail: this
      }));
    }
    getTitleText() {
      return `BulkSMS`;
    }
    getId() {
      return `bulksms`;
    }
  };
  customElements.define(`bq-wizard-bulk-sms-config`, BQWizardBulkSmsConfig);

  // InputPassword.js
  var InputPassword = class extends HTMLElement {
    constructor() {
      super();
      this.setAttribute(`type`, `password`);
      [this.el, this.$] = InstallTemplates(`InputPassword`);
      this.attachShadow({ mode: `open` });
      this.shadowRoot.appendChild(this.el);
      this.$.show.addEventListener(`click`, (evt) => this.toggleShow(evt));
      this.show(true);
    }
    connectedCallback() {
      this.$.input.setAttribute(`placeholder`, this.getAttribute(`placeholder`));
      this.$.input.value = this.getAttribute(`value`);
    }
    get value() {
      return this.$.input.value;
    }
    show(hide = false) {
      this.$.input.setAttribute(`type`, hide ? "password" : "input");
      this.$.show.classList[hide ? "add" : "remove"]("show");
    }
    isShown() {
      console.log(`this.$.input.getAttribute('type')==${this.$.input.getAttribute(`type`)}`);
      return "input" == this.$.input.getAttribute(`type`);
    }
    toggleShow(evt) {
      if (evt) {
        evt.preventDefault();
      }
      this.show(this.isShown());
    }
  };
  customElements.define(`input-password`, InputPassword);

  // BQInstall.js
  var BQInstall = class extends HTMLElement {
    constructor() {
      super();
      [this.el, this.$] = InstallTemplates(`BQInstall`);
      this.attachShadow({ mode: "open" });
      this.shadowRoot.appendChild(this.el);
    }
    connectedCallback() {
      if (!window.BQApi) {
        console.error(`BQApi is not defined`);
      } else {
        window.BQApi.CheckDatabase().then(
          (res) => {
            console.log(`BQApi.CheckDatabase = `, res);
          }
        );
        window.BQApi.Panacea().then(
          (res) => {
            console.log(`BQApi.Panacea = `, res);
          }
        );
      }
    }
    API() {
      return window.BQApi;
    }
  };
  customElements.define(`bq-install`, BQInstall);
})();
//# sourceMappingURL=BQInstall.es6.js.map
