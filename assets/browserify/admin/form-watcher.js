var FormWatcher = function(m, editor, settings, fields, events, helpers) {
    'use strict';

    var requiredFieldsInput = document.getElementById('required-fields');

    function updateFields() {
        fields.getAll().forEach(function(field) {
            // don't run for empty field names
            if(field.name().length <= 0) return;

            var fieldName = field.name();
            if( field.type() === 'checkbox' ) {
                fieldName += '[]';
            }

            var inForm = editor.containsField( fieldName );
            field.inFormContent( inForm );

            // if form contains 1 address field of group, mark all fields in this group as "required"
            if( field.phplistType() === 'address' ) {
                field.originalRequiredValue = field.originalRequiredValue === undefined ? field.forceRequired() : field.originalRequiredValue;

                // query other fields for this address group
                var nameGroup = field.name().replace(/\[(\w+)\]/g, '' );
                if( editor.query('[name^="' + nameGroup + '"]').length > 0 ) {
                    if( field.originalRequiredValue === undefined ) {
                        field.originalRequiredValue = field.forceRequired();
                    }
                    field.forceRequired(true);
                } else {
                    field.forceRequired(field.originalRequiredValue);
                }
            }

        });

        findRequiredFields();
        m.redraw();
    }

    function findRequiredFields() {

        // query fields required by PhpList
        var requiredFields = fields.getAllWhere('forceRequired', true).map(function(f) { return f.name().toUpperCase().replace(/\[(\w+)\]/g, '.$1' ); });

        // query fields in form with [required] attribute
        var requiredFieldElements = editor.query('[required]');
        Array.prototype.forEach.call(requiredFieldElements, function(el) {
            var name = el.name;

            // bail if name attr empty or starts with underscore
            if(!name || name.length < 0 || name[0] === '_') {
                return;
            }

            // replace array brackets with dot style notation
            name = name.replace(/\[(\w+)\]/g, '.$1' );

            // replace array-style fields
            name = name.replace(/\[\]$/, '');

            // uppercase everything before the .
            var pos = name.indexOf('.');
            pos = pos > 0 ? pos : name.length;
            name = name.substr(0, pos).toUpperCase() + name.substr(pos);

            // only add field if it's not already in it
            if( requiredFields.indexOf(name) === -1 ) {
                requiredFields.push(name);
            }
        });

        // update meta
        requiredFieldsInput.value = requiredFields.join(',');
    }

    // events
    editor.on('change', helpers.debounce(updateFields, 500));
    events.on('fields.change', helpers.debounce(updateFields, 500));

};

module.exports = FormWatcher;
