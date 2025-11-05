# 首页改版计划 - MSC风格复刻

## 一、目标网站分析（MSC.com）

### 1.1 技术架构
- **后端CMS**: Sitecore (企业级.NET CMS)
- **前端框架**: Alpine.js (轻量级响应式框架)
- **架构模式**: Headless/Hybrid (部分解耦，使用RESTful API)
- **图片CDN**: Sitecore Content Hub + assets.msc.com

### 1.2 核心设计特点

#### 导航菜单系统
- **触发方式**: 鼠标悬浮（hover）显示大型下拉菜单
- **布局结构**: 
  - 左侧：主分类标题 + "Back"按钮（移动端）
  - 右侧：每个子分类的卡片（图片 + 标题 + 子分类链接列表）
- **视觉效果**: 
  - 大型下拉面板（全宽或接近全宽）
  - 每个主分类有对应的代表性图片
  - 子分类以垂直列表展示
  - 平滑的显示/隐藏动画

#### 主页布局结构
1. **Hero Banner区域**
   - 全屏视频背景（1920x1080或更大）
   - 中央标题文字
   - 搜索/查询功能（可选）

2. **Our Solutions区块**
   - 区块标题 + 描述文字
   - 多个解决方案卡片（横向滚动或网格布局）
   - 每个卡片包含：图片 + 主标题 + 子分类链接列表
   - "See all solutions"链接

3. **其他内容区块**
   - Industries（行业分类）
   - News & Events（新闻事件）
   - Statistics（统计数据）

---

## 二、当前项目现状分析

### 2.1 项目结构
- **CMS**: WordPress 6.x
- **主题**: angola-b2b (自定义主题)
- **插件**: ACF Free, Polylang Free, Swiper.js
- **多语言**: 4种语言（英语、西班牙语、葡萄牙语、法语）

### 2.2 产品分类体系
- **主要分类（4个）**:
  1. 建筑工程
  2. 建筑材料
  3. 农机农具
  4. 工业设备
- **分类层级**: 父分类 → 子分类（已支持多级层级）
- **分类法**: `product_category` (hierarchical)

### 2.3 当前导航系统
- **文件位置**: `wp-content/themes/angola-b2b/header.php`
- **实现方式**: WordPress标准菜单 (`wp_nav_menu`)
- **CSS文件**: `assets/css/layout.css`
- **下拉菜单**: 基础CSS实现（`:hover`显示）
- **限制**: 
  - 不支持图片展示
  - 下拉菜单样式简单
  - 不支持大型下拉面板

### 2.4 当前首页结构
- **模板文件**: `front-page.php`
- **模块顺序**:
  1. Banner轮播 (`banner-slider.php`)
  2. 库存产品 (`stock-products.php`)
  3. 精选产品 (`featured-products.php`)
  4. 核心优势 (`advantages.php`)
  5. CTA联系 (`cta-section.php`)

---

## 三、改版需求清单

### 3.1 导航菜单改版（优先级：最高）

#### 3.1.1 功能需求
- [ ] 鼠标悬浮主分类时，显示大型下拉菜单
- [ ] 下拉菜单包含：主分类图片 + 子分类列表
- [ ] 支持4个主分类：建筑工程、建筑材料、农机农具、工业设备
- [ ] 每个主分类显示其所有子分类
- [ ] 子分类可点击跳转到分类归档页
- [ ] 移动端：点击显示/隐藏下拉菜单

#### 3.1.2 视觉需求
- [ ] 下拉菜单宽度：接近全屏宽度（max-width: 1200px，居中）
- [ ] 每个主分类需要代表性图片（从MSC网站获取占位图）
- [ ] 平滑的显示/隐藏动画（CSS transition）
- [ ] 鼠标离开后延迟关闭（避免误触）
- [ ] 当前页面的分类高亮显示

#### 3.1.3 技术实现
- **HTML结构**: 修改 `header.php` 中的导航菜单HTML
- **CSS样式**: 新增 `assets/css/navigation-mega-menu.css`
- **JavaScript**: 新增 `assets/js/mega-menu.js`（处理hover逻辑和移动端交互）
- **ACF字段**: 为每个主分类添加"导航图片"字段（可选，暂时使用占位图）

