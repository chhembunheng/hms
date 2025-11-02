function empty(instance = '') {
    if (instance === null || instance === undefined) {
        return true;
    }
    if (typeof instance === 'string') {
        instance = instance.trim();
        if (instance === '' || instance.toLowerCase() === 'null' || instance.toLowerCase() === 'undefined' || instance.toLowerCase() === 'false') {
            return true;
        }
    }
    if (Array.isArray(instance) && instance.length === 0) {
        return true;
    }
    if (typeof instance === 'number' && instance === 0) {
        return true;
    }
    return false;
}
function error(message = 'Error: An unexpected issue occurred. Please try again later.') {
    new Noty({
        type: 'error',
        text: `<div class="d-flex align-items-center p-2">
                   <i class="fa-light fa-circle-exclamation fa-beat-fade fa-fw fa-2xl me-3"></i>
                   <span>${message}</span>
               </div>`
    }).show();
}

function success(message = 'Success: Operation completed successfully. Thank you!') {
    new Noty({
        type: 'success',
        text: `<div class="d-flex align-items-center p-2">
                   <i class="fa-light fa-circle-check fa-beat-fade fa-fw fa-2xl me-3"></i>
                   <span>${message}</span>
               </div>`
    }).show();
}

function info(message = 'Info: Here is an update for you.') {
    new Noty({
        type: 'info',
        text: `<div class="d-flex align-items-center p-2">
                   <i class="fa-light fa-circle-info fa-beat-fade fa-fw fa-2xl me-3"></i>
                   <span>${message}</span>
               </div>`
    }).show();
}

function warning(message = 'Warning: Please review your action before proceeding.') {
    new Noty({
        type: 'warning',
        text: `<div class="d-flex align-items-center p-2">
                   <i class="fa-light fa-circle-exclamation fa-beat-fade fa-fw fa-2xl me-3"></i>
                   <span>${message}</span>
               </div>`
    }).show();
}