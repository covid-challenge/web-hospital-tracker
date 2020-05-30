var plugins = (function () {

    var pluginOne = function () {};

    var pluginTwo = function () {};

    var pluginThree = function () {};

    function initialize() {
        pluginOne();
        pluginTwo();
        pluginThree();
    }

    return {
        init: initialize()
    };

})();

$(document).ready(function () {
    plugins.init;
});
