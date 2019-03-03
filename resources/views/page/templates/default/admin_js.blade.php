@if($user && $user->access_level > 1)
jQuery.noConflict();
function admin() {
    var adminPanel;
    var adminOverlay;
    var menuHolder;
    var contentHolder;
    var messageHolder;
    var messageTimer;
    var ths = this;
    var $ = jQuery;
    var onUnload;

    ths.convertToSlug = function(Text) {
        var from = "ãàáäâẽèéëêìíïîõòóöôőùúüûűñç·/_,:;";
        var to   = "aaaaaeeeeeiiiioooooouuuuunc------";

        Text = Text.toLowerCase();

        for (var i=0, l=from.length ; i<l ; i++) {
            Text = Text.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        return Text
                .replace(/ /g,'-')
                .replace(/[\-]+/g,'-')
                .replace(/[^\w-]+/g,'');
    }

    ths.init = function() {
        adminPanel = $('<div id="admin-panel" />');
        $('body').append(adminPanel);
        adminPanel.prepend('<span class="text">{{ _("Tools:") }}</span>');

        var adminOverlayHTML = '<div id="admin-overlay" class="admin-overlay">';
            adminOverlayHTML += '<div class="content">';
                adminOverlayHTML += '<h2>{{ _("Administration") }}<i class="fa fa-close"></i></h2>';
                adminOverlayHTML += '<div class="menu"></div>';
                adminOverlayHTML += '<div class="holder"></div>';
                adminOverlayHTML += '<div class="message-holder"></div>';
            adminOverlayHTML += '</div>';
        adminOverlayHTML += '</div>';
        adminOverlay = $(adminOverlayHTML);
        adminOverlay.hide();
        adminOverlay.on('close', function(e) {
            if(e.target==this) {
                ths.hideAdminOverlay();
            }
        });
        $('body').append(adminOverlay);

        menuHolder = adminOverlay.find('> .content').find('> .menu');
        contentHolder = adminOverlay.find('> .content').find('> .holder');
        messageHolder = adminOverlay.find('> .content').find('> .message-holder');

        var at = $('<button class="admin-tools">{{ _("Admin tools") }}</button>');
        at.on('click', function(e) {
            ths.showAdminOverlay();
        })
        adminPanel.append(at);

        $(adminOverlay).on('click', 'button[name="cancel"]', function(e) {
            var callback = $(this).parent().find('button[name="submit"]').data('onUnload');
            if(callback) {
                callback();
            }

            ths.welcomeMessage();
        });

        $(adminOverlay).on('click', 'h2 .fa', function(e) {
            adminOverlay.trigger('close');
        });

        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
                adminOverlay.trigger('close');
            }
        });

        $(adminOverlay).on('click', 'button[name="submit"], button[name="delete"]', function(e) {
            e.preventDefault();

            var f = $(this).closest('form');
            var formType = f.data('type') + ($(this).attr('name') == 'delete' ? '_Delete' : '');
            var formHandler = f.data('handler') ? f.data('handler') : 'Admin';
            var fields = {};
            var $this = $(this);

            var invalidField = '';

            $this.blur();
            $this.addClass('processing').removeClass('done').removeClass('fail');

            f.find('input[required], select[required], textarea[required]').each(function( index ) {
                if($.trim($(this).val()).length == 0 && ['radio', 'checkbox'].indexOf($(this).attr('type')) == -1) {
                    invalidField = $(this);
                }
                if(['radio', 'checkbox'].indexOf($(this).attr('type')) !== -1) {
                    if(!f.find('input[name="' + $(this).attr('name') + '"]').is(':checked')) {
                        invalidField = $(this);
                    }
                }
            });

            if(invalidField !== '') {
                alert("{{ _("Please fill all required fields.") }}");
                invalidField.focus();

                $this.removeClass('processing').removeClass('done').addClass('fail');
                return false;
            }

            var a = f.serializeArray();
            $.each(a, function() {
                if (fields[this.name] !== undefined) {
                    if (!fields[this.name].push) {
                        fields[this.name] = [fields[this.name]];
                    }
                    fields[this.name].push(this.value || '');
                } else {
                    fields[this.name] = this.value || '';
                }
            });

            ths.ajaxCall(
                {
                    handler: formHandler,
                    type: formType,
                    //'_token': f.find('input[name="_token"]').val(),
                    formFields: fields
                },
                function(data) {
                    console.log(data);

                    $this.removeClass('processing').addClass('done');

                    adminTools.showMessage(data.message);

                    if($this.data('onUnload')) {
                        $this.data('onUnload')(data);
                    }
                }
            );
        });
    }

    ths.ajaxCall = function(params, callback) {
            var ajax_params = {
                handler: 'Admin',
                type: '',
                '_token': $('{!! Form::token() !!}').val()
            };

            for (var prop in params) {
                ajax_params[prop] = params[prop];
            }

            $.ajax({
                cache: false,
                url: "{{ URL::to("/ajax") }}",
                method: "POST",
                //contentType: 'application/json',
                //dataType: "json",
                data: ajax_params,
                success: function(data) {
                    //console.log(data);

                    if(typeof(callback) !== "undefined") {
                        callback(data);
                    }
                }
            });
    }

    ths.createDropzone = function(holder, params, callback) {
        var ajax_params = {
            handler: 'Admin',
            type: 'AddFileVersion',
            '_token': $('{!! Form::token() !!}').val()
        };

        for (var prop in params) {
            ajax_params[prop] = params[prop];
        }

        $(holder).dropzone({
            url: "/ajax",
            uploadMultiple: false,
            paramName: "fileUpload",
            sending: function(file, xhr, formData) {
                for (var prop in ajax_params) {
                    formData.append(prop, ajax_params[prop]);
                }
            },
            success: function(file, data) {
                if(typeof(callback) !== "undefined") {
                    callback(file, data, this);
                }
            }
        });
    }

    ths.addMenu = function(o) {
        var htmlStr = '';
        var params = {
            'name': 'Menupoint',
            'class': 'admin-menu',
            'click': function() { console.log('click is not set.'); }
        }

        for (var i in o) {
            params[i] = o[i];
        }

        var menuPoint = $('<a href="javascript:void(0);"></a>');
        menuPoint.addClass(params.class);
        menuPoint.html(params.name);
        menuPoint.data('click', params.click);
        menuPoint.on('click', ths.clickMenu);

        menuHolder.append(menuPoint);
    }

    ths.clickMenu = function(e) {
        var $this = $(e.target);

        if(!$this.hasClass('selected')) {
            menuHolder.find('a').removeClass('selected');
            $this.addClass('selected');
        }

        if($this.data('click') && $this.data('click') !== '') {
            e.preventDefault();
            $this.data('click')(e);
        }
    }

    ths.setContent = function(c, callback) {
        if(typeof(onUnload) !== "undefined") {
            onUnload();
        }

        contentHolder.html(c);
        //$(c).appendTo(contentHolder);
        onUnload = callback;
    }

    ths.hideAdminOverlay = function() {
        if(adminOverlay.is(':visible')) adminOverlay.fadeOut(100, function() {
            menuHolder.find('a').removeClass('selected');
            $(document.body).removeClass('overlay-open');
            ths.welcomeMessage();
        });
    }

    ths.showAdminOverlay = function() {
        if(!adminOverlay.is(':visible')) {
            adminOverlay.fadeIn(100);
            $(document.body).addClass('overlay-open');
            ths.welcomeMessage();
        }
    }

    ths.welcomeMessage = function() {
        var htmlStr = '';
        htmlStr += '<h3>{{ _("Welcome. Use the left navigation to set the contents.") }}</h3>';
        ths.setContent(htmlStr);
        menuHolder.find('a').removeClass('selected');
    }

    ths.showMessage = function(message) {
        clearTimeout(messageTimer);
        messageHolder.html(message).fadeIn(200);

        messageTimer = setTimeout(function() {
            messageHolder.fadeOut(200);
        }, 1000 * 20);
    }
};
var adminTools;

