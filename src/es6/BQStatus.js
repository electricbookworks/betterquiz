import {InstallTemplates} from './InstallTemplates.js';

export class BQStatus extends HTMLElement {
	constructor() {
		super();
		[this.el, this.$] = InstallTemplates(`BQStatus`);
		this.attachShadow({mode:`open`});
		this.shadowRoot.appendChild(this.el);
	}
	connectedCallback() {
		this.setError(this.getAttribute(`error`));
		this.setOk(this.getAttribute(`ok`));
		this.$.explain.innerText = this.getAttribute(`explain`);
	}

	setError(error) {
		if (!error) return;
		console.log(`BQStauts::setError(${error})`);
		this.classList.remove(`ok`);
		this.classList.add(`error`);
		this.$.error.innerText = error;
		this.$.ok.innerText = ``;
	}
	setOk(ok) {
		if (!ok) return;
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
		return [`explain`,`error`,`ok`];
	}
}
customElements.define(`bq-status`, BQStatus);