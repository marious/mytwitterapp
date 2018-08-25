function TemplateObject()
{
    "use strict";


    function _wrapValidation(message) {
        return '<span class="errorMessage">' + message + '</span>';
    }


    function _validation(validation, status) {
        var status = status || '';
        $.each(validation, function(k, v) {
            if (k == 'birth_date') {
                $('div.select-boxes').after(_wrapValidation(v[0]));
                $('div.select-boxes select').addClass('warningInputField').on('change', function() {
                    $(this).removeClass('warningInputField');
                    $('div.select-boxes').next('.errorMessage').remove();
                });
            }
            $('#' + status + k).addClass('warningInputField');
            $('#' + status + k).after(_wrapValidation(v[0]));
            $('#' + status + k).on('focusin', function() {
                $(this).removeClass('warningInputField');
                $(this).next('.errorMessage').remove();
            });
        });
    }

    function _displayMessage(thisForm, message) {
        thisForm.find('div').after(message);
    }


    function _clearFormValidation(thisForm) {
        thisForm.find('.errorMessage').remove();
    }


    function _reset() {
        thisForm[0].reset();
    }


    function _submitCreateScheduleTweet()
    {
        $(document).on('submit', '#schedule-tweet', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var thisForm = $(this);
            var thisArray = thisForm.serializeArray();

            $.post('schedule_tweets.php', thisArray, function(data) {
                console.log(data);
                if (data.error == false) {
                   console.log('success');
                    $('#createTwittModal').modal('hide');
                    window.location.href = 'schedule_tweets.php';
                } else if (data.error == true) {
                    _clearFormValidation(thisForm);
                    _displayMessage(thisForm, data.message);
                    _validation(data.validation);
                }
            }, 'json');
        });
    }



    function _submitUpdateScheduleTweet()
    {
        $(document).on('submit', '#update_schedule_tweet', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var thisForm = $(this);
            var thisArray = thisForm.serializeArray();

            $.post('schedule_tweets.php', thisArray, function(data) {
                console.log(data);
                if (data.error == false) {
                    console.log('success');
                    $('#createTwittModal').modal('hide');
                    window.location.href = 'schedule_tweets.php';
                } else if (data.error == true) {
                    _clearFormValidation(thisForm);
                    _displayMessage(thisForm, data.message);
                    _validation(data.validation);
                }
            }, 'json');
        });
    }



    function _addSpinner(el) {
        $(el).addClass('spinner ion-loop');
    }

    function _removeSpinner(el) {
        $(el).removeClass('spinner ion-loop');
    }


    function _seccessAlertBox(msg)
    {
        $('.alert.alert-success').remove();
        var output = ' <div class="alert alert-success">';
        output += '<i class="glyphicon glyphicon-ok"></i>';
        output += msg;
        output += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        output += '</div>';
        return output;
    }






    this.init = function() {
        _submitCreateScheduleTweet();
        _submitUpdateScheduleTweet()
    }
}

var obj = new TemplateObject();
obj.init();


if ($('#create_tweet').length) {
    $('#create_tweet').on('click', function() {
        $('input[type="checkbox"]').prop('checked', false);
        // $('#createTwittModal').modal('show');
    });
}

if ($('#scheduled_tweets').length) {

    $('#scheduled_tweets').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"ajax/fetch_scheduled_tweets.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[1,2,3,4],
                "orderable":false,
            },
        ],

    });


    $(document).on('click', '.update_schedule_tweet', function() {
       var tweetId = $(this).attr('id');
       $.ajax({
           url: 'ajax/fetch_single_schedule_tweet.php',
           method: 'POST',
           data: {tweet_id: tweetId},
           dataType: 'json',
           success: function(data) {
               $('input[type="checkbox"]').prop('checked', false);

               $('#updateScheduleTweet').modal('show');
               console.log(data);
               $('.update_tweet_content').val(data.tweet_content);
               $('.update_time_to_post').val(data.time_to_post);
               $('.update_tweet_id').val(data.id);
               var checkedMedia = data.tweet_media.split('-');
               console.log(checkedMedia);
               checkedMedia.forEach(function(item) {
                   $('input[value="'+item+'"]').prop('checked', true);
               });
           }
       });
    });
}


