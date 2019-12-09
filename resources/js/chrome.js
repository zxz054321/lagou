window.ajaxHook = require('ajax-hook');

/**
 * 模拟鼠标点击 ================================================================================
 */
function simulate(element, eventName) {
    var options = extend(defaultOptions, arguments[2] || {});
    var oEvent, eventType = null;

    for (var name in eventMatchers) {
        if (eventMatchers[name].test(eventName)) {
            eventType = name;
            break;
        }
    }

    if (!eventType)
        throw new SyntaxError('Only HTMLEvents and MouseEvents interfaces are supported');

    if (document.createEvent) {
        oEvent = document.createEvent(eventType);
        if (eventType == 'HTMLEvents') {
            oEvent.initEvent(eventName, options.bubbles, options.cancelable);
        } else {
            oEvent.initMouseEvent(eventName, options.bubbles, options.cancelable, document.defaultView,
                options.button, options.pointerX, options.pointerY, options.pointerX, options.pointerY,
                options.ctrlKey, options.altKey, options.shiftKey, options.metaKey, options.button, element);
        }
        element.dispatchEvent(oEvent);
    } else {
        options.clientX = options.pointerX;
        options.clientY = options.pointerY;
        var evt = document.createEventObject();
        oEvent = extend(evt, options);
        element.fireEvent('on' + eventName, oEvent);
    }
    return element;
}

function extend(destination, source) {
    for (var property in source)
        destination[property] = source[property];
    return destination;
}

var eventMatchers = {
    'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
    'MouseEvents': /^(?:click|dblclick|mouse(?:down|up|over|move|out))$/
};
var defaultOptions = {
    pointerX: 0,
    pointerY: 0,
    button: 0,
    ctrlKey: false,
    altKey: false,
    shiftKey: false,
    metaKey: false,
    bubbles: true,
    cancelable: true
};

/**
 * 拦截XHR响应 ================================================================================
 */
ajaxHook.hookAjax({
    onreadystatechange: function (xhr) {
        if (-1 === xhr.responseURL.indexOf('positionAjax.json'))
            return;

        $.post('{{url(\'api/lagou/pour\')}}', xhr.responseText);
    },
});

/**
 * 开始慢慢爬 ==================================================================================
 */
function randomSleep(fn) {
    let timeout = 5 * 1000 + Math.random() * 20 * 1000;
    console.log(`随机休息${timeout}s`);
    setTimeout(fn, timeout);
}

function crawl() {
    // 到达第一页后停止
    if (1 === $('.pager_is_current').attr('page')) {
        console.log('爬取完成');
        return;
    }

    // 往上一页走
    randomSleep(_ => {
        simulate($('.pager_prev ').last().get(0), 'click');
        crawl();
    });
}

randomSleep(_ => {
    // 先去最后一页
    simulate($('.item_con_pager .pager_not_current').last().get(0), 'click');

    // 开始爬取
    crawl();
});
