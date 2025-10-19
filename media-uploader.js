jQuery(document).ready(function($) {
    'use strict';

    // --- Media Uploader Logic ---
    var frame;
    $('body').on('click', '.fwbe-upload-logo-button', function(e) {
        e.preventDefault();

        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: 'Select or Upload Logo',
            button: { text: 'Use this logo' },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            // Update settings field and preview
            $('#fwbe_logo_url').val(attachment.url);
            $('#fwbe-logo-preview').attr('src', attachment.url).show();
            $('#preview-logo').attr('src', attachment.url).show();
            $('.fwbe-remove-logo-button').show();
        });

        frame.open();
    });

    $('body').on('click', '.fwbe-remove-logo-button', function(e) {
        e.preventDefault();
        // Update settings field and preview
        $('#fwbe_logo_url').val('');
        $('#fwbe-logo-preview').attr('src', '').hide();
        $('#preview-logo').attr('src', '').hide();
        $(this).hide();
    });

    // --- Live Preview Logic ---
    
    // Function to update preview text
    function updatePreviewText(inputSelector, previewSelector) {
        $(previewSelector).text($(inputSelector).val());
    }

    // Listen for changes on text inputs
    $('#fwbe_from_name').on('keyup', function() {
        updatePreviewText(this, '#preview-from-name');
    });
    $('#fwbe_footer_line_1').on('keyup', function() {
        updatePreviewText(this, '#preview-footer-1');
    });
    $('#fwbe_footer_line_2').on('keyup', function() {
        updatePreviewText(this, '#preview-footer-2');
    });
    $('#fwbe_footer_line_3').on('keyup', function() {
        updatePreviewText(this, '#preview-footer-3');
    });
    
    // Initialize and listen for changes on the color picker
    $('.color-picker').wpColorPicker({
        change: function(event, ui) {
            $('#email-preview-wrap').css('background-color', ui.color.toString());
        }
    });
});