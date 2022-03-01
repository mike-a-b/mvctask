(function ($) {
  $(function () {

    var slugify = function (str) {
      str = str.replace(/^\s+|\s+$/g, ''); // trim
      str = str.toLowerCase();

      // remove accents, swap ñ for n, etc
      var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
      var to = "aaaaeeeeiiiioooouuuunc------";

      for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
      }

      str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

      return str;

    };

    var json_to_input = function (data) {
      return encodeURIComponent(JSON.stringify(data))
    };

    var input_to_json = function (data) {
      return JSON.parse(decodeURIComponent(data));
    };

    window.exlog = window.exlog || {};
    window.exlog.slugify = window.exlog.slugify || slugify;
    window.exlog.json_to_input = window.exlog.json_to_input || json_to_input;
    window.exlog.input_to_json = window.exlog.input_to_json || input_to_json;

  })
}(jQuery));
