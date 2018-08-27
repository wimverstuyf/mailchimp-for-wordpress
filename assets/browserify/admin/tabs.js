'use strict';

const URL = require('./url.js');

// Tabs
const Tabs = function(context) {

	// TODO: last piece of jQuery... can we get rid of it?
	const $ = window.jQuery;

	const $context = $(context);
	let $tabs = $context.find('.tab');
    let $tabNavs = $context.find('.nav-tab');
    let refererField = context.querySelector('input[name="_wp_http_referer"]');
    let tabs = [];

	$.each($tabs, function(i,t) {
		const id = t.id.substring(4);
		const title = $(t).find('h2').first().text();

		tabs.push({
			id: id,
			title: title,
			element: t,
			nav: context.querySelectorAll('.nav-tab-' + id),
			open: function() { return open(id); }
		});
	});

	function get(id) {

		for( let i=0; i<tabs.length; i++){
			if(tabs[i].id === id ) {
				return tabs[i];
			}
		}

		return undefined;
	}

	function open( tab, updateState ) {

		// make sure we have a tab object
		if(typeof(tab) === "string"){
			tab = get(tab);
		}

		if(!tab) { return false; }

		// should we update state?
		if( updateState == undefined ) {
			updateState = true;
		}

		// hide all tabs & remove active class
		$tabs.removeClass('tab-active').css('display', 'none');
		$tabNavs.removeClass('nav-tab-active');

		// add `nav-tab-active` to this tab
		Array.prototype.forEach.call(tab.nav, function(nav) {
			nav.className += " nav-tab-active";
			nav.blur();
		});

		// show target tab
		tab.element.style.display = 'block';
		tab.element.className += " tab-active";

		// create new URL
		var url = URL.setParameter(window.location.href, "tab", tab.id );

		// update hash
		if( history.pushState && updateState ) {
			history.pushState( tab.id, '', url );
		}

		// update document title
		title(tab);

		// update referer field
		refererField.value = url;

		// if thickbox is open, close it.
		if( typeof(tb_remove) === "function" ) {
			tb_remove();
		}

		// refresh editor after switching tabs
		// TODO: decouple this! law of demeter etc.
		if( tab.id === 'fields' && window.pl4wp && window.pl4wp.forms && window.pl4wp.forms.editor ) {
			pl4wp.forms.editor.refresh();
		}

		return true;
	}

	function title(tab) {
		var title = document.title.split('-');
		document.title = document.title.replace(title[0], tab.title + " ");
	}

	function switchTab(e) {
		e = e || window.event;

		// get from data attribute
		var tabId = this.getAttribute('data-tab');

		// get from classname
		if( ! tabId ) {
			var match = this.className.match(/nav-tab-(\w+)?/);
			if( match ) {
				tabId = match[1];
			}
		}

		// get from href
		if( ! tabId ) {
			var urlParams = URL.parse( this.href );
			if( ! urlParams.tab ) { return; }
			tabId = urlParams.tab;
		}

		var opened = open( tabId );

		if( opened ) {
			e.preventDefault();
			e.returnValue = false;
			return false;
		}

		return true;
	}

	function init() {

		// check for current tab
		if(! history.pushState) {
			return;
		}

		var activeTab = $tabs.filter(':visible').get(0);
		if( ! activeTab ) { return; }
		var tab = get(activeTab.id.substring(4));
		if(!tab) return;

		// check if tab is in html5 history
		if( history.replaceState && history.state === null) {
			history.replaceState( tab.id, '' );
		}

		// update document title
		title(tab);
	}

	$tabNavs.click(switchTab);
	$(document.body).on('click', '.tab-link', switchTab);
	init();

	if(window.addEventListener && history.pushState ) {
		window.addEventListener('popstate', function(e) {
			if(!e.state) return true;
			var tabId = e.state;
			return open(tabId,false);
		});
	}

	return {
		open: open,
		get: get
	}

};

module.exports = Tabs;