### 3.2 首页Hero区域改版

#### 3.2.1 功能需求
- [ ] 全屏背景图片（使用MSC网站的占位图）
- [ ] 中央标题文字（可配置，通过ACF）
- [ ] 保持当前Banner轮播功能（可切换显示模式）

#### 3.2.2 视觉需求
- [ ] 高度：视口高度的60-80%（min-height: 60vh）
- [ ] 背景图片：覆盖整个区域，`object-fit: cover`
- [ ] 文字叠加：半透明遮罩层，确保文字可读性
- [ ] 响应式：移动端调整为较小高度

#### 3.2.3 技术实现
- **模板文件**: 修改 `template-parts/homepage/banner-slider.php`
- **CSS样式**: 修改 `assets/css/homepage.css`
- **ACF字段**: 在"首页设置"（页面ID: 45）添加：
  - `hero_background_image` (Image Field)
  - `hero_title` (Text Field)
  - `hero_subtitle` (Textarea Field)

### 3.3 首页"产品分类展示"区块（新增）

#### 3.3.1 功能需求
- [ ] 展示4个主分类
- [ ] 每个分类卡片包含：图片 + 标题 + 子分类列表 + "查看全部"链接
- [ ] 点击子分类跳转到分类归档页
- [ ] 点击主分类标题跳转到主分类归档页

#### 3.3.2 视觉需求
- [ ] 4列网格布局（桌面端），2列（平板），1列（移动端）
- [ ] 每个卡片：图片在上，标题居中，子分类列表在下
- [ ] 卡片悬停效果：轻微阴影和缩放
- [ ] 使用MSC网站的占位图片

#### 3.3.3 技术实现
- **模板文件**: 新增 `template-parts/homepage/category-showcase.php`
- **CSS样式**: 新增 `assets/css/category-showcase.css`
- **调用位置**: 在 `front-page.php` 中，Banner之后插入

---

## 四、技术实现方案

### 4.1 导航菜单改版实现

#### 4.1.1 HTML结构修改
**文件**: `wp-content/themes/angola-b2b/header.php`

**新结构**:
```php
<nav class="main-navigation mega-menu-nav">
    <ul class="menu-primary">
        <?php
        // 获取4个主分类
        $main_categories = get_terms(array(
            'taxonomy' => 'product_category',
            'parent' => 0,
            'hide_empty' => false,
        ));
        
        foreach ($main_categories as $category) :
            // 获取子分类
            $subcategories = get_terms(array(
                'taxonomy' => 'product_category',
                'parent' => $category->term_id,
                'hide_empty' => false,
            ));
            
            // 获取分类图片（ACF字段或占位图）
            $category_image = get_field('category_nav_image', 'product_category_' . $category->term_id);
            if (!$category_image) {
                // 使用MSC占位图（临时）
                $category_image = 'https://www.msc.com/-/media/images/msc-cargo/...';
            }
        ?>
        <li class="menu-item menu-item-has-children">
            <a href="<?php echo get_term_link($category); ?>">
                <?php echo esc_html($category->name); ?>
            </a>
            <div class="mega-menu-dropdown">
                <div class="mega-menu-content">
                    <div class="mega-menu-image">
                        <img src="<?php echo esc_url($category_image); ?>" 
                             alt="<?php echo esc_attr($category->name); ?>">
                    </div>
                    <div class="mega-menu-links">
                        <h3><?php echo esc_html($category->name); ?></h3>
                        <ul class="subcategory-list">
                            <?php foreach ($subcategories as $subcat) : ?>
                            <li>
                                <a href="<?php echo get_term_link($subcat); ?>">
                                    <?php echo esc_html($subcat->name); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?php echo get_term_link($category); ?>" class="view-all-link">
                            <?php esc_html_e('View All', 'angola-b2b'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
```

#### 4.1.2 CSS样式实现
**文件**: `wp-content/themes/angola-b2b/assets/css/navigation-mega-menu.css`

**关键样式**:
- `.mega-menu-dropdown`: 绝对定位，全宽下拉面板
- `.mega-menu-content`: Flexbox布局，图片+链接区域
- `.mega-menu-image`: 图片容器，固定宽度
- `.mega-menu-links`: 链接区域，flex: 1
- 显示/隐藏：`opacity` + `transform` + `visibility`
- 延迟关闭：JavaScript控制

