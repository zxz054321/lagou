window.ajaxHook = require('ajax-hook');

/**
 * 模拟鼠标点击 ================================================================================
 */
function simulate(element, eventName) {
    function extend(destination, source) {
        for (var property in source)
            destination[property] = source[property];
        return destination;
    }

    const eventMatchers = {
        'HTMLEvents': /^(?:load|unload|abort|error|select|change|submit|reset|focus|blur|resize|scroll)$/,
        'MouseEvents': /^(?:click|dblclick|mouse(?:down|up|over|move|out))$/
    };

    let oEvent, eventType = null;
    let options = extend({
        pointerX: 0,
        pointerY: 0,
        button: 0,
        ctrlKey: false,
        altKey: false,
        shiftKey: false,
        metaKey: false,
        bubbles: true,
        cancelable: true
    }, arguments[2] || {});

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

/**
 * 拦截XHR响应 ================================================================================
 */
ajaxHook.hookAjax({
    onreadystatechange: function (xhr) {
        if (-1 === xhr.responseURL.indexOf('positionAjax.json'))
            return;

        $.post(process.env.MIX_APP_URL + '/api/pour', xhr.responseText);
    },
});

/**
 * 开始慢慢爬 ==================================================================================
 */
function randomSleep(fn) {
    const timeout = 5 * 1000 + Math.random() * 20 * 1000;
    console.log(`随机休息${timeout}s`);
    setTimeout(fn, timeout);
}

function crawl() {
    const currentPage = $('.pager_is_current').attr('page');

    if (1 == currentPage) {
        document.title = '爬取完成。';
        console.log('已到达第一页，爬取完成');
        return;
    }

    if (undefined === currentPage) {
        document.title = '爬取完成。';
        console.log('此职位只有一页，爬取完成');
        return;
    }

    randomSleep(_ => {
        console.log('往上一页走');
        simulate($('.pager_prev ').last().get(0), 'click');
        crawl();
    });
}

randomSleep(_ => {
    document.title = '正在爬取……';

    const lastPage = $('.item_con_pager .pager_not_current').last().get(0);

    if (lastPage) {
        console.log('先去最后一页');
        simulate(lastPage, 'click');
    } else {
        console.log('此职位只有一页');
        $.post('https://www.lagou.com/jobs/positionAjax.json?' + window.global.queryParamStr, {
            first: true,
            pn: 1,
            kd: window.global.keyword,
        });
    }

    console.log('开始爬取');
    crawl();
});
