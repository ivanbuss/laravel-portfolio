$(document).ready(function () {

    $(".ts-sidebar-menu li a").each(function () {
        if ($(this).next().length > 0) {
            $(this).addClass("parent");
        }
        ;
    })
    var menux = $('.ts-sidebar-menu li a.parent');
    $('<div class="more"><i class="fa fa-angle-down"></i></div>').insertBefore(menux);
    $('.more').click(function () {
        $(this).parent('li').toggleClass('open');
    });
    $('.parent').click(function (e) {
        e.preventDefault();
        $(this).parent('li').toggleClass('open');
    });
    $('.menu-btn').click(function () {
        $('nav.ts-sidebar').toggleClass('menu-open');
    });


    $('#zctb').DataTable();


    $("#input-43").fileinput({
        showPreview: false,
        allowedFileExtensions: ["zip", "rar", "gz", "tgz"],
        elErrorContainer: "#errorBlock43"
        // you can configure `msgErrorClass` and `msgInvalidFileExtension` as well
    });

    $('#decks-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "/admin/report/decks-uploaded/data"
    });

    if ($('#users-decks-table').length) {
        $uid = $('#users-decks-table').attr('rel');
        $('#users-decks-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": '/admin/report/user/'+$uid+'/decks-uploaded/data'
        });
    }

    $('#decks-rated-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "/admin/report/decks-rated/data"
    });

    $('#decks-tagged-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "/admin/report/decks-tagged/data"
    });

    $('#users-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "/admin/users/data",
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 1 ] },
            { 'bSortable': false, 'aTargets': [ 3 ] },
            { 'bSortable': false, 'aTargets': [ 6 ] },
        ]
    });

    $('#pages-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "/admin/pages/data",
        "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 6 ] },
        ]
    });



    // Deck rated by age Chart
    if ($('#chart-deck-rated-age').length || $('#chart-deck-rated-collection').length
        || $('#chart-deck-rated-gender').length || $('#chart-deck-rated-location').length) {
        var deckReport = {
            url: null,
            filter: null,
            element: null,
            initializing: function() {
                deckReport.filter = 'all';
                deckReport.url = $('#chart-deck-rated-age').attr('data-url');
                deckReport.request();
            },
            request: function(value) {
                if (deckReport.element) {
                    deckReport.url = $(deckReport.element).attr('data-url');
                }
                $.ajax({
                    url: deckReport.url,
                    dataType: 'json',
                    data: {filter:deckReport.filter, value:value, _token:$('meta[name="csrf-token"]').attr('content')},
                    type: 'post',
                    success: function( data ) {
                        if (data.success) {
                            var graphData = {
                                labels: null,
                                datasets: [
                                    {
                                        data: null,
                                        backgroundColor: [
                                            "#FF6384",
                                            "#4BC0C0",
                                            "#FFCE56",
                                            "#E7E9ED",
                                            "#36A2EB"
                                        ],
                                    }]
                            };
                            var options = {
                                type: 'pie',
                                data: graphData,
                                options: {responsive: true}
                            };

                            switch (deckReport.filter) {
                                case 'age':
                                    if (document.deckRatedAgeChart) document.deckRatedAgeChart.destroy();
                                    var age_options = $.extend(true, {}, options);
                                    age_options.data.labels = data.data.labels.age;
                                    age_options.data.datasets[0].data = data.data.results.age;
                                    document.deckRatedAgeChart = new Chart($(deckReport.element), age_options);
                                    break;
                                case 'collection':
                                    if (document.deckRatedCollectionChart) document.deckRatedCollectionChart.destroy();
                                    var collection_options = $.extend(true, {}, options);
                                    collection_options.data.labels = data.data.labels.collection;
                                    collection_options.data.datasets[0].data = data.data.results.collection;
                                    document.deckRatedCollectionChart = new Chart($(deckReport.element), collection_options);
                                    break;
                                case 'gender':
                                    if (document.deckRatedGenderChart) document.deckRatedGenderChart.destroy();
                                    var gender_options = $.extend(true, {}, options);
                                    gender_options.data.labels = data.data.labels.gender;
                                    gender_options.data.datasets[0].data = data.data.results.gender;
                                    document.deckRatedGenderChart = new Chart($(deckReport.element), gender_options);
                                    break;
                                case 'location':
                                    if (document.deckRatedLocationChart) document.deckRatedLocationChart.destroy();
                                    var location_options = $.extend(true, {}, options);
                                    location_options.data.labels = data.data.labels.location;
                                    location_options.data.datasets[0].data = data.data.results.location;
                                    document.deckRatedLocationChart = new Chart($(deckReport.element), location_options);
                                    break;
                                default:
                                    if (document.deckRatedAgeChart) document.deckRatedAgeChart.destroy();
                                    var age_options = $.extend(true, {}, options);
                                    age_options.data.labels = data.data.labels.age;
                                    age_options.data.datasets[0].data = data.data.results.age;
                                    document.deckRatedAgeChart = new Chart($('#chart-deck-rated-age'), age_options);

                                    if (document.deckRatedCollectionChart) document.deckRatedCollectionChart.destroy();
                                    var collection_options = $.extend(true, {}, options);
                                    collection_options.data.labels = data.data.labels.collection;
                                    collection_options.data.datasets[0].data = data.data.results.collection;
                                    document.deckRatedCollectionChart = new Chart($('#chart-deck-rated-collection'), collection_options);

                                    if (document.deckRatedGenderChart) document.deckRatedGenderChart.destroy();
                                    var gender_options = $.extend(true, {}, options);
                                    gender_options.data.labels = data.data.labels.gender;
                                    gender_options.data.datasets[0].data = data.data.results.gender;
                                    document.deckRatedGenderChart = new Chart($('#chart-deck-rated-gender'), gender_options);

                                    if (document.deckRatedLocationChart) document.deckRatedLocationChart.destroy();
                                    var location_options = $.extend(true, {}, options);
                                    location_options.data.labels = data.data.labels.location;
                                    location_options.data.datasets[0].data = data.data.results.location;
                                    document.deckRatedLocationChart = new Chart($('#chart-deck-rated-location'), location_options);

                                    break;
                            }
                        }
                    }
                });
            }
        };

        deckReport.initializing();

        $('.chart-deck-rated-age-filter').bind('click', function() {
            deckReport.element = $('#chart-deck-rated-age');
            deckReport.filter = 'age';
            deckReport.request($(this).attr('rel'));
        })

        $('.chart-deck-rated-collection-filter').bind('click', function() {
            deckReport.element = $('#chart-deck-rated-collection');
            deckReport.filter = 'collection';
            deckReport.request($(this).attr('rel'));
        })

        $('.chart-deck-rated-gender-filter').bind('click', function() {
            deckReport.element = $('#chart-deck-rated-gender');
            deckReport.filter = 'gender';
            deckReport.request($(this).attr('rel'));
        })

        $('.chart-deck-rated-location-filter').bind('click', function() {
            deckReport.element = $('#chart-deck-rated-location');
            deckReport.filter = 'location';
            deckReport.request($(this).attr('rel'));
        })
    }

    tinymce.init({
        selector: 'textarea.editor',
        height: 500,
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });



});
