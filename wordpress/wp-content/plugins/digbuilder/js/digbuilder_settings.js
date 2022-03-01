jQuery(function () {
    setTimeout(function () {
        jQuery("#elementor-switch-mode-button").trigger('click');
    }, 200);

    var loader = jQuery(".dig_load_overlay");


    var new_layout = jQuery(".digits-builder_new_form");
    jQuery(".wrap").append(new_layout);


    var new_button;

    jQuery(document).on("click", ".page-title-action", function (e) {
        e.preventDefault();

        jQuery('body').addClass('digits-no-overflow');

        new_button = jQuery(this);
        if (new_button.hasClass('import_preset')) {
            show_uploader();
            new_layout.fadeIn('fast');
            new_layout.find('.digits-builder-popup-heading').text(digbuilder.import_preset);
        } else {
            hide_uploader();
            new_layout.find('.digits-builder-popup-heading').text(digbuilder.preset_library);
            if (!new_layout.data('loaded') || new_layout.data('loaded') === 0) {
                load_presets();
            } else {
                new_layout.fadeIn('fast');
            }
        }
        return false;
    });

    function load_presets() {
        show_loader();
        jQuery.ajax({
            type: 'get',
            url: 'https://bridge.unitedover.com/digits/presets/',
            data: {
                action: 'get_metadata',
                type: new_layout.find('.preset_type').val(),
            },
            success: function (res) {
                new_layout.data('loaded', 1).fadeIn('fast');
                load_data(res);
            },
            error: function () {
                load_data(null);
                showDigErrorMessage(digbuilder.error_loading_preset);
            }
        });
    }

    function load_data(data) {
        var loaded = 0;
        var preset_template = jQuery('#digbuilder-preset-template').html();
        var presets_container = new_layout.find('.digits-builder-presets');
        presets_container.empty();
        var new_post = jQuery(preset_template).clone();
        new_post.addClass('digb_preset_new').find('a').data('new', 1);
        presets_container.append(new_post);
        if (data != null) {
            try {
                jQuery.each(data, function (key, preset) {
                    var template = jQuery(preset_template).clone();
                    template.find('.digb_select_preset').attr({
                        'href': '#',
                        'data-slug': key
                    });
                    template.find('.digb_preset_name').text(preset.name);
                    template.find('.digb_preset_preview').attr('src', preset.thumb);
                    template.find('.dig_preset_big_img').attr('href', preset.preview);

                    presets_container.append(template);
                });
                loaded = 1;
            } catch (e) {

            }
        }
        new_layout.fadeIn('fast').data('loaded', loaded);
        hide_loader();

    }

    jQuery(document).on("click", ".digb_select_preset", function (e) {
        var $this = jQuery(this);


        if ($this.data('new')) return true;


        new_layout.find(".dig_preset_selected").removeClass('dig_preset_selected');
        $this.addClass('dig_preset_selected');
        var slug = jQuery(this).data('slug');
        new_layout.find('input[name="preset_slug"]').val(slug);

        return false;
    });

    function show_uploader() {
        toggle_uploader(true);
    };

    function hide_uploader() {
        toggle_uploader(false);
    }

    function toggle_uploader($show) {
        var body = new_layout.find('.modal-body');
        var cls = 'select_file digits-builder-body-border digits-builder-body-center';
        if (!$show) {
            new_layout.removeClass('file_selector');
            body.removeClass(cls);
        } else {
            new_layout.addClass('file_selector');
            body.addClass(cls);
        }
    }

    jQuery(document).on("click", ".digpage_new_import_form .select_file", function (e) {
        e.preventDefault();
        e.stopPropagation();
        jQuery(this).closest('form').find('.digpreset_upload').trigger('click');
    });


    jQuery(document).on("change", ".digpreset_upload", function (e) {
        var fileName = e.target.files[0].name;
        new_layout.find('.select_file_text').text(fileName);
        new_layout.data('submit', true);
        new_layout.find('input[name="preset_slug"]').val('');
    });

    jQuery(".digpreset_import_button").on('click', function () {
        var form = jQuery(this).closest('form');

        if (!form.find('.dig_preset_selected').length && !form.find('input[type="file"]').val()) {
            return false;
        }
        if (form.find('.dig_preset_selected').length && form.find('.dig_preset_selected').is(":visible")) {
            var select = form.find('.digits-settings_select');
            if (select.val() == -1) {
                showDigNoticeMessage(digbuilder.please_select_type);
                return false;
            }
        }
        jQuery(this).closest('form').submit();
    })

    jQuery(".page-title-action").after('' +
        '<a href="#" class="page-title-action import_preset">' + digbuilder.import + '</a>');


    jQuery(document).on('keyup', function (e) {
        hideDigMessage();
        if (e.keyCode == 27 && new_layout) {
            if (new_layout.is(':visible')) {
                new_layout.find('.digits-overlay-close').trigger('click');
            }
        }
    });

    jQuery(document).on("dragover", ".select_file", function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    jQuery(document).on("dragenter", ".select_file", function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    jQuery(document).on("drop", ".select_file", function (e) {
        e.preventDefault();
        e.stopPropagation();
        hideDigMessage();

        if (window.FileReader) {
            if (e.originalEvent.dataTransfer.files.length == 1) {
                var file = e.originalEvent.dataTransfer.files[0];
                var type = file.type;
                if (type != 'application/json') {
                    showDigErrorMessage(digbuilder.only_json);
                    return;
                }
                var form = jQuery(this).closest('form');
                var data = new FormData(form[0]);
                data.append('file', file);
                data.append('action', 'digbuilder_import_preset');
                submit_form(data);
            } else {
                showDigErrorMessage(digbuilder.multiple_not_supported);
            }
        } else showDigErrorMessage(digbuilder.browser_not_supported);
    });


    function submit_form(data) {
        loader.show();

        jQuery.ajax({
            type: 'post',
            url: digbuilder.ajax_url,
            data: data,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success === false) {
                    loader.hide();
                    showDigErrorMessage(res.data.message);
                } else {
                    window.location.href = jQuery.trim(res.data.redirect);
                }
            },
            error: function () {
                loader.hide();
                showDigErrorMessage(digbuilder.error);
            }
        });
    }

    function show_loader() {
        loader.show();
    }

    function hide_loader() {
        loader.hide();
    }

});