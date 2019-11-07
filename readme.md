## Laravel Lagou

注意事项：

- 本项目仅用于研究爬虫技术，严禁用作其它用途！如违反法律，后果自负！
- 如需使用后端爬虫，必须启用 `exec` 函数



## 基于 Chrome 浏览器爬取

通过 https://lagou.air-soft.cn/api/uibot/jobs 接口可获得已处理好的拉勾职位目录页 url，在 Chrome 浏览器中跳转到相应页面之后，打开 DevTools 执行以下 JavaScript 代码，以注入爬虫代码：

```javascript
// 引入 Ajax Hook
var script = document.createElement('script');
script.src = "https://unpkg.com/ajax-hook/dist/ajaxhook.min.js";
document.getElementsByTagName('head')[0].appendChild(script);

// 引入 Chrome 浏览器爬虫
var script = document.createElement('script');
script.src = "https://lagou.air-soft.cn/uibot/chrome.js";
document.getElementsByTagName('head')[0].appendChild(script);
```

在 DevTools 执行以下 JavaScript 代码以运行爬虫：

```javascript
randomSleep(_ => {
    // 先去最后一页
    simulate($(".item_con_pager .pager_not_current").last().get(0), "click");

    // 开始爬取
    crawl();
});
```

爬虫运行流程：

1. 首先跳转最后一页
2. 从最后一页开始往第一页爬
3. 爬完第一页后停止

爬虫禁忌：

- 请不要调短

## 部署脚本

项目根目录下的 deploy.sh 用于在 [OneinStack](https://oneinstack.com/) 环境下进行代码一键更新、部署，适用于生产环境