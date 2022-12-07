import {InstallTemplates} from './InstallTemplates.js';
import {BQWizard} from './BQWizard.js';

class BQWizardDatabaseConfig extends HTMLElement {
	constructor() {
		super();
		[this.el, this.$] = InstallTemplates(`BQWizardDatabaseConfig`);
		this.attachShadow({mode:`open`});
		this.shadowRoot.appendChild(this.el);
		this.addEventListener(`wizard-tab-next`, evt=>this.nextPressed(evt));
	}
	connectedCallback() {
		console.log(`BQWizardDatabaseConfig::connectedCallback()`);
		this.dispatchEvent(new CustomEvent(`wizard-add-tab`, {
			composed:true, bubbles:true, cancelable: true,
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
				(res)=>{
					console.log(`BQApi.ConfigDatabase = `, res);
					if (res.error_code) {
						this.$.status.setAttribute("explain", `These settings don't seem to be right. Please check them and try again.`);
						this.$.status.setAttribute("error", res.error);
					} else {
						this.$.status.setAttribute(`ok`, `We've configured the database.`);
						BQWizard.showTag(this, `bulksms`);
					}
				})
			.finally( ()=>{
				BQWizard.stopWorking(this);
			})
		console.log(`BQWizardDatabaseConfig: nextPressed`);

	}

}
customElements.define(`bq-wizard-database-config`, BQWizardDatabaseConfig);