if ($('#auto_retweet').length) {
    $('#auto_retweet').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: window.root + "ajax/fetch_retweets_accounts.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[0,1,2,3,4,5,6,7],
                "orderable":false,
            },
        ],
    });
}

if ($('#auto_favs').length) {
    $('#auto_favs').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: window.root + "ajax/fetch_favs_accounts.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[0,1,2,3,4,5,6,7],
                "orderable":false,
            },
        ],
    });
}


if ($('#auto_replay').length) {
    $('#auto_replay').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url: window.root + "ajax/fetch_replay_accounts.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[0,1,2,3,4,5,6,7],
                "orderable":false,
            },
        ],
    });
}


if ($('#twitter-search').length) {

    // var engine;
    // var host = 'http://127.0.0.1/mytwitterapp/ajax/account_search.php';
    //
    // engine = new Bloodhound({
    //     identify: function(o) { return o.id_str; },
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name', 'screen_name'),
    //     dupDetector: function(a, b) { return a.id_str === b.id_str; },
    //     prefetch: remoteHost + '/demo/prefetch',
    //     remote: {
    //         url: remoteHost + '/query=%QUERY',
    //         wildcard: '%QUERY'
    //     }
    // });

    // $('#search-account').typeahead({
    //     source: function(query, result) {
    //         $.ajax({
    //             url: '/mytwitterapp/ajax/account_search.php',
    //             method: "POST",
    //             data: {query: query},
    //             dataType: "json",
    //             success: function(data) {
    //                 result($.map(data, function(item){
    //                     return item.screen_name;
    //                 }));
    //             }
    //         });
    //     }
    // });

    var engine, remoteHost, template, empty;

    $.support.cors = true;

    remoteHost = 'https://typeahead-js-twitter-api-proxy.herokuapp.com';
    template = Handlebars.compile($("#result-template").html());
    empty = Handlebars.compile($("#empty-template").html());

    engine = new Bloodhound({
        identify: function(o) { return o.id_str; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name', 'screen_name'),
        dupDetector: function(a, b) { return a.id_str === b.id_str; },
        prefetch: remoteHost + '/demo/prefetch',
        remote: {
            url: remoteHost + '/demo/search?q=%QUERY',
            wildcard: '%QUERY'
        }
    });

    // ensure default users are read on initialization
    engine.get('1090217586', '58502284', '10273252', '24477185')

    function engineWithDefaults(q, sync, async) {
        if (q === '') {
            sync(engine.get('1090217586', '58502284', '10273252', '24477185'));
            async([]);
        }

        else {
            engine.search(q, sync, async);
        }
    }

    $('#search-account').typeahead({
        hint: $('.Typeahead-hint'),
        menu: $('.Typeahead-menu'),
        minLength: 0,
        classNames: {
            open: 'is-open',
            empty: 'is-empty',
            cursor: 'is-active',
            suggestion: 'Typeahead-suggestion',
            selectable: 'Typeahead-selectable'
        }
    }, {
        source: engineWithDefaults,
        displayKey: 'screen_name',
        templates: {
            suggestion: template,
            empty: empty
        }
    })
        .on('typeahead:asyncrequest', function() {
            $('.Typeahead-spinner').show();
        })
        .on('typeahead:asynccancel typeahead:asyncreceive', function() {
            $('.Typeahead-spinner').hide();
        });
}
//
// if ($('#replay-check').length) {
//     $('.replay-container :input').prop('disabled', true);
//     $('#replay-check').click(function() {
//        $('.replay-container').toggleClass('bg-disabled');
//        if ($('#replay-check').is(':checked')) {
//            $('.replay-container :input').prop('disabled', false);
//        }
//     });
// }
//
// if ($('#retweet-check').length) {
//     $('.retweet-container :input').prop('disabled', true);
//     $('#retweet-check').click(function() {
//         $('.retweet-container').toggleClass('bg-disabled');
//         if ($('#retweet-check').is(':checked')) {
//             $('.retweet-container :input').prop('disabled', false);
//         }
//     });
// }