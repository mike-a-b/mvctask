(function ($) {
    $(function () {
        var $modal = $(".exlog_modal");
        var $loader = $(".exlog_loader_container");
        var $modal_content_container = $(".exlog_test_results_inner_container", $modal);
        var $modal_test_results = $(".exlog_test_results", $modal);
        var $modal_error = $(".exlog_test_fail", $modal_content_container);
        var $modal_error_title = $(".exlog-error-title", $modal_error);
        var $modal_error_message = $(".exlog-error-message", $modal_error);
        var wordpressBaseUrl = $('[data-exlog-wp-base]').attr('data-exlog-wp-base');

        var error_messages = {
            unknown: {
                title: "Error",
                message: "This is an unknown error."
            },
            lost: {
              title: "Error",
              message: "Could not access the server to run the test."
            },
            server: {
                title: "Error",
                message: "There was an error on the server."
            },
            empty_result: {
              title: "Error",
              message: "No data returned from the server. Please check your settings."
            }
        };

        var error_codes = {
          100: error_messages.unknown,       // Ajax returned, unknown error
          101: error_messages.unknown,       // Unknown error passed to error handler
          404: error_messages.lost,          // 404 from Ajax
          500: error_messages.server,        // 500 from server - see test_results.php?
          501: error_messages.server,        // String of "0" returned - caused by missing function?
          502: error_messages.server,        // 500ish error from Ajax
          600: error_messages.empty_result,  // Empty AJAX response
          601: error_messages.empty_result   // Blank string AJAX response
//        999: ????????????????????????      // Hard coded in markup case this system fails
        };


        function errorMessageState(error_code) {
          if (!error_code) {
            $modal_error.hide();
          }

          if (!error_codes.hasOwnProperty(error_code)) {
            error_code = 101;
          }

          var error_data = error_codes[error_code];

          $modal_error_title.text(error_data.title + ": " + error_code);
          $modal_error_message.text(error_data.message);

          $modal_error.show();
        }

        $(".exlog_close_button", $modal).click(function () {
            $modal_error.hide();
            $modal.hide();
            $modal_test_results.text("");
        });

        $("input.exlog_test_connection").click(function () {
            $modal.show();
            $loader.show();
            var data = {
                'action': 'exlog_test_connection',
                'test_results': 10
            };

            $.ajax({
                type: "GET",
                url: wordpressBaseUrl + "/wp-admin/admin-ajax.php",
                data: data,
                success: function (data) {
                    if (!data) {
                      errorMessageState(600);
                      $modal_error.show();
                    } else if (data == "") {
                      errorMessageState(601);
                      $modal_error.show();
                    } else if (data === "0") {
                      errorMessageState(501);
                    } else {
                      $modal_error.hide();
                      $modal_test_results.append(data);
                    }
                    $loader.hide();
                },
                error: function (xhr, ajaxOptions, thrownError){
                  if(xhr.status == 404) {
                    errorMessageState(404);
                  } else if (xhr.status === 500) {
                    errorMessageState(500);
                  } else if (Math.floor(xhr.status / 100) === 5) {
                    errorMessageState(502);
                  } else {
                    errorMessageState(100);
                  }
                  $loader.hide();
                }
            });
        });
    });
}(jQuery));
