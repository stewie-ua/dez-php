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
            }
        },

        click: {
            value: function(callback) {
                this.each(function(item) {
                    item.addEventListener('click', callback);
                });

                return this;
            }
        },

        show: {
            value: function () {
                this.each(function(item) {
                    item.style.display = 'block';
                });

                return this;
            }
        },

        hide: {
            value: function () {
                this.each(function(item) {
                    item.style.display = 'none';
                });

                return this;
            }
        },

        html: {
            value: function(html) {
                return isString(html) ? (this[0].innerHTML = html) && this : this[0].innerHTML;
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