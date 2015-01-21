(function() {
	'use strict';

	var fieldHelper = {};

	// Models
	var List = function( data ) {
		this.name = m.prop( data.name );
		this.id = m.prop( data.id );
		this.fields = m.prop( ( data.fields ) ? data.fields : [] );
	};

	var Field = function( data ) {
		this.name = m.prop( data.name );
		this.label = m.prop( data.label );
		this.fieldType = m.prop( data.fieldType );
		this.required = m.prop( data.required );
	};

	// Controller
	fieldHelper.controller = function() {
		var ctrl = this;

		ctrl.fieldSelector = new fieldSelector.controller();
		ctrl.fieldBuilder = new fieldBuilder.controller();
	};

	// View
	fieldHelper.view = function( ctrl ) {
		return m( "div", [
			fieldSelector.view( ctrl.fieldSelector ),
			fieldBuilder.view( ctrl.fieldBuilder )
		])
	};

	var fieldSelector = {

		// properties
		formContent: document.getElementById('mc4wpformmarkup'),
		selectedLists: m.prop( [] ),
		selectedField: m.prop( null ),
		availableFieldGroups: m.prop( [] ),
		availableFields: m.prop( [] ),
		defaultFields: m.prop( [] ),

		/**
		 * Find which MailChimp lists are selected
		 * - Builds list of List models
		 */
		updateSelectedLists: function() {

			var selectedListInputs = document.querySelectorAll('#mc4wp-lists input:checked');
			var selectedLists = [];

			// loop through selected list id's
			for( var i = 0; i < selectedListInputs.length; i++ ) {
				var rawList = mc4wp.mailchimpLists[ selectedListInputs[i].value ];

				// create new list
				var list = new List({
					id: rawList.id,
					name: rawList.name
				});

				// build array of list fields
				var listFields = [];
				for( var j=0; j < rawList.merge_vars.length; j++ ) {
					var field = rawList.merge_vars[j];
					listFields.push( new Field({
						name     : field.tag,
						label    : field.name,
						fieldType    : field.field_type,
						required: field.req
					}) );
				}

				// set list fields
				list.fields( listFields )

				// push list to full array
				selectedLists.push( list );
			}

			fieldSelector.selectedLists( selectedLists );
			fieldSelector.updateAvailableFields();
		},

		/**
		 * Build array of available fields to choose from
		 */
		updateAvailableFields: function() {
			var fieldGroups = [];
			var fields = [];
			var lists = fieldSelector.selectedLists();

			// add list fields
			for( var i = 0; i < lists.length; i++ ) {

				fields = fields.concat( lists[i].fields() );

				fieldGroups.push({
					name: lists[i].name(),
					options: lists[i].fields()
				});
			}

			// add other fields
			fields = fields.concat ( fieldSelector.defaultFields() );
			fieldGroups.push( {
				name: "Other fields",
				options: fieldSelector.defaultFields()
			});

			fieldSelector.availableFields( fields );
			fieldSelector.availableFieldGroups( fieldGroups );
		},

		/**
		 * Check if form contains a given field already
		 *
		 * @param fieldName
		 * @returns {boolean}
		 */
		formHasField: function( fieldName ) {
			return fieldSelector.formContent.value.toLowerCase().indexOf('name="'+ fieldName.toLowerCase() +'"') > -1;
		},

		/**
		 * Mounts the FieldHelper module, runs after a MailChimp field is selected
		 *
		 * @param evt
		 */
		updateSelectedField: function( evt ) {
			var selectedFieldIndex =  evt.target.selectedIndex;
			var selectedField = fieldSelector.availableFields()[ selectedFieldIndex - 1 ];

			// update selected field
			fieldBuilder.vm.updateConfig( selectedField );
		},

		/**
		 * Controller
		 *
		 * - Attaches event listener to list inputs
		 * - Checks which lists are currently selected
		 */
		controller: function() {

			// build default fields array
			var submitField = new Field({
				name: "",
				label: "Submit Button",
				fieldType: "submit",
				required: false
			});

			var listChoiceField = new Field({
				name: "_mc4wp_lists",
				label: "Lists Choice",
				fieldType: "choice",
				required: false
			});

			fieldSelector.defaultFields = m.prop([
				submitField,
				listChoiceField
			]);

			var listInputs = document.querySelectorAll( '#mc4wp-lists input');
			for( var i = 0; i < listInputs.length; i++ ) {
				listInputs[i].addEventListener( 'change', fieldSelector.updateSelectedLists );
			}

			// check which lists are selected
			fieldSelector.updateSelectedLists();
		},

		/**
		 * Renders the Mithril view
		 *
		 * @param ctrl
		 * @returns {*}
		 */
		view: function( ctrl ) {

			var f = fieldSelector;

			// Show notice if no MailChimp lists are selected
			if( 0 == f.selectedLists().length ) {
				return m("p.mc4wp-notice", "Please select at least one list first." );
			}

			// build array w/ <options> from available fields
			var options = f.availableFieldGroups().map( function( group ) {
				return m("optgroup", { label: group.name }, [
					group.options.map( function( field ) {
						return m( "option", {
							value: field.name(),
							class: ( f.formHasField( field.name() ) ? 'muted' : '' )
						}, [
							field.label(), ( ( field.required() ) ? m( "span.req", "*" ) : '' )
						]);
					})
				])
			});

			// Build the final select element
			return m( "p", [
				m( "select.widefat", { onchange: f.updateSelectedField } , [
					m( "option", { disabled: true, selected: ( f.selectedField() == null ) }, "Select a MailChimp field." ),
					options
				] )
			]);
		}
	};


	var fieldBuilder = {};

	// Model
	fieldBuilder.Config = function( data ) {
		this.type = m.prop( data.type || "text" );
		this.label = m.prop( data.label || "" );
		this.placeholder = m.prop( data.placeholder || "" );
		this.isRequired = m.prop( data.required || false );
		this.wrapInP = m.prop( data.wrapInP || false );
		this.value = m.prop( data.value || "" );
	};

	// Functions
	fieldBuilder.vm = {};
	fieldBuilder.vm.init = function() {
		this.config = new fieldBuilder.Config( {});
		this.selectedField = m.prop( null );
	};

	fieldBuilder.vm.updateConfig = function( field ) {

		this.selectedField( field );

		this.config.label( field.label() );
		this.config.placeholder( "Your " + field.label().toLowerCase() );
		this.config.isRequired( field.required() );
		this.config.type( field.fieldType() );

	};


	/**
	 * Renders the configuration fields for a given field type
	 *
	 * @returns {*[]}
	 */
	fieldBuilder.renderConfigFields = function() {

		var cfg = fieldBuilder.vm.config;

		switch( cfg.type() ) {
			case 'text':
			case 'email':

				return [
					m( "p", [
						m( "label", "Label Text"),
						m( "input", { type: "text", value: cfg.label(), onchange: m.withAttr( "value", fieldBuilder.vm.config.label ) } )
					]),
					m( "p", [
						m( "label", "Placeholder Text" ),
						m( "input", { type: "text", value: cfg.placeholder(), onchange: m.withAttr( "value", fieldBuilder.vm.config.placeholder ) } )
					]),
					m( "p", [
						m( "label", "Default Value" ),
						m( "input", { type: "text", onchange: m.withAttr( "value", fieldBuilder.vm.config.value ) } )
					]),
					m( "p", [
						m( "label", [
							m( "input", { type: "checkbox", checked: cfg.wrapInP(), onchange: m.withAttr( "checked", fieldBuilder.vm.config.wrapInP ) } ),
							m( "span", "Wrap in paragraphs?" )
						])
					]),
					m( "p", [
						m( "label", [
							m( "input", { type: "checkbox", checked: cfg.isRequired(), onchange: m.withAttr( "checked", fieldBuilder.vm.config.isRequired ) } ),
							m( "span", "This is a required field." )
						])
					])
				];

				break;
		}
	};

	/**
	 * Controller
	 */
	fieldBuilder.controller = function() {
		fieldBuilder.vm.init();
	};

	/**
	 * View
	 *
	 * @param ctrl
	 * @returns {*}
	 */
	fieldBuilder.view = function( ctrl ) {

		var vm = fieldBuilder.vm;

		// If no field is selected, show a message to choose a field first..
		if( vm.selectedField() == null ) {
			return m( "p", "Select a field.." );
		}

		// Output configuration fields, add to form button & code preview
		return [
			fieldBuilder.renderConfigFields(),
			m( "p", [
				m("button.button", "Add to form")
			]),
			m( "textarea.code-preview", { id: "code-preview" }, codePreview.render( fieldBuilder.vm.config ) )
		];
	};


	// Let's do this!
	m.module( document.getElementById( 'mc4wp-field-helper' ), fieldHelper );

	var codePreview = {};
	codePreview.config = m.prop( null );
	codePreview.preview = m.prop( '' );

	codePreview.render = function( config ) {

		console.log( config.label() );

		console.log( "rendering code preview.." );

		// compare preview with last generated preview
		//if( codePreview.config() == config ) {
		//	console.log( "not updating preview.." );
		//	return codePreview.preview();
		//}

		// create new HTML string
		var html = '';

		// add label to string
		html += config.label();

		// store & return preview
		codePreview.config( config );
		codePreview.preview( html );
		return codePreview.preview();
	};

})();