(function () {
    "use strict";

    // File widget
    $('.file-widget-container .file-preview a').click(function (e) {
        $(this).closest('.file-widget-container').removeClass('show-preview');
        e.preventDefault();
    });

    // Collections
    function addItemForm($addButton) {
        // For multiple nested collections we have the inner prototype nested in the outer one
        // Nested prototypes have multiple instances of __name__.
        // Some of them relate to the outer prototype, other(s) to the inner one(s)
        // e.g. outer_form_elements___name___inner_form___name___title
        // e.g. outer_form[elements][__name__][inner_form][__name__][title]
        // As this function only handles outer prototype we will need to replace every first occurence of __name__
        // Other occurences will be replaced when the inner prototype will be used

        var prototype = $addButton.data('prototype'),
            index = $addButton.data('index'),
            $newRow = $addButton.parent().clone().empty();

        // Find all groups of __name__ (one or several in same string)
        var reg = new RegExp('[^\\s=;"&]*(__name__)\\1*[^\\s;"&]*', 'g');

        prototype = prototype.replace(reg, function replacer(match) {
            // Replace only first occurence (no global flag)
            return match.replace(/__name__/, index);
        });

        // Increment the index for the next item
        $addButton.data('index', index + 1);

        $newRow.html(prototype);
        $addButton.parent().before($newRow);
    }

    jQuery(document).ready(function () {
        $(document).on('click', 'button[data-prototype]', function (e) {
            e.preventDefault();
            addItemForm($(this));
        });

        $(document).on('click', 'button[data-delete]', function (e) {
            e.preventDefault();
            $(this).parent().remove();
        });
    });

})();