#### 4.1.3 JavaScript实现
**文件**: `wp-content/themes/angola-b2b/assets/js/mega-menu.js`

**功能**:
- 鼠标进入主分类：显示下拉菜单
- 鼠标离开：延迟300ms后隐藏（如果鼠标不在下拉菜单内）
- 鼠标进入下拉菜单：保持显示
- 移动端：点击切换显示/隐藏

### 4.2 占位图片使用方案

#### 4.2.1 MSC网站图片URL（临时占位）
从MSC网站网络请求中提取的图片URL：

1. **建筑工程**（对应Shipping Solutions）:
   - URL: `https://assets.msc.com/api/public/content/f1a1f33daaf248cebfe4c54aa9ba5fc3?v=af337467?w=600`
   - 或: `https://www.msc.com/-/media/images/msc-cargo/services/...`

2. **建筑材料**（对应Inland Transportation）:
   - URL: `https://msc-p-001.sitecorecontenthub.cloud/api/public/content/c8b90d03c3394471bf7f9639968f0cab?v=0f1e5582?w=600`

3. **农机农具**（对应Agriculture）:
   - 使用MSC Agriculture相关图片

4. **工业设备**（对应Digital Business Solutions）:
   - URL: `https://www.msc.com/-/media/images/msc-cargo/services/digital-solutions/digital-solution-538-x277.jpg?rev=71fa1fe412bd4afca937d650a90eb081?w=600`

#### 4.2.2 图片尺寸要求
- **导航菜单图片**: 400x300px (宽高比 4:3)
- **分类展示卡片图片**: 600x400px (宽高比 3:2)
- **Hero背景图片**: 1920x1080px (宽高比 16:9)

#### 4.2.3 图片加载策略
- 使用`loading="lazy"`（导航菜单图片除外，需立即加载）
- 提供占位符（placeholder）避免布局闪烁
- 图片加载失败时显示默认占位图

### 4.3 ACF字段配置

#### 4.3.1 分类导航图片字段
**字段组**: "Product Category Settings"
**字段名**: `category_nav_image`
**字段类型**: Image
**位置规则**: Taxonomy Term = product_category
**说明**: 用于导航菜单和分类展示的图片

#### 4.3.2 首页Hero设置字段
**字段组**: "Homepage Settings" (已存在，页面ID: 45)
**新增字段**:
- `hero_background_image` (Image) - Hero背景图片
- `hero_title` (Text) - Hero标题
- `hero_subtitle` (Textarea) - Hero副标题

---

## 五、文件修改清单

### 5.1 需要修改的文件

1. **`wp-content/themes/angola-b2b/header.php`**
   - 修改导航菜单HTML结构
   - 添加mega menu的HTML

2. **`wp-content/themes/angola-b2b/front-page.php`**
   - 在Banner之后插入分类展示区块

3. **`wp-content/themes/angola-b2b/template-parts/homepage/banner-slider.php`**
   - 修改为支持Hero全屏背景模式
   - 保留轮播功能作为备选

### 5.2 需要新增的文件

1. **`wp-content/themes/angola-b2b/assets/css/navigation-mega-menu.css`**
   - Mega menu样式

2. **`wp-content/themes/angola-b2b/assets/css/category-showcase.css`**
   - 分类展示区块样式

3. **`wp-content/themes/angola-b2b/assets/js/mega-menu.js`**
   - Mega menu交互逻辑

4. **`wp-content/themes/angola-b2b/template-parts/homepage/category-showcase.php`**
   - 分类展示区块模板

### 5.3 需要修改的CSS文件

1. **`wp-content/themes/angola-b2b/assets/css/layout.css`**
   - 更新导航菜单基础样式
   - 移除旧的简单下拉菜单样式

2. **`wp-content/themes/angola-b2b/assets/css/homepage.css`**
   - 更新Hero/Banner区域样式

3. **`wp-content/themes/angola-b2b/assets/css/responsive.css`**
   - 添加mega menu的响应式样式

### 5.4 需要修改的PHP文件

