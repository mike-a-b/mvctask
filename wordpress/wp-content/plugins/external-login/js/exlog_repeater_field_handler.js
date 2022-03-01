// Possible data structure

var possible_repeater_data_master = [
  {
    "field_name": "title",
    "id": "12345678",
    "repeater_field": false,
    "field_data": "this is some text"
  },
  {
    "field_name": "Names Repeater",
    "id": "12345678",
    "repeater_field": true,
    "field_data": [ // Repeater items
      [ // Repeater Item
        {
          "field_name": "title in repeater",
          "id": "12345678",
          "repeater_field": false,
          "field_data": "this is some textoooo"
        },
        {
          "field_name": "title in repeater222",
          "id": "12345678",
          "repeater_field": false,
          "field_data": "this is some textoooo222"
        },
        {
          "field_name": "inner_repeater",
          "id": "12345678",
          "repeater_field": true,
          "field_data": [ // Repeater Items
            [ // Repater Item
              {
                "field_name": "title in repeater333",
                "id": "12345678",
                "repeater_field": false,
                "field_data": "this is some textoooo3333"
              },
              {
                "field_name": "title in repeater3331",
                "id": "12345678",
                "repeater_field": false,
                "field_data": "this is some textoooo3332"
              }
            ],
            [ // Repater Item 2
              {
                "field_name": "title in repeater333",
                "id": "12345678",
                "repeater_field": false,
                "field_data": "this is some textoooo3333"
              },
              {
                "field_name": "title in repeater3331",
                "id": "12345678",
                "repeater_field": false,
                "field_data": "this is some textoooo3332"
              }
            ]
          ]
        }
      ]
    ]
  }
];

