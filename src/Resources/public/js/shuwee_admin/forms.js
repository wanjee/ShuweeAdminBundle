(function(){
    "use strict";

    /* file_widget */

    $('.file-widget-container .file-preview a').click(function(e) {
        $(this).closest('.file-widget-container').removeClass('show-preview');
        e.preventDefault();
    });

    function addItemForm($addButton) {
        var prototype = $addButton.data('prototype'),
            index = $addButton.data('index'),
            newForm = prototype.replace(/__name__/g, index),
            $newRow = $addButton.parent().clone().empty();

        // increase the index with one for the next item
        $addButton.data('index', index + 1);

        $newRow.html(newForm);
        $addButton.parent().before($newRow);
    }

    jQuery(document).ready(function() {
        $(document).on('click', 'button[data-prototype]', function(e) {
            e.preventDefault();
            addItemForm($(this));
        });

        $(document).on('click', 'button[data-delete]', function(e) {
            e.preventDefault();
            $(this).parent().remove();
        });
    });

})();