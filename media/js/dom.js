var DOM = (function() {

    function Element(selector) {
        var element = document.querySelectorAll(selector);
        this.length = element.length;

        [].forEach.call(element, function(item, index) {
            this[index] = item;
        }.bind(this));
    }

    Object.defineProperties(Element.prototype, {
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
        }
    });

    return {
        get: function(selector) {
            return new Element(selector);
        }
    }
}());