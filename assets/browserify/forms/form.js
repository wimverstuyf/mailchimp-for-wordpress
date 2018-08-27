'use strict';

var serialize = require('form-serialize');
var populate = require('populate.js');

var Form = function(id, element) {
	this.id = id;
	this.element = element || document.createElement('form');
	this.name = this.element.getAttribute('data-name') || "Form #" + this.id;
	this.errors = [];
	this.started = false;
};

Form.prototype.setData = function(data) {
	try {
		populate(this.element, data);
	} catch(e) {
		console.error(e);
	}
};

Form.prototype.getData = function() {
	return serialize(this.element, { hash: true, empty: true });
};

Form.prototype.getSerializedData = function() {
	return serialize(this.element, { hash: false, empty: true });
};

Form.prototype.setResponse = function( msg ) {
	this.element.querySelector('.pl4wp-response').innerHTML = msg;
};

// revert back to original state
Form.prototype.reset = function() {
	this.setResponse('');
	this.element.querySelector('.pl4wp-form-fields').style.display = '';
	this.element.reset();
};

module.exports = Form;
