var ProductSearch = (function ($) {
    var $searchField = $('#search'),
        $searchButton = $('button#search-button'),
        $resultsConatiner = $('div#main'),

        bindEvents = function () {
            $searchButton.click(function () {
                var term = $searchField.val();
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