(function ($) {
$(document).ready(function() {
        var p = {!! json_encode($originalPage) !!};
        var g = {!! json_encode($global) !!};

        console.log(p);
        console.log(g);

        adminTools = new admin;
        adminTools.init();

        adminTools.addMenu({
            name: "<i class='fa fa-plus'></i>{{ _("New post") }}",
            class: "new-post",
            click: function() {
                showPostEdit();
            }
        });

        adminTools.addMenu({
            name: "<i class='fa fa-pencil'></i>{{ _("Edit post") }}",
            class: "edit-post",
            click: function() {
                showPostEdit(p);
            }
        });

        adminTools.addMenu({
            name: "<i class='fa fa-remove'></i>{{ _("Delete post") }}",
            class: "delete-post",
            click: function() {
                showPostDelete(p);
            }
        });

        adminTools.addMenu({
            name: "<i class='fa fa-plug'></i>{{ _("Attached posts") }}",
            class: "attached-posts",
            click: function() {
                showPostAttachments(p);
            }
        });

        adminTools.addMenu({
            name: "<i class='fa fa-globe'></i>{{ _("Global Assignments") }}",
            class: "global-assignments",
            click: function() {
                showGlobalAttachments(g);
            }
        });

        adminTools.addMenu({
            name: "<i class='fa fa-recycle'></i>{{ _("Delete caches") }}",
            class: "delete-caches",
            click: function() {
                showDeleteAllCache();
            }
        });

        function showPostEdit(p) {
        	var htmlStr = '';
            var postEdit;
            var isEdit = typeof(p) !== "undefined";

            htmlStr += '<div class="post-edit-container">';

                htmlStr += '<form class="post-edit" data-type="AddPostVersion">';
                    htmlStr += '<div class="message-holder" />';
                    htmlStr += '{!! Form::token() !!}';
                    htmlStr += '<label for="post_title">{{ _("Post title") }}</label>';
                    htmlStr += '<input type="text" id="page_title" name="title" />';

                    htmlStr += '<label for="post_uri">{{ _("Post URI") }}</label>';
                    htmlStr += '<input type="text" id="page_uri" name="uri" required />';

                    htmlStr += '<label for="post_description">{{ _("Post description") }}</label>';
                    htmlStr += '<input type="text" name="description" id="page_description" />';

                    htmlStr += '<label for="post_keywords">{{ _("Post keywords") }}</label>';
                    htmlStr += '<input type="text" name="keywords" id="page_keywords" />';

                    htmlStr += '<label for="post_excerpt">{{ _("Post excerpt") }}</label>';
                    htmlStr += '<textarea name="excerpt" id="page_excerpt"></textarea>';

                    htmlStr += '<label for="post_body">{{ _("Post body") }}</label>';
                    htmlStr += '<textarea name="body" id="page_body"></textarea>';

                    htmlStr += '<label for="post_post_type">{{ _("Post type") }}</label>';
                    htmlStr += '<input type="text" id="post_post_type" name="post_type" value="post" />';

                    htmlStr += '<label for="post_access_level">{{ _("Access level") }}</label>';
                    htmlStr += '<input type="number" id="post_access_level" name="access_level" value="0" />';

                    htmlStr += '<label for="post_related">';
                        htmlStr += '<input type="checkbox" id="post_related" />';
                        htmlStr += '{{ _("Create relation to current, with position:") }}';
                    htmlStr += '</label>';
                    htmlStr += '<input type="text" id="post_related_position" disabled placeholder="{{ _("Enter relation position here") }}" />';

                    htmlStr += '<button type="button" name="submit"><i class="fa fa-plus"></i>{{ _("Add") }}</button>';
                    htmlStr += '<button type="button" name="cancel"><i class="fa fa-close"></i>{{ _("Cancel") }}</button>';
                htmlStr += '</form>';

                htmlStr += '<div class="dropZone">';
                    htmlStr += '<form action="/ajax" method="post" enctype="multipart/form-data">';
                        htmlStr += '{!! Form::token() !!}';
                        htmlStr += '<input type="file" name="fileUpload" />';
                    htmlStr += '</form>';
                htmlStr += '</div>';

            htmlStr += '</div>';

            postEdit = $(htmlStr);

            if(isEdit) {
                postEdit.find('button[name="submit"]').html('<i class="fa fa-plus"></i>{{ _("Update") }}');
                postEdit.find('input[name="title"]').val(p.title);
                postEdit.find('input[name="uri"]').val(p.uri);
                postEdit.find('input[name="description"]').val(p.description);
                postEdit.find('input[name="keywords"]').val(p.keywords);
                postEdit.find('textarea[name="excerpt"]').val(p.excerpt);
                postEdit.find('textarea[name="body"]').val(p.body);
                postEdit.find('input[name="post_type"]').val(p.post_type);
                postEdit.find('input[name="access_level"]').val(p.access_level);
            }

            adminTools.setContent(postEdit);

            if(!isEdit) {
                var currentPage = {!! json_encode($originalPage) !!};

                postEdit.find('input[name="title"]').on('change', function(e) {
                    if(postEdit.find('input[name="uri"]').val() == "") {
                        //try to guess a slug
                        postEdit.find('input[name="uri"]').val('/' + (new Date().getFullYear()) + '/' + adminTools.convertToSlug($(this).val()));
                    }
                });

                postEdit.find('#post_related').on('change', function(e) {
                    postEdit.find('#post_related_position').prop('disabled', !$(this).is(':checked'));
                });

                postEdit.find('#post_related_position').autocomplete({
                    source: ['featuredImage', 'post-thumbnail', 'none'],
                    minLength: 0,
                    appendTo: "#admin-overlay .post-edit"
                });

                postEdit.find('#post_related_position').focus(function(e) {
                    postEdit.find('#post_related_position').autocomplete("search");
                });

                postEdit.find('button[name="submit"]').data('onUnload', function(data) {
                    window.open(
                        data.post.uri +
                        (data.post.post_type == 'attachment' ? '?view=page' : '')
                    , '_blank');
                    console.log('{{ _("Post added.") }}');

                    if(postEdit.find('#post_related').is(':checked') && postEdit.find('#post_related_position').val() !== "")
                        adminTools.ajaxCall({
                            handler: "Admin",
                            type: "RelatedPost",
                            formFields: {
                                uri_a: data.post.uri,
                                uri_b: currentPage.uri,
                                position: postEdit.find('#post_related_position').val()
                            }
                        },
                        function(related_data) {
                            console.log('{{ _("Post relation added.") }}');
                        });
                })
            }
            else {
                postEdit.find('label[for="post_related"]').hide();
                postEdit.find('#post_related_position').hide();
            }

            adminTools.createDropzone(
                postEdit.find(".dropZone"),
                {
                    handler: "Admin",
                    type: "AddFileVersion"
                },
                function(file, data, dz) {
                    if(data.success) {
                        postEdit.find('textarea[name="body"]').val(data.storage_path);
                        postEdit.find('input[name="post_type"]').val('attachment');
                        console.log(file);
                        dz.removeAllFiles();
                    }
                    else {
                        console.log(data.message);
                    }

                    adminTools.showMessage(data.message);
                }
            );

            adminTools.showAdminOverlay();
        }

        function showPostDelete(p) {
        	var htmlStr = '';
            var postDelete;
            var isEdit = typeof(p) !== "undefined";

            htmlStr += '<form class="post-delete" data-type="DeletePost">';
                htmlStr += '{!! Form::token() !!}';
                htmlStr += '<div class="delete-message">';
                htmlStr += '{{ _("Are you sure you wish to delete the post?") }}';
                htmlStr += '</div>';

                if(isEdit) htmlStr += '<input type="hidden" name="uri" value="' + p.uri + '">';
                htmlStr += '<button type="button" name="submit"><i class="fa fa-check"></i>{{ _("Delete") }}</button>';
                htmlStr += '<button type="button" name="cancel"><i class="fa fa-close"></i>{{ _("Cancel") }}</button>';
            htmlStr += '</form>';

            postDelete = $(htmlStr);

            adminTools.setContent(postDelete);

            adminTools.showAdminOverlay();
        }

        function showPostAttachments(p) {
        	var htmlStr = '';
            var postAttachments;
            if(typeof(p) === "undefined") return;

            htmlStr += '<div class="related-holder">';
            for(var i in p.relatedPosts) {
                var relatedPost = p.relatedPosts[i];
                htmlStr += '<form class="related-post" data-type="RelatedPost">';
                    htmlStr += '{!! Form::token() !!}';
                    htmlStr += '<div class="related-title" title="{{ _("Title") }}">';
                    htmlStr += relatedPost.title;
                    htmlStr += '</div>';

                    htmlStr += '<label for="related_position_'+i+'">{{ _("Position") }}</label>';
                    htmlStr += '<input type="text" name="position" id="related_position_'+i+'" data-value="' + relatedPost.position + '" value="' + relatedPost.position + '" />';

                    htmlStr += '<input type="hidden" name="id" value="' + relatedPost.relation_id + '" />';
                    htmlStr += '<input type="hidden" name="uri_a" value="' + relatedPost.uri_a + '" />';
                    htmlStr += '<input type="hidden" name="uri_b" value="' + relatedPost.uri_b + '" />';

                    htmlStr += '<a class="related-link" href="' + relatedPost.uri + (relatedPost.post_type == 'attachment' ? '?view=page' : '') + '" target="_blank" title="{{ _("Click to open in new tab") }}">';
                    htmlStr += "{{ _("Click to open in new tab") }}";
                    htmlStr += '</a>';

                    htmlStr += '<button type="button" name="submit"><i class="fa fa-refresh"></i>{{ _("Update connection") }}</button>';
                    htmlStr += '<button type="button" name="delete"><i class="fa fa-trash-o"></i>{{ _("Delete connection") }}</button>';
                    //htmlStr += '<button type="button" name="cancel">{{ _("Cancel") }}</button>';
                htmlStr += '</form>';
            }
                htmlStr += '<form class="related-post-add" data-type="RelatedPost">';
                    htmlStr += '{!! Form::token() !!}';

                    htmlStr += '<label for="related_position_add">{{ _("Position") }}</label>';
                    htmlStr += '<input type="text" name="position" id="related_position_add" placeholder="{{ _("Enter the position of the connection") }}" />';

                    htmlStr += '<label for="related_uri_add">{{ _("URI to attach") }}</label>';
                    htmlStr += '<input type="text" name="uri_a" id="related_uri_add" placeholder="{{ _("Enter the uri to attach") }}" />';

                    htmlStr += '<input type="hidden" name="uri_b" value="' + p.uri + '" />';

                    htmlStr += '<button type="button" name="submit"><i class="fa fa-plus-square-o"></i>{{ _("Add connection") }}</button>';
                    //htmlStr += '<button type="button" name="cancel">{{ _("Cancel") }}</button>';
                htmlStr += '</form>';
            htmlStr += '</div>';

            postAttachments = $(htmlStr);

            adminTools.setContent(postAttachments);

            adminTools.showAdminOverlay();
        }

        function showGlobalAttachments(g) {
        	var htmlStr = '';
            var globalAttachments;
            if(typeof(g) === "undefined") return;

            htmlStr += '<div class="global-holder">';
            for(var i in g.relatedPosts) {
                var relatedPost = g.relatedPosts[i];
                htmlStr += '<form class="global-post" data-type="GlobalPost">';
                    htmlStr += '{!! Form::token() !!}';
                    htmlStr += '<div class="related-title" title="{{ _("Title") }}">';
                    htmlStr += relatedPost.title;
                    htmlStr += '</div>';

                    htmlStr += '<label for="related_position_"'+i+'">{{ _("Position") }}</label>';
                    htmlStr += '<input type="text" name="position" id="related_position_"'+i+'" value="' + relatedPost.position + '" />';

                    htmlStr += '<input type="hidden" name="uri_a" value="' + relatedPost.uri + '">';
                    htmlStr += '<a class="related-link" href="' + relatedPost.uri + (relatedPost.post_type == 'attachment' ? '?view=page' : '') + '" target="_blank" title="{{ _("Click to open in new tab") }}">';
                    htmlStr += "{{ _("Click to open in new tab") }}";
                    htmlStr += '</a>';

                    htmlStr += '<button type="button" name="submit"><i class="fa fa-refresh"></i>{{ _("Update connection") }}</button>';
                    htmlStr += '<button type="button" name="delete"><i class="fa fa-trash-o"></i>{{ _("Delete connection") }}</button>';
                    //htmlStr += '<button type="button" name="cancel">{{ _("Cancel") }}</button>';
                htmlStr += '</form>';
            }
                htmlStr += '<form class="global-post-add" data-type="GlobalPost">';
                    htmlStr += '{!! Form::token() !!}';

                    htmlStr += '<label for="global_position_add">{{ _("Position") }}</label>';
                    htmlStr += '<input type="text" name="position" id="related_position_add" placeholder="{{ _("Enter the position of the connection") }}" />';

                    htmlStr += '<label for="global_uri_add">{{ _("URI to attach") }}</label>';
                    htmlStr += '<input type="text" name="uri_a" id="global_uri_add" placeholder="{{ _("Enter the uri to attach") }}" />';

                    htmlStr += '<input type="hidden" name="uri_b" value="" />';

                    htmlStr += '<button type="button" name="submit"><i class="fa fa-plus-square-o"></i>{{ _("Add connection") }}</button>';
                    //htmlStr += '<button type="button" name="cancel">{{ _("Cancel") }}</button>';
                htmlStr += '</form>';
            htmlStr += '</div>';

            globalAttachments = $(htmlStr);

            adminTools.setContent(globalAttachments);

            adminTools.showAdminOverlay();
        }

        function showDeleteAllCache(c) {
            var htmlStr = '';
            var allCacheDelete;
            //var isEdit = typeof(c) !== "undefined";

            htmlStr += '<form class="post-delete" data-type="ClearCache_All">';
                htmlStr += '{!! Form::token() !!}';
                htmlStr += '<div class="delete-message">';
                htmlStr += '{{ _("Are you sure you wish to delete all datas cached?") }}';
                htmlStr += '</div>';

                //if(isEdit) htmlStr += '<input type="hidden" name="uri" value="' + p.uri + '">';
                htmlStr += '<button type="button" name="submit"><i class="fa fa-check"></i>{{ _("Delete") }}</button>';
                htmlStr += '<button type="button" name="cancel"><i class="fa fa-close"></i>{{ _("Cancel") }}</button>';
            htmlStr += '</form>';

            allCacheDelete = $(htmlStr);

            adminTools.setContent(allCacheDelete);

            adminTools.showAdminOverlay();
        }
});
})(jQuery);
@endif
