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
    Toast.fire({
        icon: 'error',
        text: message
    });
}

function success(message = 'Success: Operation completed successfully. Thank you!') {
    Toast.fire({
        icon: 'success',
        text: message
    });
}

function info(message = 'Info: Here is an update for you.') {
    Toast.fire({
        icon: 'info',
        text: message
    });
}

function warning(message = 'Warning: Please review your action before proceeding.') {
    Toast.fire({
        icon: 'warning',
        text: message
    });
}