1. **`wp-content/themes/angola-b2b/inc/enqueue-scripts.php`**
   - 注册新的CSS和JS文件

2. **`wp-content/themes/angola-b2b/inc/acf-fields.php`**
   - 添加分类导航图片字段（可选）

---

## 六、开发步骤

### 阶段1：导航菜单改版（优先级最高）

#### Step 1.1: 准备HTML结构
1. 修改 `header.php`，添加mega menu HTML结构
2. 使用WordPress函数获取主分类和子分类
3. 添加占位图片URL（临时使用MSC图片）

#### Step 1.2: 实现CSS样式
1. 创建 `navigation-mega-menu.css`
2. 实现下拉面板布局（Flexbox）
3. 实现显示/隐藏动画
4. 实现响应式样式（移动端）

#### Step 1.3: 实现JavaScript交互
1. 创建 `mega-menu.js`
2. 实现hover显示逻辑
3. 实现延迟关闭逻辑
4. 实现移动端点击切换

#### Step 1.4: 测试和优化
1. 测试4个主分类的下拉菜单
2. 测试移动端交互
3. 优化动画性能
4. 检查多语言支持

### 阶段2：首页Hero区域改版

#### Step 2.1: 修改Banner模板
1. 修改 `banner-slider.php`支持Hero模式
2. 添加ACF字段读取逻辑
3. 使用MSC占位图片

#### Step 2.2: 更新CSS样式
1. 修改 `homepage.css`
2. 实现全屏背景
3. 实现文字叠加效果
4. 实现响应式调整

### 阶段3：分类展示区块（新增）

#### Step 3.1: 创建模板文件
1. 创建 `category-showcase.php`
2. 实现4个主分类的展示逻辑
3. 使用MSC占位图片

#### Step 3.2: 实现CSS样式
1. 创建 `category-showcase.css`
2. 实现网格布局
3. 实现卡片样式和悬停效果

#### Step 3.3: 集成到首页
1. 在 `front-page.php`中插入新模块
2. 调整模块顺序

### 阶段4：优化和收尾

#### Step 4.1: 代码优化
1. 检查所有文件的无错误、无警告
2. 优化CSS性能（合并、压缩）
3. 优化JavaScript性能
4. 添加代码注释

#### Step 4.2: 多语言支持
1. 检查所有硬编码文本
2. 使用 `pll__()` 或 `esc_html_e()` 进行翻译
3. 测试4种语言的显示

#### Step 4.3: 响应式测试
1. 桌面端（1920px+）
2. 平板端（768px - 1024px）
3. 移动端（< 768px）
4. 各种浏览器兼容性测试

---

## 七、占位图片详细清单

### 7.1 导航菜单图片（4张）

| 分类 | 占位图URL | 尺寸 | 用途 |
|------|----------|------|------|
| 建筑工程 | `https://assets.msc.com/api/public/content/f1a1f33daaf248cebfe4c54aa9ba5fc3?v=af337467?w=400` | 400x300 | 导航下拉菜单 |
| 建筑材料 | `https://msc-p-001.sitecorecontenthub.cloud/api/public/content/c8b90d03c3394471bf7f9639968f0cab?v=0f1e5582?w=400` | 400x300 | 导航下拉菜单 |
| 农机农具 | `https://www.msc.com/-/media/images/msc-cargo/services/trades---dark-images/msc-vessel-connecting-europe-to-north-america.jpg?rev=9d6fb135611347948ce2d7e1b212a928?w=400` | 400x300 | 导航下拉菜单 |
| 工业设备 | `https://www.msc.com/-/media/images/msc-cargo/services/digital-solutions/digital-solution-538-x277.jpg?rev=71fa1fe412bd4afca937d650a90eb081?w=400` | 400x300 | 导航下拉菜单 |

### 7.2 分类展示卡片图片（4张）

使用与导航菜单相同的图片，但尺寸调整为600x400。

### 7.3 Hero背景图片（1张）

使用MSC主页的Hero背景图：
- URL: `https://www.msc.com/-/media/videos/msc-cargo/home-and-general-pages/parallax-effects/2025/landingpage-banner-animation-1920x1105.mp4?rev=d5252ee9e1724422bc4897adff72035c`
- 注意：这是视频文件，如果使用静态图片，可从MSC网站提取静态背景图
- 备用：使用MSC Container Vessel相关图片

