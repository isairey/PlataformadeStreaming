export function createElement(tag, attributes = {}, children = []) {
    const element = document.createElement(tag);
    
    Object.entries(attributes).forEach(([key, value]) => {
        if (key === 'className') {
            element.className = value;
        } else if (key === 'textContent') {
            element.textContent = value;
        } else {
            element.setAttribute(key, value);
        }
    });
    
    if (Array.isArray(children)) {
        children.forEach(child => {
            if (typeof child === 'string') {
                element.appendChild(document.createTextNode(child));
            } else if (child instanceof HTMLElement) {
                element.appendChild(child);
            }
        });
    } else if (typeof children === 'string') {
        element.textContent = children;
    }
    
    return element;
}

export function clearElement(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

export function toggleClass(element, className) {
    element.classList.toggle(className);
}

export function addClasses(element, classes) {
    element.classList.add(...classes);
}

export function removeClasses(element, classes) {
    element.classList.remove(...classes);
}
export function showElement(element) {
    element.classList.remove('hidden');
}

export function createErrorMessage(message) {
    return createElement('div', {className: 'error-message'}, message);
}
export function createSuccessMessage(message) {
    return createElement('div', {className: 'success-message'}, message);
} 