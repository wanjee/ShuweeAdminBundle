/* file_widget */

$('.file-widget-container .file-preview a').click(function(e) {
    $(this).closest('.file-widget-container').removeClass('show-preview');
    e.preventDefault();
});