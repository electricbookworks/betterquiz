import {InstallTemplates} from './InstallTemplates.js';

export class InputPassword extends HTMLElement {
	constructor() {
		super();
		this.setAttribute(`type`,`password`);
		[this.el, this.$] = InstallTemplates(`InputPassword`);
		this.attachShadow({mode:`open`});
		this.shadowRoot.appendChild(this.el);
		this.$.show.addEventListener(`click`, evt=>this.toggleShow(evt) );
		this.show(true);
	}
	connectedCallback() {
		this.$.input.setAttribute(`placeholder`, this.getAttribute(`placeholder`));
		this.$.input.value = this.getAttribute(`value`);
	}
	get value() {
		return this.$.input.value;
	}
	show(hide=false) {
		this.$.input.setAttribute(`type`, hide?"password":"input");
		this.$.show.classList[ hide ? 'add' : 'remove' ]('show');
	}
	isShown() {
		console.log(`this.$.input.getAttribute('type')==${this.$.input.getAttribute(`type`)}`);
		return "input"==this.$.input.getAttribute(`type`);
	}
	toggleShow(evt) {
		if (evt) {
			evt.preventDefault();
		}
		this.show(this.isShown());
	}
}
customElements.define(`input-password`, InputPassword);