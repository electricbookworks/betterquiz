// dtemplate generated - do not edit
export const InstallTemplates= (function() {	
	let templates =
		{"BQInstall":"\u003ctemplate\u003e\n\t\u003cstyle\u003e:host {\n  display: flex;\n  box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);\n  padding: 1em 2em;\n  border: 1px solid #ccc;\n  flex-direction: row;\n  justify-content: space-between;\n  align-items: stretch;\n  width: 100%; }\n\u003c/style\u003e\n\t\u003cbq-wizard\u003e\n\t\t\u003cbq-wizard-database-config\u003e \u003c/bq-wizard-database-config\u003e\n\t\t\u003cbq-wizard-bulk-sms-config\u003e \u003c/bq-wizard-bulk-sms-config\u003e\n\t\u003c/bq-wizard\u003e\n\u003c/template\u003e\n","BQStatus":"\u003ctemplate\u003e\n\t\u003cstyle\u003e:host {\n  display: flex;\n  flex-direction: column;\n  justify-content: flex-start;\n  align-items: stretch;\n  width: 100%;\n  font-size: 0.8em;\n  margin: 0.6em 0;\n  padding: 0.6em 0; }\n\n:host(.ok) #ok {\n  display: block; }\n:host(.ok) #error {\n  display: none; }\n\n:host(.error) #ok {\n  display: none; }\n:host(.error) #error {\n  display: block; }\n\n#ok, #error {\n  padding: 0.6em; }\n\n#ok {\n  border: 1px solid green;\n  color: #060;\n  background-color: #dfd; }\n\n#error {\n  border: 1px solid red;\n  color: red;\n  background-color: #fff0f0; }\n\n#explain {\n  color: #666; }\n\u003c/style\u003e\n\t\u003cdiv id=\"ok\"\u003e\n\t\u003c/div\u003e\n\t\u003cdiv id=\"error\"\u003e\n\t\u003c/div\u003e\n\t\u003cdiv id=\"explain\"\u003e\n\t\u003c/div\u003e\n\u003c/template\u003e\n","BQWizard":"\u003ctemplate\u003e\n\t\u003cstyle\u003e:host {\n  --color-wizard: #6cf;\n  display: flex;\n  flex-direction: column;\n  justify-content: top;\n  align-items: stretch;\n  width: 100%; }\n\n#header {\n  display: flex;\n  flex-direction: row;\n  justify-content: flex-start;\n  align-items: stretch; }\n  #header \u003e div {\n    background-color: #eee;\n    padding: 0.3em 1em;\n    border-top: 3px solid transparent; }\n    #header \u003e div.display {\n      border-color: var(--color-wizard, #6cf);\n      background-color: white; }\n\nfooter {\n  display: grid;\n  grid-template-columns: 1fr repeat(2, auto);\n  grid-column-gap: 1em;\n  margin-top: 1em;\n  border-top: 1px solid #eee;\n  padding: 0.4em; }\n  footer button {\n    background-color: white;\n    border: 1px solid var(--color-wizard, #6cf);\n    padding: 0.4em 1em;\n    color: var(--color-wizard, #6cf); }\n    footer button:not([disabled]):hover {\n      background-color: var(--color-wizard, #6cf);\n      color: white; }\n    footer button:first-child {\n      justify-self: start; }\n    footer button[disabled] {\n      border-color: #666;\n      color: #666; }\n\n#main \u003e *:not(.display) {\n  display: none; }\n\u003c/style\u003e\n\t\u003cheader id=\"header\"\u003e\n\t\u003c/header\u003e\n\t\u003cslot id=\"main\"\u003e\n\t\u003c/slot\u003e\n\t\u003cfooter\u003e\n\t\t\u003cbutton id=\"cancel\" data-event=\"click:cancel\"\u003eCancel\u003c/button\u003e\n\t\t\u003cbutton id=\"previous\" data-event=\"click:previous\" disabled=\"\"\u003ePrevious\u003c/button\u003e\n\t\t\u003cbutton id=\"next\" data-event=\"click:next\"\u003eNext\u003c/button\u003e\n\t\u003c/footer\u003e\n\u003c/template\u003e\n","BQWizardBulkSmsConfig":"\u003ctemplate\u003e\n\tThis is the bulk sms configuration\n\u003c/template\u003e\n","BQWizardDatabaseConfig":"\u003ctemplate\u003e\n\t\u003cstyle\u003e:host {\n  display: flex;\n  flex-direction: column;\n  justify-content: flex-start;\n  align-items: stretch; }\n\nlabel {\n  margin-top: 0.6em;\n  margin-bottom: -0.2em;\n  font-size: 0.7em;\n  color: #666; }\n\u003c/style\u003e\t\n\t\u003clabel for=\"server\"\u003eServer\u003c/label\u003e\n\t\u003cinput type=\"text\" id=\"server\" placeholder=\"localhost\" value=\"localhost\"/\u003e\n\t\u003clabel for=\"username\"\u003eUsername\u003c/label\u003e\n\t\u003cinput type=\"text\" id=\"username\" placeholder=\"user\" value=\"betterquiz\"/\u003e\n\t\u003clabel for=\"password\"\u003ePassword\u003c/label\u003e\n\t\u003cinput-password id=\"password\" placeholder=\"password\" value=\"betterquiz\"\u003e \u003c/input-password\u003e\n\t\u003clabel for=\"database\"\u003eDatabase\u003c/label\u003e\n\t\u003cinput type=\"text\" id=\"database\" placeholder=\"betterquiz\" value=\"betterquiz\"/\u003e\n\t\u003cbq-status id=\"status\" ok=\"So far so good\" explain=\"We got some issues.\"\u003e\n\t\u003c/bq-status\u003e\n\u003c/template\u003e\n","InputPassword":"\u003ctemplate\u003e\n\t\u003cstyle\u003e:host {\n  border: 0px solid red;\n  position: relative;\n  display: flex;\n  flex-direction: row;\n  justify-content: space-betweeen;\n  align-items: stretch;\n  gap: 0.4em; }\n\ninput {\n  flex-grow: 1; }\n\nbutton {\n  tranform: translate(-5em);\n  border-width: 0;\n  background-color: transparent;\n  color: #777; }\n  button \u003e svg {\n    width: 1.4em; }\n  button.show svg#eye-off {\n    display: none; }\n  button.show svg#eye {\n    display: block; }\n  button:not(.show) svg#eye-off {\n    display: block; }\n  button:not(.show) svg#eye {\n    display: none; }\n\u003c/style\u003e\n\t\u003cinput id=\"input\" placeholder=\"placeholder\"/\u003e\n\t\n\t\u003cbutton id=\"show\"\u003e\n\t\t\u003csvg stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" xmlns=\"http://www.w3.org/2000/svg\" id=\"eye\" viewBox=\"0 0 24 24\" fill=\"none\"\u003e\u003cpath d=\"M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z\"/\u003e\u003ccircle cx=\"12\" cy=\"12\" r=\"3\"/\u003e\u003c/svg\u003e\n\t\t\u003csvg stroke-linecap=\"round\" stroke-linejoin=\"round\" xmlns=\"http://www.w3.org/2000/svg\" id=\"eye-off\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"\u003e\u003cpath d=\"M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24\"/\u003e\u003cline x1=\"1\" y1=\"1\" x2=\"23\" y2=\"23\"/\u003e\u003c/svg\u003e\n\t\u003c/button\u003e\n\u003c/template\u003e\n"};

	let mk = function(k, html) {
		let el = document.createElement('div');
		el.innerHTML = html;
		//console.log("mk(",k,") html = ", html);

		let c = el.firstElementChild;
		while ((null!=c) && (Node.ELEMENT_NODE!=c.nodeType)) {
			c = c.nextSibling;
		}
		if (null==c) {
			console.error("FAILED TO FIND ANY ELEMENT CHILD OF ", k, ":", el)
			return mk('error', '<em>No child elements in template ' + k + '</em>');
		}
		el = c;
		if ('function'==typeof el.querySelector) {
			let et = el.querySelector('[data-set="this"]');
			if (null!=et) {
				el = et;
				el.removeAttribute('data-set');
			}
		}
		return el;
	}

	return function(t, dest={}) {
		// Return a deep copy of the node, created on first use
		let n = templates[t];
		if ('string'==typeof(n)) {			
			n = mk(t, n);
			templates[t] = n;
		}
		if ('undefined'==typeof n) {
			console.error('Failed to find template ' + t);
			return [false,false];
		}
		if (n.content) {
			n = n.content.cloneNode(true);
		} else {
			n = n.cloneNode(true);
		}
		try {
			for (let attr of ['id', 'data-set']) {
				let nodes = Array.from(n.querySelectorAll('[' + attr + ']'));
				if ('function'==typeof n.hasAttribute && n.hasAttribute(attr)) {
					nodes.unshift(n);
				}
				for (let el of nodes) {
					let a = el.getAttribute(attr);
					if (a.substr(0,1)=='$') {
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
		return [n,dest];
	}
})();
