(function (){
    'use strict';

    var root = this;

    function createDescriptors(object, properties) {
        var descriptors = { };
        Object.keys(properties).forEach(function(key) {
            descriptors[key] = Object.getOwnPropertyDescriptor(properties, key);
            descriptors[key].enumerable = false;
        });

        return descriptors;
    }

    function toArray(object) {
        return [].slice.call(object);
    }

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

    var DOM = root.DOM = function( selector ) {
        return new DOMElements(selector);
    };

    /**
     * Static properties
     */
    DOM.isElement       = isElement;
    DOM.isElementsList  = isElementsList;

    DOM.extend = function(properties) {
        Object.defineProperties(DOMElements.prototype, createDescriptors({}, properties));
    };

    DOM.ready = function(callback) {
        if (!isFunction(callback)) return false;
        document.addEventListener('DOMContentLoaded', function(e) {
            callback.call(DOM, DOM, e);
        }, false);
    };

    /**
     * DOM elements collection
     * @param {string|Element|HTMLCollection} selector
     * @constructor
     */
    function DOMElements( selector ) {
        if (isElement(selector)) { this.push(selector); return; }

        try {
            var elements = isElementsList(selector) ? selector : document.querySelectorAll(selector),
                i = 0, l = elements.length;
            for (; i < l; i++) this.push(elements[i]);
        } catch (e) { }
    }

    DOMElements.prototype = Object.create(Array.prototype, {
        constructor: {
            value: DOMElements
        }
    });

    /**
     * Base methods
     */
    DOM.extend({
        each: function(callback) {
            this.forEach(function(item, index) {
                callback.call(item, item, index);
            });

            return this;
        },

        text: function(text) {
            return text ? this.each(function() {
                this.innerText = text;
            }) : this[0].innerText;
        },

        html: function(html) {
            return html ? this.each(function(item) {
                item.innerHTML = html;
            }) : this[0].innerHTML;
        },

        parent: function() {
            this.splice(0, this.length, this[0].parentNode);
            return this;
        },

        parents: function(selector) {
            var elem = this[0];
            this.splice(0);

            while((elem = elem.parentNode)) {
                if (elem.nodeType === 1) {
                    if (selector) {
                        if (elem.matches(selector)) { this.push(elem); break; }
                    } else {
                        this.push(elem);
                    }
                }
            }

            return this;
        },

        find: function(selector) {
            var matches = [ ];

            this.each(function() {
                matches = matches.concat( toArray(this.querySelectorAll(selector)) );
            });

            this.splice(0);
            matches.forEach(function(item) {
                this.push(item);
            }.bind(this));

            return this;
        },

        eq: function(index) {
            this.splice(0, this.length, this[index]);
            return this;
        },

        append: function(html) {
            return this.each(function() {
                this.insertAdjacentHTML('beforeend', html);
            });
        },

        after: function(html) {
            return this.each(function() {
                this.insertAdjacentHTML('afterend', html);
            });
        },

        prepend: function(html) {
            return this.each(function() {
                this.insertAdjacentHTML('afterbegin', html);
            });
        },

        before: function(html) {
            return this.each(function() {
                this.insertAdjacentHTML('beforebegin', html);
            });
        },

        attr: function(key, value) {
            return value ? this.each(function() {
                this.setAttribute(key, value);
            }) : this[0].getAttribute(key);
        },

        data: function(key, value) {
            return value || isObject(key) ? this.each(function(item) {
                isObject(key) && each(key, function(name, val) {
                    item.dataset[name] = val;
                });

                isString(value) && (item.dataset[key] = value);
            }) : this[0].dataset[key];
        }
    });

    /**
     * Methods for elements classes
     */
    DOM.extend({
        hasClass: function(name) {
            return this[0].classList.contains(name);
        },

        addClass: function(name) {
            return this.each(function() {
                this.classList.add(name);
            });
        },

        removeClass: function(name) {
            return this.each(function() {
                this.classList.remove(name);
            });
        },

        toggleClass: function(name) {
            return this.each(function() {
                this.classList.toggle(name);
            });
        }
    });

    /**
     * Methods for elements styles
     */
    DOM.extend({
        show: function () {
            return this.each(function() {
                this.style.display = 'block';
            });
        },

        hide: function () {
            return this.each(function() {
                this.style.display = 'none';
            });
        }
    });

    /**
     * Events methods
     */
    DOM.extend({
        on: function() {

        },

        off: function() {

        },

        click: function() {

        },

        focus: function() {

        }
    });

}.call(window.app || (window.app = Object.create(null))));