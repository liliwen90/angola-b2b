# 1688商品搜索集成方案

> **创建日期**: 2025-10-31  
> **状态**: 方案确定，待开发  
> **优先级**: ⭐⭐⭐⭐⭐

---

## 📋 项目背景

### 业务需求
**客户**: 安哥拉贸易公司  
**主要业务**: 从中国大陆进口各类商品到安哥拉销售（批发+零售）  
**服务特点**: Door-to-door shipment，客户需要什么就进口什么

### 核心痛点
- 产品种类极多，无法全部录入WordPress
- 客户需要**实时**看到可购买的商品，而非仅提交询价
- 需要展示中国供应商的真实商品信息

### 解决思路
在现有WordPress主题基础上，添加**实时搜索1688商品**的功能

---

## 🎯 方案对比与选择

### 方案1: 官方API集成 ⭐⭐⭐⭐⭐
**技术方案**: 
- 1688.com开放平台API
- Alibaba.com官方API

**优势**:
- ✅ 合法合规
- ✅ 数据稳定
- ✅ 无封禁风险

**劣势**:
- ❌ 需要企业资质
- ❌ 审核周期长（2-4周）
- ❌ 可能需要年费
- ❌ API功能受限

**结论**: 最优方案，但短期无法实现

---

### 方案2: 爬虫实时抓取 ⭐⭐⭐ **【已选择】**
**技术方案**:
- 用户搜索时，后台实时去1688搜索
- 抓取前10个商品的：标题、图片、价格
- 在您的网站展示（不缓存，实时抓）

**优势**:
- ✅ 商品数据自动更新
- ✅ 开发成本不高（3-5天）

**劣势**:
- ⚠️ 违反1688服务条款（但很难被发现）
- ⚠️ IP可能被封（可用代理IP池）
- ⚠️ 1688改版需要调整代码

**用户决策**: 愿意承担风险，选择此方案

---

### 方案3: 混合模式 ⭐⭐⭐⭐
**技术方案**:
- 热门产品 → 人工录入WordPress
- 长尾产品 → 实时爬虫搜索

**优势**:
- ✅ 常见商品加载快
- ✅ 减少爬虫请求
- ✅ 降低封禁风险

**劣势**:
- ⚠️ 需要人工维护
- ⚠️ 开发复杂度增加

**结论**: 可作为方案2的升级版

---

## 🏗️ 技术架构（分离式）

### 架构设计理念
**用户提出的专业方案**: 将爬虫服务部署在**腾讯云中国服务器**，而非WordPress所在的阿里云海外服务器

### 为什么分离？

| 对比项 | 安哥拉服务器爬虫 | 腾讯云中国服务器爬虫 ✅ |
|--------|-----------------|----------------------|
| **被识别概率** | 🔴 极高（非洲IP） | 🟢 低（中国IP） |
| **访问速度** | 🔴 慢（2-3秒） | 🟢 快（200-500ms） |
| **被封影响** | 🔴 主站受影响 | 🟢 主站安全 |
| **模拟真人** | 🔴 难（无Cookie） | 🟢 易（可存Session） |
| **代理池成本** | 🔴 海外代理贵 | 🟢 国内代理便宜 |

### 数据流程图

```
┌─────────────────────────────────────────────┐
│ 阿里云海外（安哥拉网站 - WordPress）        │
│                                             │
│  用户搜索 "手机壳"                          │
│      ↓                                      │
│  前端JS发送AJAX → backend API               │
│      ↓                                      │
│  PHP调用腾讯云API                           │
│  https://api.yourdomain.com/search          │
└─────────────────┬───────────────────────────┘
                  │ HTTP请求
                  │ {"keyword": "手机壳", "page": 1}
                  ↓
┌─────────────────────────────────────────────┐
│ 腾讯云中国（爬虫服务 - Node.js）            │
│                                             │
│  1. 接收关键词                              │
│  2. 模拟浏览器访问1688                      │
│     - 带Cookie（预热的真实Session）         │
│     - 随机User-Agent                        │
│     - 延迟300-800ms（像人）                 │
│  3. 提取商品数据                            │
│     - 标题                                  │
│     - 主图URL                               │
│     - 价格区间                              │
│     - 店铺名称                              │
│     - 商品链接                              │
│  4. 返回JSON给安哥拉服务器                  │
└─────────────────────────────────────────────┘
```

---

## 💻 开发清单

### A. 安哥拉服务器（WordPress主题） - 3天

**文件结构**:
```
wp-content/themes/angola-b2b/
├── inc/
│   └── scraper-api-client.php      (调用腾讯云API)
├── assets/
│   ├── css/
│   │   └── live-search.css         (搜索界面样式)
│   └── js/
│       └── live-search.js          (前端搜索逻辑)
├── template-parts/
│   └── scraper-results.php         (搜索结果展示)
└── page-templates/
    └── template-live-search.php    (搜索页面模板)
```

