jQuery(document).ready(function($) {

    $('select#wpar-cat').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        placeholder: '-- Select categories (required) --',
        persist: false,
        create: false
    });

    $('select#wpar-tag').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        placeholder: '-- Select tags (required) --',
        persist: false,
        create: false
    });

    $('select#wpar-days').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        placeholder: '-- Select weekdays (required) --',
        persist: false,
        create: false
    });

    $('#wpar-position').change(function() {
        if ($('#wpar-position').val() == 'disable') {
            $('.wpar-text').hide();
            $('#wpar-text').removeAttr('required');
        }
        if ($('#wpar-position').val() != 'disable') {
            $('.wpar-text').show();
            $('#wpar-text').attr('required', 'required');
        }
    });
    $('#wpar-position').trigger('change');

    $('#wpar-exclude-type').change(function() {
        if ($('#wpar-exclude-type').val() == 'none') {
            $('.wpar-taxonomy').hide();
            $('.wpar-cat'). hide();
            $('.wpar-tag').hide();
            $('.wpar-override-cat-tag').hide();
        }
        if ($('#wpar-exclude-type').val() != 'none') {
            $('.wpar-taxonomy').show();
            $('.wpar-override-cat-tag').show();
            $('#wpar-taxonomy').change(function() {
                if ($('#wpar-taxonomy').val() == 'category') {
                    $('.wpar-cat').show();
                    $('.wpar-tag').hide();
                }
                if ($('#wpar-taxonomy').val() == 'post_tag') {
                    $('.wpar-tag').show();
                    $('.wpar-cat').hide();
                }
            });
            $('#wpar-taxonomy').trigger('change');
        }
    });
    $('#wpar-exclude-type').trigger('change');

    $(".coffee-amt").change(function() {
        var btn = $('.buy-coffee-btn');
        btn.attr('href', btn.data('link') + $(this).val());
    });
    $(".coffee-amt").trigger('change');
    
});