### 7.4 图片使用说明

1. **临时性**: 这些图片仅用于开发期间的占位，最终需要用户上传正式图片
2. **CDN**: MSC图片来自其CDN，可能无法长期稳定访问
3. **版权**: 注意MSC图片的版权，仅用于开发测试
4. **替换**: 开发完成后，通过WordPress后台上传正式图片替换

---

## 八、注意事项

### 8.1 技术注意事项

1. **WordPress兼容性**: 确保所有代码符合WordPress编码标准
2. **性能优化**: 
   - 图片懒加载
   - CSS/JS文件合并和压缩
   - 避免重复查询数据库
3. **安全性**: 
   - 所有输出使用 `esc_html()`, `esc_url()`, `esc_attr()`
   - 防止XSS攻击
   - 验证用户输入
4. **可访问性**: 
   - 语义化HTML
   - ARIA标签
   - 键盘导航支持

### 8.2 多语言注意事项

1. **文本翻译**: 所有UI文本必须支持4种语言
2. **图片替换**: 如果不同语言需要不同图片，需要ACF字段支持
3. **URL结构**: 确保分类链接支持Polylang的URL结构

### 8.3 响应式注意事项

1. **移动端导航**: Mega menu在移动端改为抽屉式菜单
2. **图片尺寸**: 移动端使用较小尺寸的图片
3. **触摸交互**: 确保触摸设备上的交互流畅

---

## 九、测试清单

### 9.1 功能测试
- [ ] 导航菜单hover显示/隐藏正常
- [ ] 4个主分类的下拉菜单都正常显示
- [ ] 子分类链接跳转正确
- [ ] 移动端导航菜单交互正常
- [ ] Hero区域背景图片显示正常
- [ ] 分类展示区块显示正常
- [ ] 所有链接跳转正确

### 9.2 视觉测试
- [ ] 桌面端布局正确
- [ ] 平板端布局正确
- [ ] 移动端布局正确
- [ ] 动画效果流畅
- [ ] 图片显示清晰
- [ ] 文字可读性良好

### 9.3 性能测试
- [ ] 页面加载速度 < 3秒
- [ ] 图片加载不阻塞页面渲染
- [ ] JavaScript执行不阻塞页面
- [ ] CSS文件大小合理

### 9.4 浏览器兼容性测试
- [ ] Chrome (最新版)
- [ ] Firefox (最新版)
- [ ] Safari (最新版)
- [ ] Edge (最新版)
- [ ] 移动端浏览器

### 9.5 多语言测试
- [ ] 英语版本正常
- [ ] 西班牙语版本正常
- [ ] 葡萄牙语版本正常
- [ ] 法语版本正常

---

## 十、后续优化建议

### 10.1 功能增强
1. **搜索功能**: 在导航菜单中添加搜索框
2. **面包屑导航**: 添加面包屑导航支持
3. **分类图标**: 为每个分类添加图标（除了图片）

### 10.2 性能优化
1. **图片CDN**: 考虑使用WordPress图片CDN插件
2. **缓存**: 实施页面缓存和对象缓存
3. **Lazy Loading**: 优化图片懒加载策略

### 10.3 用户体验优化
1. **动画细节**: 优化动画的缓动函数
2. **加载状态**: 添加图片加载的占位符动画
3. **错误处理**: 图片加载失败时的优雅降级

---

## 十一、开发时间估算

- **阶段1（导航菜单）**: 4-6小时
- **阶段2（Hero区域）**: 2-3小时
- **阶段3（分类展示）**: 3-4小时
- **阶段4（优化收尾）**: 2-3小时
- **总计**: 11-16小时

---

## 十二、参考资料

1. **MSC网站**: https://www.msc.com/
2. **WordPress菜单文档**: https://developer.wordpress.org/reference/functions/wp_nav_menu/
3. **ACF文档**: https://www.advancedcustomfields.com/resources/
4. **Swiper.js文档**: https://swiperjs.com/get-started

---

**文档创建时间**: 2025-01-09
**最后更新**: 2025-01-09
**版本**: 1.0

