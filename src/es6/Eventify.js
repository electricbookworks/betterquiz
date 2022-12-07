export function Eventify(el, object) {
	let eventLink = function(el, event, object, method) {
		el.addEventListener(event, evt=>{
			object[method].call(object, evt);
		});
	}
	let eventify = function(e) {
		let pairs = e.getAttribute(`data-event`).split(`;`);
		for (let p of pairs) {
			let [event,method] = p.trim().split(`:`).map( m=>m.trim() );
			for (let evt of event.split(`,`).map( et=>et.trim() )) {
				if (!method) {
					method = evt;
				}
				eventLink(e, evt, object, method);
			}
		}
	};
	if (`function`==typeof el.hasAttribute && el.hasAttribute(`data-event`)) {
		eventify(el);
	}
	for (let e of el.querySelectorAll(`[data-event]`)) eventify(e);
}