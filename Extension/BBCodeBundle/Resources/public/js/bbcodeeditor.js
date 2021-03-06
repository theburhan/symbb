(function($) {

    var BBCodeEditor = function(element)
    {
        var elem    = $(element);
        var obj     = this;
        var area    = $(element).find('.symbb_bbcode_editor_textarea');
        $(element).find('.symbb_bbbcode_btn').each(function(index, button) {
            $(button).click(function() {
                button      = $(button);
                var tagCode   = button.data('tag-code');
                obj.insertCode(tagCode, area);
            });
        });

        this.insertCode = function(tagCode, element) {
            element = element[0];
            if (document.selection) {
                element.focus();
                sel = document.selection.createRange();
                sel.text = tagCode.replace('{0}', sel.text);
            } else if (element.selectionStart || element.selectionStart == '0') {
                element.focus();
                var startPos = element.selectionStart;
                var endPos = element.selectionEnd;
                element.value = element.value.substring(0, startPos) + tagCode.replace('{0}', element.value.substring(startPos, endPos)) + element.value.substring(endPos, element.value.length);
            } else {
                element.value += tagCode.replace('{0}', '');
            }
        }
    };

    $.fn.bbcodeEditor = function()
    {
        return this.each(function()
        {
            var element = $(this);

            // Return early if this element already has a plugin instance
            if (element.data('bbcodeEditor'))
                return;

            var myplugin = new BBCodeEditor(this);

            // Store plugin object in this element's data
            element.data('bbcodeEditor', myplugin);
        });
    };

})(jQuery);


$(document).ready(function() {
    $('#symbb_bbcode_editor').bbcodeEditor();
});