"use strict";

(function ($) {
    $(document).on('click', '#wpil_keywords_table .delete', wpil_keyword_delete);
    $(document).on('click', '#wpil_keywords_settings i', wpil_keyword_settings_show);
    $(document).on('click', '.link-whisper_page_link_whisper_keywords .column-keyword .dashicons', wpil_keyword_local_settings_show);
    $(document).on('click', '#wpil_keywords_settings input[type="submit"]', wpil_keyword_clear_fields);
    $(document).on('click', '#add_keyword_form a', wpil_keyword_add);
    $(document).on('click', '.wpil_keyword_local_settings_save', wpil_keyword_local_settings_save);
    $(document).on('click', '#wpil_keywords_reset_button', wpil_keyword_reset);
    $(document).on('click', '.wpil-insert-selected-keywords', wpil_insert_selected_keywords);

    if (is_wpil_keyword_reset) {
        wpil_keyword_reset_process(2, 1);
    }

    function wpil_keyword_delete() {
        if (confirm("Are you sure you want to delete this keyword?")) {
            var el = $(this);
            var id = el.data('id');

            $.post(ajaxurl, {
                action: 'wpil_keyword_delete',
                id: id
            }, function(){
                el.closest('tr').fadeOut(300);
            });
        }
    }

    function wpil_keyword_settings_show() {
        $('#wpil_keywords_settings .block').toggle();
    }

    function wpil_keyword_local_settings_show() {
        $(this).closest('td').find('.block').toggle();
    }

    $(document).on('change', '.wpil_keywords_set_priority_checkbox', wpilShowSetPriorityInput);
    function wpilShowSetPriorityInput(){
        var button = $(this);
        button.parent().find('.wpil_keywords_priority_setting_container').toggle();
    }

    $(document).on('change', '.wpil_keywords_restrict_date_checkbox', wpilShowRestrictDateInput);
    function wpilShowRestrictDateInput(){
        var button = $(this);
        button.parent().find('.wpil_keywords_restricted_date-container').toggle();
    }

    $(document).on('click', '.wpil-keywords-restrict-cats-show', wpilShowRestrictCategoryList);
    function wpilShowRestrictCategoryList(){
        var button = $(this);
        button.parents('.block').find('.wpil-keywords-restrict-cats').toggle();
        button.toggleClass('open');
    }

    function wpil_keyword_clear_fields() {
        $('input[name="keyword"]').val('');
        $('input[name="link"]').val('');
    }

    function wpil_keyword_add() {
        var form = $('#add_keyword_form');
        var keyword = form.find('input[name="keyword"]').val();
        var link = form.find('input[name="link"]').val();

        if(keyword.length === 0 || link.length === 0){
            wpil_swal({"title": "Auto-Link Field Empty", "text": "Please make sure there's a Keyword and a Link in the Auto-Link creation fields before attempting to creating an Auto-Link.", "icon": "info"});
            return;
        }

        var restrictedToDate = $('#wpil_keywords_restrict_date').prop('checked') ? 1 : 0;
        var restrictedToCat = $('#wpil_keywords_restrict_to_cats').prop('checked') ? 1 : 0;
        var setPriority = $('#wpil_keywords_set_priority').prop('checked') ? 1 : 0;

        form.find('input[type="text"]').hide();
        form.find('.progress_panel').show();
        var params = {
            keyword: keyword,
            link: link,
            wpil_keywords_add_same_link: $('#wpil_keywords_add_same_link').prop('checked') ? 1 : 0,
            wpil_keywords_link_once: $('#wpil_keywords_link_once').prop('checked') ? 1 : 0,
            wpil_keywords_select_links: $('#wpil_keywords_select_links').prop('checked') ? 1 : 0,
            wpil_keywords_set_priority: setPriority,
            wpil_keywords_restrict_date: restrictedToDate,
            wpil_keywords_restrict_to_cats: restrictedToCat,
        };

        if(setPriority){
            var priority = $('#wpil_keywords_priority_setting').val();
            if(!priority){
                priority = null;
            }
            params['wpil_keywords_priority_setting'] = priority; 
        }

        if(restrictedToDate){
            var date = $('#wpil_keywords_restricted_date').val();
            if(!date){
                date = null;
            }
            params['wpil_keywords_restricted_date'] = date; 
        }

        if(restrictedToCat){
            var selectedCats = [];
            $('#wpil_keywords_settings .wpil-restrict-keywords-input:checked').each(function(index, element){
                selectedCats.push($(element).data('term-id'));
            });

            params['restricted_cats'] = selectedCats; 
        }

        wpil_keyword_process(null, 0, form, params);
    }

    function wpil_keyword_local_settings_save() {
        var keyword_id = $(this).data('id');
        var form = $(this).closest('.local_settings');
        form.find('.block').hide();
        form.find('.progress_panel').show();
        var setPriority = form.find('input[type="checkbox"][name="wpil_keywords_set_priority"]').prop('checked') ? 1 : 0;
        var restrictedToDate = form.find('input[type="checkbox"][name="wpil_keywords_restrict_date"]').prop('checked') ? 1 : 0;
        var restrictedToCats = form.find('input[type="checkbox"][name="wpil_keywords_restrict_to_cats"]').prop('checked') ? 1 : 0;
        var params = {
            wpil_keywords_add_same_link: form.find('input[type="checkbox"][name="wpil_keywords_add_same_link"]').prop('checked') ? 1 : 0,
            wpil_keywords_link_once: form.find('input[type="checkbox"][name="wpil_keywords_link_once"]').prop('checked') ? 1 : 0,
            wpil_keywords_select_links: form.find('input[type="checkbox"][name="wpil_keywords_select_links"]').prop('checked') ? 1 : 0,
            wpil_keywords_restrict_date: restrictedToDate,
            wpil_keywords_restrict_to_cats: restrictedToCats,
            wpil_keywords_set_priority: setPriority
        };

        if(setPriority){
            var priority = form.find('input[name="wpil_keywords_priority_setting"]').val();
            if(!priority){
                priority = 0;
            }
            params['wpil_keywords_priority_setting'] = parseInt(priority); 
        }

        if(restrictedToDate){
            var date = form.find('input[name="wpil_keywords_restricted_date"]').val();
            if(!date){
                date = null;
            }
            params['wpil_keywords_restricted_date'] = date; 
        }

        if(restrictedToCats){
            var selectedCats = [];
            form.find('input.wpil-restrict-keywords-input[type="checkbox"]:checked').each(function(index, element){
                selectedCats.push($(element).data('term-id'));
            });

            params['restricted_cats'] = selectedCats; 
        }

        wpil_keyword_process(keyword_id, 0, form, params);
    }

    function wpil_keyword_process(keyword_id, total, form, params = {}) {
        var data = {
            action: 'wpil_keyword_add',
            nonce: wpil_keyword_nonce,
            keyword_id: keyword_id,
            total: total
        }

        for (var key in params) {
            data[key] = params[key];
        }

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            error: function (jqXHR, textStatus, errorThrown) {
                var wrapper = document.createElement('div');
                $(wrapper).append('<strong>' + textStatus + '</strong><br>');
                $(wrapper).append(jqXHR.responseText);
                wpil_swal({"title": "Error", "content": wrapper, "icon": "error"}).then(wpil_keyword_process(keyword_id, keyword, link));
            },
            success: function(response){
                if (response.error) {
                    wpil_swal(response.error.title, response.error.text, 'error');
                    return;
                }

                form.find('.progress_count').text(parseInt(response.progress) + '%');
                if (response.finish) {
                    location.reload();
                } else {
                    if (response.keyword_id && response.total) {
                        wpil_keyword_process(response.keyword_id, response.total, form);
                    }
                }
            }
        });
    }

    function wpil_keyword_reset() {
        $('#wpil_keywords_table .table').hide();
        $('#wpil_keywords_table .progress').show();
        wpil_keyword_reset_process(1, 1);
    }

    function wpil_keyword_reset_process(count, total) {
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'wpil_keyword_reset',
                nonce: wpil_keyword_nonce,
                count: count,
                total: total,
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var wrapper = document.createElement('div');
                $(wrapper).append('<strong>' + textStatus + '</strong><br>');
                $(wrapper).append(jqXHR.responseText);
                wpil_swal({"title": "Error", "content": wrapper, "icon": "error"}).then(wpil_keyword_reset_process(1, 1));
            },
            success: function(response){
                if (response.error) {
                    wpil_swal(response.error.title, response.error.text, 'error');
                    return;
                }

                var progress = Math.floor((response.ready / response.total) * 100);
                $('#wpil_keywords_table .progress .progress_count').text(progress + '%' + ' ' + response.ready + '/' + response.total);
                if (response.finish) {
                    location.reload();
                } else {
                    wpil_keyword_reset_process(response.count, response.total)
                }
            }
        });
    }

    function wpil_insert_selected_keywords(e){
        e.preventDefault();

        var parentCell = $(this).closest('.wpil-dropdown-column');
        var checkedLinks = $(this).closest('td.column-select_links').find('[name=wpil_keyword_select_link]:checked');
        var linkIds = [];

        $(checkedLinks).each(function(index, element){
            var id = $(element).data('select-keyword-id');
            if(id){
                linkIds.push(id);
            }
        });

        if(linkIds.length < 1){
            return;
        }

        // hide the dropdown and show the loading bar
        parentCell.find('.wpil-collapsible-wrapper').css({'display': 'none'});
        parentCell.find('.progress_panel.loader').css({'display': 'block'});

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'wpil_insert_selected_keyword_links',
                link_ids: linkIds,
                nonce: wpil_keyword_nonce,
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var wrapper = document.createElement('div');
                $(wrapper).append('<strong>' + textStatus + '</strong><br>');
                $(wrapper).append(jqXHR.responseText);
                wpil_swal({"title": "Error", "content": wrapper, "icon": "error"});
                // hide the loading bar and show the dropdown
                parentCell.find('.progress_panel.loader').css({'display': 'none'});
                parentCell.find('.wpil-collapsible-wrapper').css({'display': 'block'});
            },
            success: function(response){
                if (response.error) {
                    wpil_swal(response.error.title, response.error.text, 'error');

                    // hide the loading bar and show the dropdown
                    parentCell.find('.progress_panel.loader').css({'display': 'none'});
                    parentCell.find('.wpil-collapsible-wrapper').css({'display': 'block'});
                    return;
                }

                if (response.success) {
                    wpil_swal({"title": response.success.title, "text": response.success.text, "icon": "success"}).then(function(){
                        location.reload();
                    });
                } else {
                    location.reload();
                }
            }
        });
    }

    $('.wpil-select-all-possible-keywords').on('change', function(e){
        var id = $(this).data('keyword-id');
        if($(this).is(':checked')){
            $('.column-select_links .wpil-content .keyword-' + id + ' li input[name="wpil_keyword_select_link"]').prop('checked', true);
        }else{
            $('.column-select_links .wpil-content .keyword-' + id + ' li input[name="wpil_keyword_select_link"]').prop('checked', false);
        }
    });
})(jQuery);