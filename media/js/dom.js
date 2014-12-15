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

    function toArray(object) {
        return [].slice.call(object);
    }

    /**
     * Create elements list
     *
     * @param selector
     * @constructor
     */
    function Elements(selector) {
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

    Object.defineProperties(Elements.prototype, {
        length: {
            get: function () {
                return Object.keys(this).length;
            },

            set: function() { }
        }
    });

    Elements.extend = function(properties) {
        var proto = { };
        each(properties, function(key, item) {
            proto[key] = { value: item }
        });

        return Object.defineProperties(this.prototype, proto);
    };

    Elements.extend({
        /**
         * Each elements
         * @param {Function} callback
         * @returns {Elements}
         */
        each: function(callback) {
            [].forEach.call(this, callback);
            return this;
        },

        /**
         * Inner or get element text
         * @param {String} text
         * @returns {Elements}
         */
        text: function(text) {
            if (isString(text)) {
                return this.each(function(item) {
                    item.innerText = text;
                });
            }

            return this[0].innerHTML;
        },

        html: function(html) {
            if (isString(html)) {
                return this.each(function(item) {
                    item.innerHTML = html;
                })
            }

            return this[0].innerHTML;
        },

        parent: function() {
            [].splice.call(this, 0, this.length, this[0].parentNode);

            return this;
        },

        parents: function(selector) {
            var elem = this[0], i = 0;

            [].splice.call(this, 0);

            while((elem = elem.parentNode)) {
                if (elem.nodeType === 1) {
                    if (selector && elem.matches(selector)) {
                        this[0] = elem;
                        break;
                    }
                    else this[i++] = elem;
                }
            }

            return this;
        },

        find: function(selector) {
            var parents = toArray(this),
                elements = [ ];
            [].splice.call(this, 0);

            parents.forEach(function(item) {
                elements = elements.concat(toArray(item.querySelectorAll(selector)));
            }.bind(this));

            elements.forEach(function(item, index) {
                this[index] = item;
            }.bind(this));

            return this;
        },

        eq: function(index) {
            [].splice.call(this, 0, this.length, this[index]);
            return this;
        },

        click: function(callback) {
            return this.each(function(item) {
                item.addEventListener('click', callback);
            });
        },

        append: function(html) {
            return this.each(function(item) {
                item.insertAdjacentHTML('beforeend', html);
            });
        },

        after: function(html) {
            return this.each(function(item) {
                item.insertAdjacentHTML('afterend', html);
            });
        },

        prepend: function(html) {
            return this.each(function(item) {
                item.insertAdjacentHTML('afterbegin', html);
            });
        },

        before: function(html) {
            return this.each(function(item) {
                item.insertAdjacentHTML('beforebegin', html);
            });
        },

        attr: function(key, value) {
            if (isString(value)) {
                return this.each(function(item) {
                    item.setAttribute(key, value);
                });
            }

            return this[0].getAttribute(key);
        },

        data: function(key, value) {
            if (isString(value) || isObject(key)) {
                return this.each(function(item) {
                    isObject(key) && each(key, function(name, val) {
                        item.dataset[name] = val;
                    });

                    isString(value) && (item.dataset[key] = value);
                });
            }

            return this[0].dataset[key];
        },

        hasClass: function(name) {
            return this[0].classList.contains(name);
        },

        addClass: function(name) {
            return this.each(function(item) {
                item.classList.add(name);
            });
        },

        removeClass: function(name) {
            return this.each(function(item) {
                item.classList.remove(name);
            });
        },

        toggleClass: function(name) {
            return this.each(function(item) {
                item.classList.toggle(name);
            });
        },

        show: function () {
            return this.each(function(item) {
                item.style.display = 'block';
            });
        },

        hide: function () {
            return this.each(function(item) {
                item.style.display = 'none';
            });
        }
    });

    return function(selector) {
        return new Elements(selector);
    };
}());

DOM.ready = function(callback) {
    if (typeof callback === 'function') {
        document.addEventListener('DOMContentLoaded', callback, false);
    }
};