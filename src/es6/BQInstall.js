import {InstallTemplates} from './InstallTemplates.js';
import {BQStatus} from './BQStatus.js';
import {BQWizard} from './BQWizard.js';
import {BQWizardDatabaseConfig} from './BQWizardDatabaseConfig.js';
import {BQWizardBulkSmsConfig} from './BQWizardBulkSmsConfig.js';
import {InputPassword} from './InputPassword.js';

class BQInstall extends HTMLElement {
	constructor() {
		super();
		[this.el, this.$] = InstallTemplates(`BQInstall`);
		this.attachShadow({mode:'open'});
		this.shadowRoot.appendChild(this.el);
	}
	connectedCallback() {
		if (!window.BQApi) {
			console.error(`BQApi is not defined`);
		} else {
			window.BQApi.CheckDatabase().then(
				(res)=>{
					console.log(`BQApi.CheckDatabase = `, res);
				});
			window.BQApi.Panacea().then(
				(res)=>{
					console.log(`BQApi.Panacea = `, res);
				});
		}
	}
	API() {
		return window.BQApi;
	}
}

customElements.define(`bq-install`, BQInstall);