(function ($) {
  $(function () {
    var $parent_repeater_fields = $(".option-container.repeater.exlog-repeater-master");
    var repeater_buttons_selector = ".exlog_repeater_add_button";
    var repeater_item_selector = ".repeater_item";
    var repeater_data_attr = 'data-exlog-repeater-id';
    var master_markup_item_selector = repeater_item_selector + '[' + repeater_data_attr + '="0"]';
    var click_event_name = 'click';
    var change_events = ['keyup', 'paste'];
    var repeater_data_store_selector = '.exlog_repeater_data_store';

    function object_to_string_if_object(data) {
      if (typeof data === 'object') {
        try {
          data = JSON.stringify(data);
        } catch (error) {
          //  Leave data in current format
        }
      }
      return data
    }

    function place_data_from_db() {
      function place_specific_repeater_values($parent_element, data) {
        //  Put all data in the name value
        var $data_store = $parent_element.children(repeater_data_store_selector);
        var data_string;
        try {
          data_string = JSON.stringify(data);
        } catch (e) {
          console.log('EXLOG: Failed to parse some data from the database.\nIncorrect data:', data);
          data_string = false;
        }
        $data_store.val(data_string);

        // Of that data, put each value in the correct input
        if (data && Array.isArray(data)) {
          data.forEach(function (repeater_item, i) {
            var $repeater_item;
            if (i > 0) { // If not the first repeater item - create a new one in the DOM and store the jQuery object
              $repeater_item = create_new_item($parent_element, i, false);
            } else { // If the first repeater item, get the one repeater jQuery object
              $repeater_item = $parent_element.find('> .repeater_item');
            }

            repeater_item.forEach(function (repeater_item_option) {

              var input_data = object_to_string_if_object(repeater_item_option['value']);

              var input_selector = '> .repeater_item_input_container > .option-container > [name="' + repeater_item_option['name'] + '"]';

              // Add DB value into the input
              var $input_element = $repeater_item.find(input_selector).val(input_data);

              //  If the input data is a repeater field, call this function again with the relevant data and $parent element
              if (repeater_item_option['repeater_field']) {
                place_specific_repeater_values($input_element.closest('.option-container'), repeater_item_option['value']);
              }
            });
          });
        }
      }

      // For each repeater field on the page, put the master data into the repeater master component and then start
      // placing specific values
      $parent_repeater_fields.each(function () {
        var $parent_repeater_field = $(this);
        var base_64_string = $parent_repeater_field.children(repeater_data_store_selector).val();
        var parent_repeater_data;
        try {
          parent_repeater_data = JSON.parse(atob(atob(base_64_string))); // Once for storing from server, once for data store
        } catch (e) {
          parent_repeater_data = false;
        }
        place_specific_repeater_values($parent_repeater_field, parent_repeater_data)
      });

    }

    function create_new_item($repeater_option_container, item_id) {
      // Get the markup to copy from looking at first item
      var markup = $repeater_option_container.children(master_markup_item_selector).html();

      var $markup = $('<section class="repeater_item">' + markup + '</section>');

      // Clean out any repeater ids that are not the first one from the generated markup
      $(repeater_item_selector, $markup).each(function () {
        var $repeater_item = $(this);
        if ($repeater_item.attr(repeater_data_attr) !== "0") {
          $repeater_item.remove();
        }
      });

      // Store the new id in the attr of the repeater item
      $markup.attr('data-exlog-repeater-id', item_id);

      var $markup_inputs = $markup.children('.repeater_item_input_container').find('.option-container input:not([type=button])');

      $markup_inputs.each(function () {
        var $markup_input = $(this);
        $markup_input.val('');
      });

      // Place the new markup on the page
      $repeater_option_container.children('.add_more').before($markup);
      reset_watchers();
      return $markup;
    }

    function reselect_add_buttons() {
      $(repeater_buttons_selector).off(click_event_name).on(click_event_name, on_add_button_click);
    }

    function on_add_button_click() {
      var $button = $(this);

      var $add_more_container = $button.closest(".add_more");

      var used_ids = [];
      // Store all used ids
      $add_more_container.siblings(repeater_item_selector).each(function () {
        var $repeater_item = $(this);
        var repeater_item_id = $repeater_item.attr(repeater_data_attr);
        used_ids.push(repeater_item_id)
      });

      // Get a unique id for the new repeater item
      var new_id = 1;
      while (used_ids.indexOf(new_id.toString()) > -1) { // While new_id is in used_ids
        new_id += 1;
      }

      var $repeater_option_container = $add_more_container.closest('.option-container.repeater');

      create_new_item($repeater_option_container, new_id, false);
    }

    function monitorRepeaterInputs() {
      var change_events_string = change_events.join(' ');
      var $repeater_data_stores = $(repeater_data_store_selector);

      $repeater_data_stores.each(function () {
        var $repeater_data_store = $(this);
        var $inputs = $repeater_data_store.siblings('.repeater_item').children('.repeater_item_input_container').children('.option-container').children('input, textarea');

        // Clear previous events
        $inputs.off(change_events_string);

        $inputs.on(change_events_string, (function () {
          update_repeater_data($repeater_data_store, $inputs);
          $repeater_data_store.trigger(change_events[0]);
        }));
      });
    }

    /**
     * On field updates, this updates the hidden input with the newly computed data from the view
     * @param $repeater_data_store The hidden input element that will save the computed data to the database
     */
    function update_repeater_data($repeater_data_store) {
      var data_for_store = []; // Object to be populated by first child inputs of repeater
      var repeater_items = $repeater_data_store.siblings('.repeater_item');
      repeater_items.each(function () {
        var repeater_item = [];
        var $inputs = $(this).children('.repeater_item_input_container').children('.option-container').children('input, textarea');
        $inputs.each(function () {
          var $input = $(this);
          var value = $input.val();
          var repeater_field = false;
          if ($input.hasClass('exlog_repeater_data_store')) {
            repeater_field = true;
            try {
              if (value) {
                value = JSON.parse(atob(value)); // If the data can be interpreted as JSON convert it to an object
              }
            } catch (error) {
              console.log("EXLOG: Error storing repeater data for '" + $input.attr('name') + "'", "Value:", value);
            }
          }
          repeater_item.push({
            "name": $input.attr('name'),
            "repeater_field": repeater_field,
            "value": value
          });
        });
        data_for_store.push(repeater_item);
      });

      $repeater_data_store.val(btoa(JSON.stringify(data_for_store)));
    }

    function on_delete_item_click() {
      $('.delete_repeater_item').off(click_event_name).on(click_event_name, function () {
        var $delete_button = $(this);
        var $repeater_item = $delete_button.closest(repeater_item_selector);

        // var $repeater_item_name = $repeater_item.attr('name');

        var $first_item = $repeater_item.siblings(master_markup_item_selector).find('input');

        // Remove the repeater item from the DOM
        $repeater_item.remove();

        // Ensure elements no longer in the DOM are no longer selected as otherwise data is kept
        reset_watchers();

        // Make first repeater item trigger change to remove deleted items data
        $first_item.trigger(change_events[0]);
      })
    }

    // All the selectors that need updating on DOM change
    function reset_watchers() {
      monitorRepeaterInputs();
      reselect_add_buttons();
      on_delete_item_click();
    }

    /**
     * This is a temporary solution to the fact that repeater data is not properly stored in the saving hidden input
     * when it comes from the database
     * This simply triggers all repeater items so that the JS can sore the current data in the format that the database
     * expects when saving
     * A more complete solution would be to ensure that the starting data from the DB is by default put into the hidden
     * input in the correct format
     */
    function triggerAllRepeaterInputs() {
      $(repeater_item_selector + ' input').each(function () {
        const $repeater_item = $(this);
        if (typeof $repeater_item.trigger === 'function') {
          $repeater_item.trigger(change_events[0]);
        }
      })
    }

    place_data_from_db();
    reset_watchers();
    triggerAllRepeaterInputs(); // See function comment
  })
}(jQuery));
