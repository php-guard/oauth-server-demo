function readUrlHashParams() {
    let result = {};
    let hashParam = window.location.hash.substring(1);
    let params = hashParam.split("&");

    $.each(params, function (index, set) {
        let paramSet = set.split("=");
        if (typeof (paramSet[1]) !== "undefined") {
            result[paramSet[0]] = decodeURIComponent(paramSet[1].replace(/\+/g, '%20'));
        } else {
            result[paramSet[0]] = "";
        }
    });
    return result;
}

$(function () {
    let $response = $('#authorization-response');
    let $container = $response.find('.list-group');
    let $title = $response.find('.card-header span');
    if ($container.length === 0) {
        return;
    }

    let result = readUrlHashParams();
    window.location.hash = '';
    if (Object.keys(result).length > 0) {
        $title.text($title.text() + ' (from url fragment)');

        for (let i in result) {
            if (!result.hasOwnProperty(i)) {
                continue;
            }

            let $value = $('<small class="text-muted float-right">');
            if (i === 'error_uri') {
                $value.append($('<a target="_blank"></a>').text(result[i]).attr('href', result[i]))
            }
            else {
                $value.text(result[i])
            }
            $container.append($('<li class="list-group-item"><strong>' + i + '</strong></small></li>').append($value));
        }
    }
});
