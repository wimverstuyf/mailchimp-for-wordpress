//(function() {
//	'use strict';


	var fieldSelector = {

		// properties
		selectedLists: m.prop( [] ),

		// Models
		List: function( data ) {
			this.name = data.name;
			this.id = data.id;
			this.fields = data.fields;
			this.groupings = data.groupings;
		},

		/**
		 * Get the currently selected lists
		 */
		updateSelectedLists: function() {
			var selectedLists = [];
			var selectedListInputs = document.querySelectorAll('#mc4wp-lists input:checked');

			for( var i = 0; i < selectedListInputs.length; i++ ) {
				var listId = selectedListInputs[i].value;
				var list = new fieldSelector.List({
					id: listId,
					name: mc4wp.mailchimpLists[ listId ].name,
					fields: mc4wp.mailchimpLists[ listId ].merge_vars,
					groupings: mc4wp.mailchimpLists[ listId].interest_groupings
				});
				selectedLists.push(list);
			}

			fieldSelector.selectedLists( selectedLists );
			m.redraw();
		},

		/**
		 * Controller
		 *
		 * - Attaches event listener to list inputs
		 * - Checks which lists are currently selected
		 */
		controller: function() {

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

			// Show notice if no MailChimp lists are selected
			if( 0 == fieldSelector.selectedLists().length ) {
				return m("p.mc4wp-notice", "Please select at least one list first." );
			}

			// build the MailChimp field options
			var mailchimpFields = fieldSelector.selectedLists().map( function( list, index ) {

				// One <optgroup> for each selected MailChimp list
				return m("optgroup", { label: list.name, style: "color: black;" }, list.fields.map( function( field, index ) {
						return m( "option", { value: field.tag }, field.name + ( ( field.req ) ? '*' : '' ) );
					})
				);

			});


			// Build the final select element
			return m( "select.widefat", [
				m( "option", { disabled: true, selected: true }, "Select a MailChimp field." ),
				mailchimpFields,
				m( "optgroup", { label: "Other fields" }, [
					m( "option", "Submit Button" ),
					m( "option", "List Choice" )
				])
			] );
		}
	};

	// Let's do this!
	m.module( document.getElementById( 'mc4wp-field-selector' ), fieldSelector );


//})();