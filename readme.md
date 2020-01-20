## Laravel Lagou

注意事项：

- 本项目仅用于研究爬虫技术，严禁用作其它用途！如违反法律，后果自负！
- 如需使用后端爬虫，必须启用 `exec` 函数



## 基于 Chrome 浏览器爬取

首先安装 Laravel Mix 相关依赖，编译浏览器爬虫脚本：

```bash
npm install
npm run production
```

通过 https://lagou.air-soft.cn/api/lagou-job-pages 接口可获得已处理好的拉勾职位目录页 url，在 Chrome 浏览器中跳转到相应页面之后，打开 DevTools 执行以下 JavaScript 代码，以注入爬虫代码：

```javascript
var script = document.createElement('script');
script.src = "https://lagou.air-soft.cn/js/chrome.js";
document.getElementsByTagName('head')[0].appendChild(script);
```

js 文件加载完后，爬虫会自动运行。爬虫运行流程：

1. 首先跳转最后一页
2. 从最后一页开始往第一页爬
3. 爬完第一页后停止

爬虫禁忌：

- 请不要调短爬虫休息时间，会导致被封



## 部署脚本

项目根目录下的 deploy.sh 用于在 [OneinStack](https://oneinstack.com/) 环境下进行代码一键更新、部署，适用于生产环境