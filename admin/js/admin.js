jQuery(document).ready(function($) {
    $('select#wpar-cat-tag').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        placeholder: '-- Select categories or tags --',
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
            $('.wparexclude').hide();
        }
        if ($('#wpar-exclude-type').val() != 'none') {
            $('.wparexclude').show();
        }
    });
    $('#wpar-exclude-type').trigger('change');
});