$('#selector-element').customselect({
    "csclass":"custom-select",  // Class to match
    "search": true, // Is searchable?
    "numitems":     10,    // Number of results per page
    "searchblank":  false,// Search blank value options?
    "showblank":    true, // Show blank value options?
    "searchvalue":  false,// Search option values?
    "hoveropen":    false,// Open the select on hover?
    "emptytext":    "",   // Change empty option text to a set value
    "showdisabled": false,// Show disabled options
    "mobilecheck":  function() {// Mobile check function / boolean
        return navigator.platform && navigator.userAgent.match(/(android|iphone|ipad|blackberry)/i);
    }});