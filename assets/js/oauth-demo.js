function readUrlHashParams() {
    let result = {};
    let hashParam = window.location.hash.substring(1);
    let params = hashParam.split("&");

    $.each(params, function (index, set) {
        let paramSet = set.split("=");
        if (typeof (paramSet[1]) !== "undefined") {
            result[paramSet[0]] = decodeURIComponent(paramSet[1]);
        } else {
            result[paramSet[0]] = "";
        }
    });
    return result;
}
/*
<li class="list-group-item">
                                                <strong>{{ k }}</strong>
                                                <small class="text-muted float-right">
                                                    {% if k == 'error_uri' %}
                                                        <a href="{{ v }}" target="_blank">{{ v }}</a>
                                                    {% else %}
                                                        {{ v }}
                                                    {% endif %}
                                                </small>
                                            </li>
 */
$(function () {
    let $container = $('#authorization-response').find('.list-group');
    if($container.length === 0) {
        return;
    }

    let result = readUrlHashParams();
    for(let i in result) {
        if(!result.hasOwnProperty(i)) {
            continue;
        }

        let $value = $('<small class="text-muted float-right">');
        if(i === 'error_uri') {
            $value.append($('<a target="_blank"></a>').text(result[i]).attr('href', result[i]))
        }
        else {
            $value.text(result[i])
        }
        $container.append($('<li class="list-group-item"><strong>'+i+'</strong></small></li>').append($value));
    }
});