**功能点**:
- [ ] API客户端模块（调用腾讯云接口）
- [ ] 前端搜索组件（输入框 + 实时搜索）
- [ ] 结果展示页面（商品卡片布局）
- [ ] Loading/Error状态处理
- [ ] 点击商品 → 自动填充询价表单

**API调用示例**:
```php
function angola_call_scraper_api($keyword, $page = 1) {
    $response = wp_remote_post('https://api.yourdomain.com/api/search', [
        'body' => json_encode([
            'keyword' => $keyword,
            'page' => $page
        ]),
        'headers' => [
            'Content-Type' => 'application/json',
            'X-API-Key' => 'your-secret-key'  // 防止接口被滥用
        ],
        'timeout' => 10
    ]);
    
    if (is_wp_error($response)) {
        return ['error' => '网络错误'];
    }
    
    return json_decode($response['body'], true);
}
```

---

### B. 腾讯云服务器（爬虫服务） - 5天

**技术栈**:
- **Node.js** + Express (API框架)
- **Puppeteer** (无头浏览器，模拟真人)
- **PM2** (进程守护)
- **Redis** (缓存，减少重复请求)

**文件结构**:
```
1688-scraper-service/
├── src/
│   ├── app.js                  (Express主程序)
│   ├── routes/
│   │   └── search.js           (搜索API路由)
│   ├── scrapers/
│   │   └── alibaba1688.js      (1688爬虫核心)
│   ├── utils/
│   │   ├── browser-pool.js     (浏览器池管理)
│   │   ├── user-agent.js       (UA轮换)
│   │   └── delay.js            (随机延迟)
│   └── middlewares/
│       └── auth.js             (API鉴权)
├── config/
│   └── default.json            (配置文件)
├── package.json
└── ecosystem.config.js         (PM2配置)
```

**功能点**:
- [ ] Express API服务（RESTful接口）
- [ ] Puppeteer爬虫脚本（模拟真实浏览器）
- [ ] 1688数据提取逻辑（商品列表解析）
- [ ] 反爬措施
  - User-Agent随机轮换
  - 请求延迟300-800ms
  - Cookie池（预先预热）
  - 代理IP池（可选）
- [ ] 接口鉴权（API Key验证）
- [ ] Redis缓存（同一关键词1小时内不重复爬）
- [ ] 日志记录（监控封禁风险）

**核心爬虫逻辑**:
```javascript
const puppeteer = require('puppeteer');

async function search1688(keyword) {
    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    
    // 设置真实UA
    await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64)...');
    
    // 访问搜索页
    await page.goto(`https://s.1688.com/selloffer/offer_search.htm?keywords=${encodeURIComponent(keyword)}`);
    
    // 等待商品列表加载
    await page.waitForSelector('.m-gallery-product-item-wrap');
    
    // 提取数据
    const products = await page.evaluate(() => {
        const items = document.querySelectorAll('.m-gallery-product-item-wrap');
        return Array.from(items).slice(0, 10).map(item => ({
            title: item.querySelector('.title')?.textContent.trim(),
            image: item.querySelector('img')?.src,
            price: item.querySelector('.price')?.textContent.trim(),
            shop: item.querySelector('.shop-name')?.textContent.trim(),
            url: item.querySelector('a')?.href
        }));
    });
    
    await browser.close();
    return products;
}
```

---

### C. 部署配置 - 1天

**腾讯云服务器要求**:
- **配置**: 至少1核2G内存
- **系统**: Ubuntu 22.04 LTS
- **带宽**: 3M以上
- **域名**: 需要子域名（如 `api.yourdomain.com`）
- **证书**: HTTPS（Let's Encrypt免费证书）

**安装清单**:
```bash
# 1. Node.js 18+
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# 2. PM2
sudo npm install -g pm2

# 3. Redis
sudo apt-get install redis-server

# 4. Nginx (反向代理)
sudo apt-get install nginx

