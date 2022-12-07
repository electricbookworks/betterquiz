import {InstallTemplates} from './InstallTemplates.js';
import {Eventify} from './Eventify.js';

class WizardTab {
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
		let method = bDisplay ? 'add' : 'remove';
		this.titleEl.classList[method](`display`)
	}
	dispatchEvent(ce) {
		this.tab.dispatchEvent(ce);
	}
	get id() {
		return this.tab.getId();
	}
}

/**
 * BQWizard is a wizard that displays one of it's child elements
 * as a 'tab'.
 * 
 * Tabs communicates with the wizard by sending these messages:
 * 
 * wizard-add-tab { id, component, title-element, body-element }
 * wizard-enable-tab { id, disable: true }
 * wizard-show-tab { id }
 * wizard-set-next-text { detail }
 * wizard-disable-buttons { detail: { next: bool, previous: bool, cancel: bool }}
 * wizard-set-working { detail: { working: bool }}
 * 
 * The wizard communicates with each tab by sending these messages:
 * 
 * wizard-tab-active {..}
 * wizard-tab-next { id }
 *  // when a tab receives the wizard-tab-next from the wizard, the
 *  // tab should perform whatever processing it wants, then 
 *  // send the wizard a wizard-show-tab message.
 */
export class BQWizard extends HTMLElement {
	constructor() {
		super();
		[this.el, this.$] = InstallTemplates(`BQWizard`);
		this.attachShadow({mode:'open'});
		this.shadowRoot.appendChild(this.el);

		this.addEventListener(`wizard-add-tab`, evt=>this.addTab(evt));
		this.addEventListener(`wizard-enable-tab`, evt=>this.enableTab(evt));
		this.addEventListener(`wizard-show-tab`, evt=>this.showTab(evt));
		this.addEventListener(`wizard-set-next-text`, evt=>this.setNextText(evt));
		this.addEventListener(`wizard-disable-buttons`, evt=>this.disableButtons(evt));

		this.addEventListener(`wizard-set-working` , evt=>this.setWorking(evt));

		this.tabs = [];
		this.activeTab = false;

		Eventify(this.shadowRoot, this);
	}
	connectedCallback() {
	}
	addTab(evt) {
		// wizard-add-tab event should be sent with 
		// the detail being the component / object to be added.
		// The component needs to implement this interface:
		//   getTitleText() - String / Element
		//   isDefaultTab() - bool
		evt.stopPropagation();
		let tab = evt.detail;
		let title = document.createElement(`div`);
		let titleContent = tab.getTitleText();
		if (`string`==typeof titleContent) {
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
		this.tabs.map( t => console.log(`tab has id ${t.id}`));
		let nextTab = this.tabs.filter( t => t.id == id);
		if (0==nextTab.length) {
			console.error(`Requested tab with id ${id}, but no such tab found`);
			return;
		}
		this.activeTab.display(false);
		this.activeTab = nextTab[0];
		this.activeTab.display(true);
	}

	static showTag(el, tagId) {
		el.dispatchEvent(new CustomEvent(`wizard-show-tab`, {
			bubbles: true, composed: true, cancelable: true,
			detail: { source: el, id: tagId }
		}));
	}

	static sendSetWorking(el, working) {
		el.dispatchEvent(new CustomEvent(`wizard-set-working`, {
			bubbles: true, composed:true, cancelable: true,
			detail: { source: el, working: working },
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
		if ('string'==typeof next) {
			this.$.next.innerText = next;
		} else {
			// This will cause all sorts of shadow-DOM issues, unless the
			// innerHTML is itself a webcomponent
			console.warn(`setNextText() called with non-string parameter `, next);
			this.$.next.innerHTML = ``;
			this.$.next.appendChild(next);
		}
	}
	// disableButtons is sent by a tab to ask the wizard to disable
	// particular buttons.
	disableButtons(evt) {
		evt.stopPropagation();
		let disable = (btn, disable)=> {
			if (disable) {
				btn.setAttribute(`disabled`, `disabled`);
			} else {
				btn.removeAttribute(`disabled`);
			}
		}
		disable(this.$.next, evt.detail.next);
		disable(this.$.canccel, evt.detail.cancel);
		disable(this.$.previous, evt.detail.previous);
	}
	/** user pressed the 'previous' button */
	previous(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		console.log(`previous clicked`);
	}
	/** user pressed the 'cancel' button */
	cancel(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		console.log(`cancel clicked`);
	}
	/** user pressed the 'next' button */
	next(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		this.activeTab.dispatchEvent(new CustomEvent(`wizard-tab-next`, {
			bubbles: true, composed: true, cancelable: true, 
			detail: this
		}));
	}
}

customElements.define(`bq-wizard`, BQWizard);