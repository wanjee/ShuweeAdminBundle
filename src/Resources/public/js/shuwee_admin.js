/*! shuwee-admin-bundle - v0.2.0 - 2015-06-28 22:39 */
/* file_widget */

$('.file-widget-container .file-preview a').click(function(e) {
    $(this).closest('.file-widget-container').removeClass('show-preview');
    e.preventDefault();
});