# 5. Certbot (HTTPS)
sudo apt-get install certbot python3-certbot-nginx
```

**Nginx配置**:
```nginx
server {
    listen 80;
    server_name api.yourdomain.com;
    
    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

---

## 🛡️ 反反爬策略

### 基础防护（必须）
1. **User-Agent轮换**: 随机使用Chrome/Firefox/Safari的最新UA
2. **请求延迟**: 每次请求间隔300-800ms随机延迟
3. **Cookie预热**: 首次访问时保存Cookie，后续复用
4. **完整请求头**: 包含Referer、Accept-Language等

### 进阶防护（如果被封）
1. **代理IP池**: 
   - 国内住宅IP代理
   - 成本: ¥100-200/月
   - 推荐: 青果代理、芝麻代理

2. **账号Cookie池**:
   - 注册5-10个1688账号
   - 轮换使用不同账号的Session

3. **请求频率限制**:
   - 同一关键词1小时内只爬一次
   - Redis缓存结果

4. **失败重试机制**:
   - 检测到验证码 → 自动切换IP/账号
   - 3次失败 → 返回"暂时无法搜索"

---

## 💰 成本预估

### 服务器成本
| 项目 | 费用 | 备注 |
|------|------|------|
| 腾讯云轻量服务器 | ¥0 | 用户已有 |
| 阿里云海外服务器 | ¥150-300/月 | 已规划 |
| **基础成本合计** | **¥150-300/月** | |

### 可选增强成本
| 项目 | 费用 | 触发条件 |
|------|------|---------|
| 代理IP池 | ¥100-200/月 | 如果被封 |
| CDN加速 | ¥50-100/月 | 访问量大时 |
| **最高成本** | **¥500/月** | |

---

## 📊 开发时间线

```
┌─ Week 1 ─────────────────────────────────┐
│ Day 1-2: 腾讯云爬虫服务开发              │
│          - Puppeteer基础爬虫            │
│          - 数据提取逻辑                 │
│                                          │
│ Day 3-4: 反爬措施 + API服务             │
│          - UA轮换、延迟、Cookie         │
│          - Express REST API             │
│                                          │
│ Day 5:   独立测试                        │
│          - 测试100个关键词              │
│          - 监控封禁风险                 │
└──────────────────────────────────────────┘

┌─ Week 2 ─────────────────────────────────┐
│ Day 6-7: WordPress集成                   │
│          - API客户端                    │
│          - 前端搜索界面                 │
│                                          │
│ Day 8:   联调测试                        │
│          - WordPress → 腾讯云           │
│          - 完整流程测试                 │
│                                          │
│ Day 9:   部署上线                        │
│          - PM2守护进程                  │
│          - Nginx配置                    │
│          - HTTPS证书                    │
└──────────────────────────────────────────┘
```

**总计**: 9个工作日（约2周）

---

## 🎯 下一步行动

### 立即确认事项

**1. 腾讯云服务器信息**
- [ ] 服务器IP地址
- [ ] SSH登录方式（密码/密钥）
- [ ] 当前操作系统版本
- [ ] 是否已安装Node.js？

**2. 域名配置**
- [ ] 是否有域名？
- [ ] 可以配置子域名吗？（api.yourdomain.com）
- [ ] DNS由谁管理？（腾讯云/阿里云）

**3. 开发顺序确认**
推荐：**先开发爬虫服务（独立测试）→ 确认可行后再集成WordPress**

**理由**：
- ✅ 先验证技术可行性
- ✅ 避免前端工作白做
- ✅ 可以提供独立测试脚本给您运行

---

## 📝 接口文档（预览）

### 搜索接口

**请求**:
```
POST https://api.yourdomain.com/api/search
Content-Type: application/json
X-API-Key: your-secret-key

{
  "keyword": "手机壳",
  "page": 1
}
```

**响应**:
```json
{
  "success": true,
  "keyword": "手机壳",
  "total": 10,
  "data": [
    {
      "title": "苹果14手机壳透明硅胶软壳",
      "image": "https://cbu01.alicdn.com/img/...",
      "price": "¥2.5-5.8",
      "shop": "深圳XX电子",
      "url": "https://detail.1688.com/offer/..."
    }
  ],
  "cached": false,
  "timestamp": 1698765432
}
```

**错误响应**:
```json
{
  "success": false,
  "error": "搜索失败",
  "message": "疑似被1688封禁，请稍后重试",
  "retry_after": 300
}
```

---

## ⚠️ 风险提示与应对

### 风险等级

| 风险 | 概率 | 影响 | 应对方案 |
|------|------|------|---------|
| IP被封 | 🟡 中 | 🟡 中 | 代理IP池 |
| 数据格式变化 | 🟢 低 | 🟡 中 | 监控+快速更新 |
| 法律风险 | 🟡 中 | 🔴 高 | 仅用于展示，不存储 |
| 性能问题 | 🟢 低 | 🟢 低 | Redis缓存 |

### 应急预案

**Plan A: 正常运行**
- 爬虫成功率 > 95%
- 响应时间 < 2秒

**Plan B: 部分封禁**
- 启用代理IP池
- 降低请求频率
- 增加缓存时间（1小时 → 6小时）

**Plan C: 完全封禁**
- 临时关闭实时搜索功能
- 引导用户填写询价表单
- 申请1688官方API（长期方案）

---

## 📧 项目联系

**开发者**: AI Assistant  
**用户**: 安哥拉网站项目负责人  
**文档版本**: v1.0  
**最后更新**: 2025-10-31

---

## 附录：参考资源

### 技术文档
- [Puppeteer官方文档](https://pptr.dev/)
- [1688开放平台](https://open.1688.com/)
- [WordPress REST API](https://developer.wordpress.org/rest-api/)

### 反爬虫研究
- [Puppeteer反检测技巧](https://github.com/berstend/puppeteer-extra)
- [User-Agent列表](https://www.useragents.me/)

### 代理服务推荐
- 青果代理: https://www.qg.net/
- 芝麻代理: http://www.zhimaruanjian.com/

---

**状态**: ✅ 方案已确定，等待用户确认开发顺序和服务器信息

