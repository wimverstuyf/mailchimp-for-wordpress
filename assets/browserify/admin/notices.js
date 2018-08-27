'use strict';

var notices = {};

function show(id, text) {
    notices[id] = text;
    render();
}

function hide(id) {
    delete notices[id];
    render();
}

function render() {
    var html = '';
    for(var key in notices) {
        html += '<div class="notice notice-warning inline"><p>' + notices[key] + '</p></div>';
    }

    var container = document.querySelector('.pl4wp-notices');
    if( ! container ) {
        container = document.createElement('div');
        container.className = 'pl4wp-notices';
        var heading = document.querySelector('h1, h2');
        heading.parentNode.insertBefore(container, heading.nextSibling);
    }
    
    container.innerHTML = html;
}

function init( editor, fields ) {

    var groupingsNotice = function() {
        var text = "Your form contains old style <code>GROUPINGS</code> fields. <br /><br />Please remove these fields from your form and then re-add them through the available field buttons to make sure your data is getting through to PhpList correctly.";
        var formCode = editor.getValue().toLowerCase();
        ( formCode.indexOf('name="groupings') > -1 ) ? show('deprecated_groupings', text) : hide('deprecated_groupings');
    };

    var requiredFieldsNotice = function() {
        var requiredFields = fields.getAllWhere('forceRequired', true);
        var missingFields = requiredFields.filter(function(f) {
            return ! editor.containsField(f.name().toUpperCase());
        });

        var text = '<strong>Heads up!</strong> Your form is missing list fields that are required in PhpList. Either add these fields to your form or mark them as optional in PhpList.';
        text += "<br /><ul class=\"ul-square\" style=\"margin-bottom: 0;\"><li>" + missingFields.map(function(f) { return f.title(); }).join('</li><li>') + '</li></ul>';

        ( missingFields.length > 0 ) ? show('required_fields_missing', text) : hide('required_fields_missing');
    };

    // old groupings
    groupingsNotice();
    editor.on('focus', groupingsNotice);
    editor.on('blur', groupingsNotice);

    // missing required fields
    requiredFieldsNotice();
    editor.on('blur', requiredFieldsNotice);
    editor.on('focus', requiredFieldsNotice);
}



module.exports = {
    "init": init
};