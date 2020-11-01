"use strict";

// Class definition
var KTAppTodo = function() {
    var asideEl;
    var listEl;
    var viewEl;

    var asideOffcanvas;

    var initEditor = function(editor) {
        // init editor
        var options = {
            modules: {
                toolbar: {}
            },
            placeholder: 'Type message...',
            theme: 'snow'
        };

        var editor = new Quill('#' + editor, options);
    }

    var initAttachments = function(elemId) {
        var id = "#" + elemId;
        var previewNode = $(id + " .dropzone-item");
        previewNode.id = "";
        var previewTemplate = previewNode.parent('.dropzone-items').html();
        previewNode.remove();

        var myDropzone = new Dropzone(id, { // Make the whole body a dropzone
            url: "https://keenthemes.com/scripts/void.php", // Set the url for your upload script location
            parallelUploads: 20,
            maxFilesize: 1, // Max filesize in MB
            previewTemplate: previewTemplate,
            previewsContainer: id + " .dropzone-items", // Define the container to display the previews
            clickable: id + "_select" // Define the element that should be used as click trigger to select files.
        });

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            $(document).find(id + ' .dropzone-item').css('display', '');
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector(id + " .progress-bar").style.width = progress + "%";
        });

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector(id + " .progress-bar").style.opacity = "1";
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("complete", function(progress) {
            var thisProgressBar = id + " .dz-complete";
            setTimeout(function() {
                $(thisProgressBar + " .progress-bar, " + thisProgressBar + " .progress").css('opacity', '0');
            }, 300)
        });
    }

    return {
        // public functions
        init: function() {
            asideEl = KTUtil.getByID('kt_todo_aside');
            listEl = KTUtil.getByID('kt_todo_list');
            viewEl = KTUtil.getByID('kt_todo_view');

            // init
            KTAppTodo.initAside();
            KTAppTodo.initList();
            KTAppTodo.initCommentForm();
            KTAppTodo.initView();
        },

        initAside: function() {
            // Mobile offcanvas for mobile mode
            asideOffcanvas = new KTOffcanvas(asideEl, {
                overlay: true,
                baseClass: 'kt-todo__aside',
                closeBy: 'kt_todo_aside_close',
                toggleBy: 'kt_subheader_mobile_toggle'
            });
        },

        initList: function() {
            // View message
            KTUtil.on(listEl, '.kt-todo__item', 'click', function(e) {
                var actionsEl = KTUtil.find(this, '.kt-todo__actions');

                // skip actions click
                if (e.target === actionsEl || (actionsEl && actionsEl.contains(e.target) === true)) {
                    return false;
                }

                if (KTUtil.isInResponsiveRange('tablet-and-mobile') === false) {
                    return; // mobile mode
                }

                // demo loading
                var loading = new KTDialog({
                    'type': 'loader',
                    'placement': 'top center',
                    'message': 'Loading ...'
                });
                loading.show();

                setTimeout(function() {
                    loading.hide();

                    KTUtil.css(listEl, 'display', 'none');
                    KTUtil.css(viewEl, 'display', 'flex');
                }, 700);
            });

            // Group selection
            KTUtil.on(listEl, '.kt-todo__toolbar .kt-todo__check .kt-checkbox input', 'click', function() {
                var items = KTUtil.findAll(listEl, '.kt-todo__items .kt-todo__item');

                for (var i = 0, j = items.length; i < j; i++) {
                    var item = items[i];
                    var checkbox = KTUtil.find(item, '.kt-todo__actions .kt-checkbox input');
                    checkbox.checked = this.checked;

                    if (this.checked) {
                        KTUtil.addClass(item, 'kt-todo__item--selected');
                    } else {
                        KTUtil.removeClass(item, 'kt-todo__item--selected');
                    }
                }
            });

            // Individual selection
            KTUtil.on(listEl, '.kt-todo__item .kt-checkbox input', 'click', function() {
                var item = this.closest('.kt-todo__item');

                if (item && this.checked) {
                    KTUtil.addClass(item, 'kt-todo__item--selected');
                } else {
                    KTUtil.removeClass(item, 'kt-todo__item--selected');
                }
            });
        },

        initView: function() {
            // Back to listing
            KTUtil.on(viewEl, '.kt-todo__toolbar .kt-todo__icon.kt-todo__icon--back', 'click', function() {
                // demo loading
                var loading = new KTDialog({
                    'type': 'loader',
                    'placement': 'top center',
                    'message': 'Loading ...'
                });
                loading.show();

                setTimeout(function() {
                    loading.hide();

                    KTUtil.css(listEl, 'display', 'flex');
                    KTUtil.css(viewEl, 'display', 'none');
                }, 700);
            });
        },

        initCommentForm: function() {
            initEditor('kt_todo_post_editor');
            initAttachments('kt_todo_post_attachments');
        }
    };
}();

KTUtil.ready(function() {
    KTAppTodo.init();
});
