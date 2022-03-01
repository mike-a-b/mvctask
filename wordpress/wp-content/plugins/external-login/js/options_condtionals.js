(function ($) {
    $(function () {
        var err_start = "\n\nExternal Login ERROR:\n";

        function runConditionalChecks() {
            var conditionals_data_name = "data-exlog-conditionals";
            var $option_field_containers = $(".option-container");

            $option_field_containers.each(function () {
                var $this = $(this);
                var data = $this.attr(conditionals_data_name);
                if (data && data !== 'null') {
                    var json_data = false;
                    try {
                        json_data = JSON.parse(data);
                    } catch (error) {
                        console.log(err_start, "Invalid JSON in conditional string. Data:\n", data);
                        return;
                    }

                    if (json_data) {
                        var result = evaluateConditions(json_data);
                        if (result !== null) {
                            showOrHideField($this, result);
                        } else {
                            console.log(err_start, "Error validating conditions. Data:\n", data);
                        }
                    } else {
                        console.log(err_start, "Invalid condition structure. Data:\n", data);
                    }
                }
            });
        }

        function showOrHideField($selector, show) {
            if (show) {
                $selector.show();
            } else {
                $selector.hide();
            }
        }

        function evaluateConditions(raw_conditions) {
            var condition_results = [];
            var condition_result;
            var condition_data;
            var value;
            var conditions_prepared = prepareConditionsData(raw_conditions);
            var andOr = conditions_prepared['andOr'];
            var conditions = conditions_prepared['conditions'];

            for (var i = 0; i < conditions.length; i++) {
                condition_data = conditions[i];
                value = getFieldValue(condition_data["condition_field"]);
                condition_result = evaluateCondition(
                    value,
                    condition_data["condition_field_value"],
                    condition_data["condition_operator"]
                );
                condition_results.push(condition_result);
            }

            if (andOr === "and") {
                //Check if all conditions are true
                return condition_results.indexOf(false) === -1;
            } else if (andOr === "or") {
                //Check if one condition is true
                return condition_results.indexOf(true) === -1;
            } else {
                console.log(err_start, "And or Condition not correctly set. Data:\n", raw_conditions);
                return null;
            }
        }

        function getFieldValue(fieldID) {
            var $field = $('#' + fieldID);
            if ($field.length > 0) {
                if ($field[0].type === 'checkbox') {
                    return $field[0].checked ? "true" : "false";
                } else {
                    return $field.val();
                }
            } else {
                return "";
            }
        }

        function evaluateCondition(value, checkValue, operator) {
            switch(operator) {
                case "=":
                case "==":
                case "===":
                    return value === checkValue;
                    break;
                case "!=":
                case "!==":
                    return value !== checkValue;
                    break;
            }

        }

        function prepareConditionsData(raw_conditions) {
            var conditions_prepared = {};
            if (typeof raw_conditions[0] === 'string') {
                switch(raw_conditions[0]) {
                    case "and":
                    case "&":
                    case "&&":
                        conditions_prepared['andOr'] = "and";
                        break;
                    case "or":
                    case "|":
                    case "||":
                        conditions_prepared['andOr'] = "or";
                        break;
                    default:
                        console.log(err_start, "Invalid condition data passed for options page. Data:\n", raw_conditions);
                        return false;
                }

                raw_conditions.shift();

                } else {
                conditions_prepared['andOr'] = "and"
            }
            conditions_prepared['conditions'] = raw_conditions;
            return conditions_prepared;
        }

        runConditionalChecks();

        $('select, input').change(runConditionalChecks);
    })
}(jQuery));
