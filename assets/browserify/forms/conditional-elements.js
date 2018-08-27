'use strict';

function getFieldValues(form, fieldName) {
    let values = [];
    let inputs = form.querySelectorAll('input[name="'+fieldName+'"], select[name="'+fieldName+'"], textarea[name="'+fieldName+'"]');

    for(let i=0; i<inputs.length; i++) {
        const input = inputs[i];
        const type = input.getAttribute("type");

        if( ( type === "radio" || type === "checkbox" ) && ! input.checked) {
            continue;
        }

        values.push(input.value);
    }

    return values;
}

function findForm(element) {
    let bubbleElement = element;

    while(bubbleElement.parentElement) {
        bubbleElement = bubbleElement.parentElement;

        if(bubbleElement.tagName === 'FORM') {
            return bubbleElement;
        }
    }

    return null;
}

function toggleElement(el) {
    const show = !!el.getAttribute('data-show-if');
    const conditions = show ? el.getAttribute('data-show-if').split(':') : el.getAttribute('data-hide-if').split(':');
    const fieldName = conditions[0];
    const expectedValues = ((conditions.length > 1 ? conditions[1] : "*").split('|'));
    const form = findForm(el);
    const values = getFieldValues(form, fieldName);

    // determine whether condition is met
    let conditionMet = false;
    for(let i=0; i<values.length; i++) {
        const value = values[i];

        // condition is met when value is in array of expected values OR expected values contains a wildcard and value is not empty
        conditionMet = expectedValues.indexOf(value) > -1 || ( expectedValues.indexOf('*') > -1 && value.length > 0 );

        if(conditionMet) { 
            break;
        }
    }

    // toggle element display
    if(show){
        el.style.display = ( conditionMet ) ? '' : 'none';
    } else {
        el.style.display = ( conditionMet ) ? 'none' : '';
    }  

    // find all inputs inside this element and toggle [required] attr (to prevent HTML5 validation on hidden elements)
    let inputs = el.querySelectorAll('input, select, textarea');
    [].forEach.call(inputs, (el) => {
        if(( conditionMet || show  ) && el.getAttribute('data-was-required')) {
            el.required = true;
            el.removeAttribute('data-was-required');
        }

        if(( !conditionMet || ! show ) && el.required) {
           el.setAttribute('data-was-required', "true");
           el.required = false;
        }
    });
}

// evaluate conditional elements globally
function evaluate() {
    const elements = document.querySelectorAll('.pl4wp-form [data-show-if], .pl4wp-form [data-hide-if]');
    [].forEach.call(elements, toggleElement);
}

// re-evaluate conditional elements for change events on forms
function handleInputEvent(evt) {
    if( ! evt.target || ! evt.target.form || evt.target.form.className.indexOf('pl4wp-form') < 0 ) {
        return;
    }

    const form = evt.target.form;
    const elements = form.querySelectorAll('[data-show-if], [data-hide-if]');
    [].forEach.call(elements, toggleElement);
}

export default {
    'init': function() {
        document.addEventListener('keyup', handleInputEvent, true);
        document.addEventListener('change', handleInputEvent, true);
        document.addEventListener('pl4wp-refresh', evaluate, true);
        window.addEventListener('load', evaluate);
        evaluate();
    }
}
