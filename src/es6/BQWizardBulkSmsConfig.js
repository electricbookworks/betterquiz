import {InstallTemplates} from './InstallTemplates.js';

class BQWizardBulkSmsConfig extends HTMLElement {
	constructor() {
		super();
		[this.el, this.$] = InstallTemplates(`BQWizardBulkSmsConfig`);
		this.attachShadow({mode:`open`});
		this.shadowRoot.appendChild(this.el);
	}
	connectedCallback() {
		this.dispatchEvent(new CustomEvent(`wizard-add-tab`, {
			composed:true, bubbles:true, cancelable: true,
			detail: this
		}));
	}
	getTitleText() {
		return `BulkSMS`;
	}
	getId() {
		return `bulksms`;
	}

}
customElements.define(`bq-wizard-bulk-sms-config`, BQWizardBulkSmsConfig);