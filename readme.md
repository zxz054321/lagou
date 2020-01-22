## Laravel Lagou

注意事项：

- 本项目仅用于研究爬虫技术，严禁用作其它用途！如违反法律，后果自负！
- 如需使用后端爬虫，必须启用 `exec` 函数



## 部署脚本

项目根目录下的 deploy.sh 用于在 [OneinStack](https://oneinstack.com/) 环境下进行代码一键更新、部署，适用于生产环境



## 基于 Chrome 浏览器爬取

首先配置好 ENV：

```
MIX_APP_URL=https://域名结尾无斜杠
```

然后安装 Laravel Mix 相关依赖，编译浏览器爬虫脚本：

```bash
npm install
npm run production
```

在 Chrome 浏览器中打开 [拉勾首页](https://www.lagou.com) 之后，F12 打开 DevTools 执行以下 JavaScript 代码，注入爬虫脚本：

```javascript
var script = document.createElement('script');
script.src = "https://域名/js/spider.js";
document.getElementsByTagName('head')[0].appendChild(script);
```

JavaScript 文件加载完后，爬虫会自动对白名单中的所有页面进行轮流爬取。

爬虫在每个页面的运行流程：

1. 首先跳转最后一页
2. 从最后一页开始往第一页爬
3. 爬完第一页后停止

爬虫禁忌：

- 请不要调短爬虫休息时间，会导致被封
