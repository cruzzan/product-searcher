var ProductSearch = (function ($) {
    var $searchField = $('#search'),
        $resultsConatiner = $('div#main'),

        bindEvents = function () {
            $searchField.keyup(function () {
                var term = $(this).val();
                filterResults(term);
            });
        },
        
        filterResults = function (term) {
            getResults(term);
        },
        
        getResults = function (term) {
            $.ajax({
                url: '/index.php/search',
                method: 'GET',
                data: {searchTerm: term}
            }).done(function (data) {
                reloadList(data)
            });
        },
        
        reloadList = function (data) {
            $resultsConatiner.html(data);
        }

        initialize = function () {
            bindEvents();
        };
    return {
        init: function () {
            initialize();
        }
    };
})(jQuery);

ProductSearch.init();
