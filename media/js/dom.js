var DOM = (function() {
    "use strict";

    function isArray(array) {
        return Array.isArray(array);
    }

    function isObject(object) {
        return typeof object === 'object' ? !isArray(object) : false;
    }

    function isFunction(func) {
        return typeof func === 'function';
    }

    function isString(str) {
        return typeof str === 'string';
    }

    function isElement(element) {
        return element instanceof Element;
    }

    function isElementsList(list) {
        return list instanceof HTMLCollection;
    }

    function isNode(object) {
        return object instanceof Node;
    }

    function each(object, callback) {
        if (!isFunction(callback)) return console.error('Each: callback is not a function');

        if (isObject(object)) {
            for (var key in object) {
                callback(key, object[key]);
            }
        }
        else if (isArray(object)) {
            var i = 0, l = object.length;
            for (; i < l; i++) {
                callback(object[i], i);
            }
        }
        else return console.error('Each: invalid arguments');
    }

    /**
     * Create elements list
     *
     * @param selector
     * @constructor
     */
    function Element(selector) {
        var elements;

        if (isElementsList(selector)) {
            elements = selector;
        }
        else if (isNode(selector)) {
            this[0] = selector;
            return ;
        }
        else if (isString(selector)) {
            elements = document.querySelectorAll(selector);

        } else throw Error('Vania dolboeb!');

        [].forEach.call(elements, function(item, index) {
            this[index] = item;
        }.bind(this));
    }

    Object.defineProperties(Element.prototype, {
        length: {
            get: function() {
                return Object.keys(this).length;
            }
        },

        each: {
            value: function(callback) {
                [].forEach.call(this, callback);
                return this;
            }
        },

        click: {
            value: function(callback) {
                return this.each(function(item) {
                    item.addEventListener('click', callback);
                });
            }
        },

        show: {
            value: function () {
                return this.each(function(item) {
                    item.style.display = 'block';
                });
            }
        },

        hide: {
            value: function () {
                return this.each(function(item) {
                    item.style.display = 'none';
                });
            }
        },

        hasClass: {
            value: function(name) {
                return this[0].classList.contains(name)
            }
        },

        addClass: {
            value: function(name) {
                return this.each(function(item) {
                    item.classList.add(name);
                });
            }
        },

        removeClass: {
            value: function(name) {
                return this.each(function(item) {
                    item.classList.remove(name);
                });
            }
        },

        toggleClass: {
            value: function(name) {
                return this.each(function(item) {
                    item.classList.toggle(name);
                });
            }
        },

        attr: {
            value: function(key, value) {
                if (isString(value)) {
                    return this.each(function(item) {
                        item.setAttribute(key, value);
                    });
                }

                return this[0].getAttribute(key);
            }
        },

        data: {
            value: function(key, value) {
                if (isString(value) || isObject(key)) {
                    return this.each(function(item) {
                        isObject(key) && each(key, function(name, val) {
                            item.dataset[name] = val;
                        });

                        isString(value) && (item.dataset[key] = value);
                    });
                }

                return this[0].dataset[key];
            }
        },

        append: {
            value: function(html) {
                return this.each(function(item) {
                    item.insertAdjacentHTML('beforeend', html);
                });
            }
        },

        after: {
            value: function(html) {
                return this.each(function(item) {
                    item.insertAdjacentHTML('afterend', html);
                });
            }
        },

        prepend: {
            value: function(html) {
                return this.each(function(item) {
                    item.insertAdjacentHTML('afterbegin', html);
                });
            }
        },

        before: {
            value: function(html) {
                return this.each(function(item) {
                    item.insertAdjacentHTML('beforebegin', html);
                });
            }
        },

        text: {
            value: function(text) {
                if (isString(text)) {
                    return this.each(function(item) {
                        item.innerText = text;
                    });
                }

                return this[0].innerHTML;
            }
        },

        html: {
            value: function(html) {
                if (isString(html)) {
                    return this.each(function(item) {
                        item.innerHTML = html;
                    })
                }

                return this[0].innerHTML;
            }
        }
    });

    return function(selector) {
        return new Element(selector);
    };
}());

DOM.ready = function(callback) {
    if (typeof callback === 'function') {
        document.addEventListener('DOMContentLoaded', callback, false);
    }
};