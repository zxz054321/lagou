function sleep(time) {
    return new Promise(resolve => setTimeout(resolve, time));
}

function work(queue) {
    if (0 === queue.length) {
        console.log('队列已清空');
        return;
    }

    sleep(1000).then(_ => {
        const url = queue.shift();

        console.log('正在打开 ' + url);
        subWindow.location.href = url;

        sleep(3000).then(_ => {
            console.log('注入脚本');

            let script = subWindow.document.createElement('script');
            script.src = 'https://lagou.test/js/chrome.js';
            subWindow.document.getElementsByTagName('head')[0].appendChild(script);

            watcher = setInterval(_ => {
                console.log(subWindow.document.title);

                if ('爬取完成。' === subWindow.document.title) {
                    clearInterval(watcher);
                    work(queue);
                }
            }, 1000);
        });
    });
}

let watcher;
let subWindow = window.open('https://www.lagou.com', '_blank', '');

$.get('https://lagou.test/api/lagou-job-pages', data => work(data));
