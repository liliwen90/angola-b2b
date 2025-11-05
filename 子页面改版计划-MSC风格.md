# 子页面改版计划 - MSC风格复刻

## 一、目标网站子页面分析（MSC.com）

### 1.1 页面类型分类

#### 类型A：分类归档页（Product Category Archive）
**示例**：`/en/solutions/dry-cargo`、`/en/industries/agriculture`

**页面结构**：
1. **Hero区域**
   - 大型背景图片（全宽，高度约400-500px）
   - 标题（H1，大字号，部分文字加粗）
   - 副标题（可选）
   - CTA按钮组（如"Schedules"、"Contact us"）
   - 背景图片支持响应式（移动端切换不同图片）

2. **面包屑导航**
   - 位置：Hero区域下方，内容区域顶部
   - 格式：`Home > 主分类 > 当前分类`
   - 样式：小字号，灰色链接

3. **页面内导航（Tabs）**
   - 位置：面包屑下方
   - 功能：切换同一分类下的不同视图（如"OVERVIEW"、"OUR EQUIPMENT"）
   - 样式：横向标签页，激活状态有下划线

4. **主内容区域**
   - **区块1：标题+描述**
     - 大标题（H2）
     - 描述段落（可包含内联链接）
   
   - **区块2：优势列表**
     - 横向布局（4列）
     - 每个优势：图标 + 标题
     - 响应式：移动端单列
   
   - **区块3：图片+文字组合**
     - 左右交替布局
     - 左侧：大图片（约50%宽度）
     - 右侧：标题（H3）+ 描述段落 + 内联链接
     - 响应式：移动端上下堆叠
   
   - **区块4：标签页切换展示**
     - 标题："What do you Need to Ship?"
     - 横向标签页导航（显示子分类）
     - 每个标签页：大图片 + 标题 + "READ MORE"链接
     - 标签页切换时，图片和内容平滑过渡
   
   - **区块5：规格展示（Equipment页面特有）**
     - 标题："Whatever you Need to Ship, we Have the Right..."
     - 多个产品卡片（网格布局）
     - 每个卡片：图片 + 标题 + 详细参数表格
     - 参数表格：折叠/展开功能
     - 参数包括：Internal dimensions、Door、Cubic Capacity、Maximum Payload等
   
   - **区块6：服务列表**
     - 标题："Because you Need More Than Port-to-Port Shipping"
     - 描述段落
     - 服务列表（4列，图标+标题）
     - 响应式：移动端单列
   
   - **区块7：联系表单**
     - 标题："Ready to Book? Get in Touch with our Local Offices"
     - 表单字段：
       - First name*、Last name*、Email*、Phone No.
       - Organisation*、Select Country*
       - What would you like to ship?*（多选框，Agriculture页面特有）
       - Query*（文本域）
       - Privacy Policy复选框
     - 提交按钮
     - 表单验证（前端+后端）

5. **Footer**
   - 4列布局：Office信息、业务链接、关于我们、社交媒体
   - 固定Footer，所有页面一致

---

#### 类型B：产品详情页（Single Product）
**示例**：具体产品页面（MSC网站的产品详情页结构）

**页面结构**：
1. **Hero区域**
   - 产品大图（全宽背景或左侧大图）
   - 产品标题（H1）
   - 产品关键信息（如规格、价格等）
   - CTA按钮（"Get Quote"、"Contact Us"）

2. **面包屑导航**
   - `Home > 分类 > 子分类 > 产品名称`

3. **产品详情区域**
   - **左侧：产品图片画廊**
     - 主图展示区（大图）
     - 缩略图导航（底部横向滚动）
     - 图片切换动画（淡入淡出）
     - 图片放大/灯箱功能
   
   - **右侧：产品信息**
     - 产品标题
     - 产品描述
     - 关键规格（表格形式）
     - 价格/询价按钮
     - 联系方式
   
   - **下方：详细内容Tabs**
     - Overview（概述）
     - Specifications（规格参数）
     - Applications（应用场景）
     - Certifications（认证证书）
     - Related Products（相关产品）

4. **相关产品推荐**
   - 同分类产品网格展示
   - 横向滚动或网格布局

---

#### 类型C：产品列表页（Product Archive）
**当前实现**：`archive-product.php`

**MSC风格改进**：
1. **Hero区域**
   - 分类标题 + 描述
   - 背景图片（可选）

2. **筛选和搜索**
   - 左侧：分类筛选（多级分类树）
   - 顶部：搜索框 + 排序下拉菜单
   - 筛选结果实时更新（AJAX）

3. **产品网格**
   - 响应式网格（桌面4列，平板2列，移动1列）
   - 产品卡片样式：
     - 图片（固定尺寸，aspect-ratio）
     - 标题（2行，超出省略）
     - 描述（3行，超出省略）
     - 关键信息（库存、价格等）
     - CTA按钮（"View Details"、"Quick Inquiry"）

4. **分页/无限滚动**
   - 可选：传统分页或无限滚动
   - 加载状态指示器

---

### 1.2 设计特点总结

#### 视觉设计
- **配色**：深蓝色主色（#1a365d），白色背景，灰色文字
- **字体**：无衬线字体，大标题粗体，正文常规
- **间距**：大间距（section之间80-120px），小间距（元素之间16-24px）
- **圆角**：卡片和按钮使用小圆角（4-8px）
- **阴影**：卡片使用轻微阴影（box-shadow: 0 2px 8px rgba(0,0,0,0.1)）

#### 交互设计
- **悬浮效果**：卡片hover时上浮（transform: translateY(-4px)）+ 阴影加深
- **动画**：所有过渡使用平滑动画（transition: 0.3s ease）
- **标签页切换**：内容淡入淡出 + 图片缩放
- **表单交互**：输入框聚焦时边框颜色变化，错误提示即时显示

#### 响应式设计
- **断点**：
  - Mobile: < 768px
  - Tablet: 768px - 1024px
  - Desktop: > 1024px
- **布局调整**：
  - 桌面：多列布局（2-4列）
  - 平板：2列布局
  - 移动：单列布局，图片和文字上下堆叠

---

## 二、当前项目子页面现状

### 2.1 现有页面类型

1. **分类归档页**：`archive-product.php`
   - 基础实现：有标题、描述、筛选、产品网格
   - 缺少：Hero区域、页面内导航、MSC风格的内容区块

2. **产品详情页**：`single-product.php`
   - 基础实现：有图片画廊、产品信息、标签页
   - 缺少：Hero区域、MSC风格的信息展示

3. **普通页面**：`page.php`
   - 基础实现：标题+内容
   - 缺少：MSC风格的内容区块

---

## 三、改版需求清单

### 3.1 分类归档页改版（优先级：高）

#### 需求1：Hero区域
- [ ] 添加大型背景图片支持（ACF图片字段）
- [ ] 标题（H1）样式优化（大字号、部分文字加粗）
- [ ] CTA按钮组（可配置，通过ACF）
- [ ] 响应式图片切换（移动端使用不同图片）

#### 需求2：页面内导航（Tabs）
- [ ] 创建Tab导航组件
- [ ] 支持多个Tab页面（通过ACF Repeater）
- [ ] Tab切换动画（淡入淡出）
- [ ] 锚点导航支持（点击Tab跳转到对应区块）

#### 需求3：内容区块组件化
- [ ] **区块：标题+描述**
  - ACF字段：标题、描述、内联链接
- [ ] **区块：优势列表**
  - ACF Repeater：图标、标题、描述（可选）
  - 响应式布局（4列→2列→1列）
- [ ] **区块：图片+文字组合**
  - ACF字段：图片、标题、描述、内联链接
  - 布局选项：左图右文、右图左文
- [ ] **区块：标签页切换展示**
  - ACF Repeater：标签标题、图片、描述、链接
  - JavaScript：标签页切换逻辑
- [ ] **区块：服务列表**
  - ACF Repeater：图标、标题
  - 4列网格布局
- [ ] **区块：联系表单**
  - 表单字段配置（ACF）
  - 表单验证（前端+后端）
  - AJAX提交
  - 邮件通知

---

### 3.2 产品详情页改版（优先级：中）

#### 需求1：Hero区域
- [ ] 产品大图作为Hero背景（或左侧大图）
- [ ] 产品标题（H1）+ 关键信息
- [ ] CTA按钮组

#### 需求2：产品信息优化
- [ ] 关键规格表格（顶部展示）
- [ ] 详细信息Tabs优化（样式+动画）
- [ ] 相关产品推荐区块

---

### 3.3 产品列表页改版（优先级：中）

#### 需求1：Hero区域
- [ ] 分类标题 + 描述
- [ ] 背景图片（可选）

#### 需求2：筛选优化
- [ ] 左侧分类树（多级分类）
- [ ] 顶部搜索+排序
- [ ] AJAX实时筛选

#### 需求3：产品卡片优化
- [ ] MSC风格卡片样式
- [ ] 悬浮动画
- [ ] 响应式网格

---

### 3.4 通用组件开发（优先级：高）

#### 组件1：Hero区域组件
- 文件：`template-parts/components/hero-section.php`
- ACF字段：
  - `hero_background_image`（图片）
  - `hero_background_image_mobile`（移动端图片，可选）
  - `hero_title`（文本）
  - `hero_subtitle`（文本，可选）
  - `hero_cta_buttons`（Repeater：按钮文本、链接、样式）

#### 组件2：面包屑导航组件
- 文件：`template-parts/components/breadcrumbs.php`
- 自动生成（WordPress标准面包屑）
- MSC风格样式

#### 组件3：标签页导航组件
- 文件：`template-parts/components/tab-navigation.php`
- ACF字段：`tab_items`（Repeater：标签标题、锚点ID）
- JavaScript：Tab切换逻辑

#### 组件4：内容区块组件库
- `template-parts/blocks/title-description.php`（标题+描述）
- `template-parts/blocks/advantages-list.php`（优势列表）
- `template-parts/blocks/image-text.php`（图片+文字）
- `template-parts/blocks/tabbed-content.php`（标签页内容）
- `template-parts/blocks/services-list.php`（服务列表）
- `template-parts/blocks/contact-form.php`（联系表单）

#### 组件5：联系表单组件
- 文件：`template-parts/components/contact-form.php`
- ACF字段配置表单字段
- 表单验证（前端：JavaScript，后端：PHP）
- AJAX提交处理
- 邮件通知（使用WordPress `wp_mail()`）

---

## 四、技术实现方案

### 4.1 ACF字段组设计

#### 字段组1：分类归档页设置（绑定到分类术语）
**位置**：`inc/acf-fields.php`

**字段结构**：
```php
// Hero区域
- hero_background_image (Image)
- hero_background_image_mobile (Image, 可选)
- hero_title (Text)
- hero_subtitle (Textarea, 可选)
- hero_cta_buttons (Repeater)
  - button_text (Text)
  - button_link (Link)
  - button_style (Select: primary/secondary)

// 页面内导航
- enable_tab_navigation (True/False)
- tab_items (Repeater)
  - tab_title (Text)
  - tab_anchor_id (Text)

// 内容区块（通过Flexible Content实现）
- content_blocks (Flexible Content)
  - Layout: title_description
  - Layout: advantages_list
  - Layout: image_text
  - Layout: tabbed_content
  - Layout: services_list
  - Layout: contact_form
```

#### 字段组2：产品详情页设置（绑定到Product Post Type）
**字段结构**：
```php
// Hero区域
- product_hero_image (Image)
- product_hero_title (Text, 可选，默认使用产品标题)
- product_key_specs (Repeater)
  - spec_label (Text)
  - spec_value (Text)

// 详细信息Tabs
- product_overview (Wysiwyg)
- product_specifications (Repeater)
  - spec_category (Text)
  - spec_items (Repeater)
    - item_label (Text)
    - item_value (Text)
- product_applications (Wysiwyg)
- product_certifications (Repeater: 证书图片+标题)
```

---

### 4.2 CSS样式实现

#### 文件结构
```
assets/css/
├── archive-product.css (分类归档页样式)
├── single-product.css (产品详情页样式)
├── components/
│   ├── hero-section.css (Hero组件样式)
│   ├── breadcrumbs.css (面包屑样式)
│   ├── tabs.css (标签页样式)
│   └── contact-form.css (表单样式)
└── blocks/
    ├── advantages-list.css (优势列表样式)
    ├── image-text.css (图片文字样式)
    └── tabbed-content.css (标签页内容样式)
```

#### 关键样式实现

**Hero区域**：
```css
.hero-section {
    position: relative;
    width: 100%;
    height: 500px; /* 桌面端 */
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100%;
    color: white;
    text-align: center;
    padding: 0 var(--space-4);
}

@media (max-width: 768px) {
    .hero-section {
        height: 400px;
    }
}
```

**标签页导航**：
```css
.tab-navigation {
    display: flex;
    gap: var(--space-4);
    border-bottom: 2px solid var(--color-border);
}

.tab-item {
    padding: var(--space-3) var(--space-4);
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-item.active {
    border-bottom-color: var(--color-primary);
    color: var(--color-primary);
    font-weight: 600;
}
```

**图片+文字组合**：
```css
.image-text-block {
    display: flex;
    gap: var(--space-6);
    align-items: center;
    margin: var(--space-8) 0;
}

.image-text-block.image-left {
    flex-direction: row;
}

.image-text-block.image-right {
    flex-direction: row-reverse;
}

.image-text-block .block-image {
    flex: 1;
    max-width: 50%;
}

.image-text-block .block-content {
    flex: 1;
    max-width: 50%;
}

@media (max-width: 768px) {
    .image-text-block {
        flex-direction: column !important;
    }
    
    .image-text-block .block-image,
    .image-text-block .block-content {
        max-width: 100%;
    }
}
```

---

### 4.3 JavaScript实现

#### 文件结构
```
assets/js/
├── archive-product.js (分类归档页交互)
├── single-product.js (产品详情页交互)
├── components/
│   ├── tabs.js (标签页切换)
│   ├── tabbed-content.js (标签页内容切换)
│   └── contact-form.js (表单验证和提交)
```

#### 关键功能实现

**标签页切换**：
```javascript
// tabs.js
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-item');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-tab');
            
            // 移除所有激活状态
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));
            
            // 激活当前标签页
            this.classList.add('active');
            const targetPanel = document.getElementById(targetId);
            if (targetPanel) {
                targetPanel.classList.add('active');
                // 平滑滚动到目标位置
                targetPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
```

**标签页内容切换（带图片动画）**：
```javascript
// tabbed-content.js
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.content-tab-button');
    const tabPanels = document.querySelectorAll('.content-tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-content-tab');
            
            // 淡出当前内容
            tabPanels.forEach(panel => {
                if (panel.classList.contains('active')) {
                    panel.style.opacity = '0';
                    setTimeout(() => {
                        panel.classList.remove('active');
                    }, 300);
                }
            });
            
            // 淡入新内容
            setTimeout(() => {
                const targetPanel = document.getElementById(targetId);
                if (targetPanel) {
                    targetPanel.classList.add('active');
                    setTimeout(() => {
                        targetPanel.style.opacity = '1';
                    }, 50);
                }
            }, 300);
        });
    });
});
```

**联系表单验证和AJAX提交**：
```javascript
// contact-form.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // 前端验证
        if (!validateForm()) {
            return;
        }
        
        // 收集表单数据
        const formData = new FormData(form);
        
        // AJAX提交
        fetch(angolaB2B.ajaxUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.data.message);
                form.reset();
            } else {
                showErrorMessage(data.data.message);
            }
        })
        .catch(error => {
            showErrorMessage('提交失败，请稍后重试。');
        });
    });
});
```

---

## 五、文件修改清单

### 5.1 需要修改的文件

1. **`archive-product.php`**
   - 添加Hero区域
   - 添加页面内导航（Tabs）
   - 添加内容区块支持（Flexible Content）

2. **`single-product.php`**
   - 添加Hero区域
   - 优化产品信息展示

3. **`inc/acf-fields.php`**
   - 添加分类归档页字段组
   - 添加产品详情页字段组
   - 添加内容区块字段组（Flexible Content）

4. **`inc/enqueue-scripts.php`**
   - 注册新的CSS文件
   - 注册新的JavaScript文件

---

### 5.2 需要新增的文件

#### PHP模板文件
1. `template-parts/components/hero-section.php`
2. `template-parts/components/breadcrumbs.php`
3. `template-parts/components/tab-navigation.php`
4. `template-parts/components/contact-form.php`
5. `template-parts/blocks/title-description.php`
6. `template-parts/blocks/advantages-list.php`
7. `template-parts/blocks/image-text.php`
8. `template-parts/blocks/tabbed-content.php`
9. `template-parts/blocks/services-list.php`

#### CSS文件
1. `assets/css/archive-product.css`
2. `assets/css/single-product.css`
3. `assets/css/components/hero-section.css`
4. `assets/css/components/breadcrumbs.css`
5. `assets/css/components/tabs.css`
6. `assets/css/components/contact-form.css`
7. `assets/css/blocks/advantages-list.css`
8. `assets/css/blocks/image-text.css`
9. `assets/css/blocks/tabbed-content.css`

#### JavaScript文件
1. `assets/js/archive-product.js`
2. `assets/js/single-product.js`
3. `assets/js/components/tabs.js`
4. `assets/js/components/tabbed-content.js`
5. `assets/js/components/contact-form.js`

#### AJAX处理文件
1. `inc/ajax-handlers.php`（修改，添加联系表单提交处理）

---

## 六、开发步骤

### 阶段1：基础组件开发（优先级：高）

**步骤1.1：Hero区域组件**
1. 创建 `template-parts/components/hero-section.php`
2. 创建 `assets/css/components/hero-section.css`
3. 添加ACF字段（hero_background_image、hero_title等）
4. 实现响应式图片切换
5. 测试多语言支持

**步骤1.2：面包屑导航组件**
1. 创建 `template-parts/components/breadcrumbs.php`
2. 创建 `assets/css/components/breadcrumbs.css`
3. 使用WordPress标准面包屑函数
4. 应用MSC风格样式

**步骤1.3：标签页导航组件**
1. 创建 `template-parts/components/tab-navigation.php`
2. 创建 `assets/css/components/tabs.css`
3. 创建 `assets/js/components/tabs.js`
4. 实现Tab切换动画
5. 添加锚点导航支持

---

### 阶段2：内容区块组件开发（优先级：高）

**步骤2.1：基础区块**
1. 创建标题+描述区块
2. 创建优势列表区块
3. 创建图片+文字组合区块
4. 每个区块包含：PHP模板、CSS样式、ACF字段配置

**步骤2.2：高级区块**
1. 创建标签页内容区块（带图片切换动画）
2. 创建服务列表区块
3. 创建联系表单区块（包含验证和AJAX提交）

---

### 阶段3：页面模板改版（优先级：高）

**步骤3.1：分类归档页改版**
1. 修改 `archive-product.php`，添加Hero区域
2. 添加页面内导航（Tabs）
3. 集成内容区块（Flexible Content）
4. 添加ACF字段组（绑定到分类术语）
5. 测试所有内容区块

**步骤3.2：产品详情页改版**
1. 修改 `single-product.php`，添加Hero区域
2. 优化产品信息展示
3. 添加关键规格表格
4. 优化详细信息Tabs样式

**步骤3.3：产品列表页改版**
1. 优化 `archive-product.php`的列表视图
2. 添加分类筛选树
3. 优化产品卡片样式
4. 实现AJAX筛选

---

### 阶段4：联系表单功能开发（优先级：中）

**步骤4.1：表单前端**
1. 创建联系表单组件
2. 实现前端验证（JavaScript）
3. 实现表单样式（MSC风格）

**步骤4.2：表单后端**
1. 添加AJAX处理函数（`inc/ajax-handlers.php`）
2. 实现后端验证（PHP）
3. 实现邮件通知（`wp_mail()`）
4. 添加成功/错误消息显示

---

### 阶段5：测试和优化（优先级：高）

**步骤5.1：功能测试**
1. 测试所有内容区块显示
2. 测试表单提交和邮件通知
3. 测试标签页切换动画
4. 测试响应式布局

**步骤5.2：多语言测试**
1. 测试所有4种语言的显示
2. 测试Polylang翻译字符串
3. 测试URL结构（目录方式）

**步骤5.3：性能优化**
1. 图片懒加载
2. JavaScript代码优化
3. CSS代码压缩
4. 缓存优化

---

## 七、占位图片使用方案

### 7.1 分类归档页Hero图片

**建筑工程分类**：
- 桌面端：`https://www.msc.com/assets/images/solutions/dry-cargo/hero-desktop.jpg`
- 移动端：`https://www.msc.com/assets/images/solutions/dry-cargo/hero-mobile.jpg`

**建筑材料分类**：
- 桌面端：`https://www.msc.com/assets/images/industries/agriculture/hero-desktop.jpg`
- 移动端：`https://www.msc.com/assets/images/industries/agriculture/hero-mobile.jpg`

**农机农具分类**：
- 使用MSC Agriculture页面的Hero图片
- URL：从MSC网站获取

**工业设备分类**：
- 使用MSC Dry Cargo页面的Hero图片
- URL：从MSC网站获取

---

### 7.2 内容区块图片

**图片+文字组合区块**：
- 使用MSC对应页面的图片
- 临时使用MSC图片URL，后续替换为实际图片

**标签页内容图片**：
- 使用MSC对应标签页的图片
- 每个标签页一张图片

**优势列表图标**：
- 使用MSC的图标样式
- 或使用Font Awesome图标库

---

## 八、注意事项

### 8.1 技术注意事项

1. **ACF Flexible Content限制**
   - ACF Free版本不支持Flexible Content
   - **解决方案**：使用Repeater字段 + 条件逻辑，或升级到ACF Pro

2. **分类术语字段绑定**
   - ACF字段组需要绑定到分类术语（`product_category`）
   - 使用 `'location' => array(array('rule' => 'taxonomy', 'operator' => '==', 'value' => 'product_category'))`

3. **多语言支持**
   - 所有ACF字段需要使用Polylang翻译
   - 使用 `pll__()` 函数翻译UI字符串
   - 内容区块中的文本需要多语言版本

4. **响应式图片**
   - Hero区域使用 `<picture>` 元素或CSS `@media` 查询
   - 所有图片使用 `srcset` 和 `sizes` 属性

5. **JavaScript依赖**
   - 标签页切换需要jQuery或原生JavaScript
   - 表单AJAX提交使用Fetch API（现代浏览器）

---

### 8.2 内容管理注意事项

1. **内容区块顺序**
   - 通过ACF Repeater的拖拽功能调整顺序
   - 或使用Flexible Content（如果升级到ACF Pro）

2. **图片管理**
   - 所有占位图片存储在WordPress媒体库
   - 使用固定的图片尺寸（通过 `add_image_size()` 定义）

3. **表单字段配置**
   - 联系表单字段通过ACF配置
   - 支持条件显示（如Agriculture页面的产品类型选择）

---

### 8.3 性能注意事项

1. **图片优化**
   - 所有图片使用WebP格式（如果支持）
   - 使用适当的图片尺寸（不要加载过大的图片）
   - 实现懒加载（`loading="lazy"`）

2. **JavaScript优化**
   - 使用事件委托减少事件监听器
   - 避免重复的DOM查询
   - 使用防抖（debounce）优化搜索和筛选

3. **CSS优化**
   - 使用CSS变量（已实现）
   - 避免深层嵌套选择器
   - 使用 `will-change` 优化动画性能

---

## 九、测试清单

### 9.1 功能测试

- [ ] Hero区域显示正常（桌面端和移动端）
- [ ] 面包屑导航链接正确
- [ ] 标签页导航切换正常
- [ ] 所有内容区块显示正常
- [ ] 图片+文字组合区块布局正确（左右交替）
- [ ] 标签页内容切换动画流畅
- [ ] 联系表单验证正常（前端+后端）
- [ ] 表单AJAX提交正常
- [ ] 邮件通知正常发送

---

### 9.2 视觉测试

- [ ] 所有页面样式与MSC网站一致
- [ ] 响应式布局正常（桌面、平板、移动）
- [ ] 动画效果流畅
- [ ] 图片显示清晰（无模糊）
- [ ] 字体大小和间距合适

---

### 9.3 多语言测试

- [ ] 所有4种语言显示正常
- [ ] 翻译字符串正确
- [ ] URL结构正确（目录方式）
- [ ] 语言切换功能正常

---

### 9.4 浏览器兼容性测试

- [ ] Chrome（最新版本）
- [ ] Firefox（最新版本）
- [ ] Safari（最新版本）
- [ ] Edge（最新版本）
- [ ] 移动浏览器（iOS Safari、Chrome Mobile）

---

### 9.5 性能测试

- [ ] 页面加载速度（目标：< 3秒）
- [ ] 图片加载优化（懒加载）
- [ ] JavaScript执行效率
- [ ] CSS渲染性能

---

## 十、后续优化建议

### 10.1 短期优化（1-2周）

1. **图片CDN**
   - 考虑使用CDN加速图片加载
   - 或使用WordPress图片优化插件

2. **缓存优化**
   - 实现页面缓存（WP Super Cache或W3 Total Cache）
   - 实现对象缓存（Redis或Memcached）

3. **SEO优化**
   - 添加结构化数据（Schema.org）
   - 优化页面标题和描述
   - 添加Open Graph标签

---

### 10.2 中期优化（1-2月）

1. **高级功能**
   - 产品对比功能
   - 收藏夹功能
   - 最近浏览历史

2. **用户体验优化**
   - 添加搜索建议（自动完成）
   - 添加筛选历史记录
   - 添加分享功能

---

### 10.3 长期优化（3-6月）

1. **数据分析**
   - 集成Google Analytics
   - 添加热力图分析
   - 添加用户行为追踪

2. **A/B测试**
   - 测试不同的CTA按钮文案
   - 测试不同的布局方案
   - 优化转化率

---

## 十一、开发时间估算

### 阶段1：基础组件开发
- **预计时间**：3-5天
- **包括**：Hero组件、面包屑、标签页导航

### 阶段2：内容区块组件开发
- **预计时间**：5-7天
- **包括**：所有内容区块组件

### 阶段3：页面模板改版
- **预计时间**：4-6天
- **包括**：分类归档页、产品详情页、产品列表页

### 阶段4：联系表单功能开发
- **预计时间**：2-3天
- **包括**：表单前端、后端、邮件通知

### 阶段5：测试和优化
- **预计时间**：3-5天
- **包括**：功能测试、多语言测试、性能优化

**总计**：17-26天（约3-4周）

---

## 十二、占位图片详细清单

### 12.1 Hero区域图片（8张）

**建筑工程分类**：
- 桌面端：`https://www.msc.com/assets/images/solutions/dry-cargo/MSC16006541-test4.jpg`
- 移动端：`https://www.msc.com/assets/images/solutions/dry-cargo/MSC16006541_m.webp`

**建筑材料分类**：
- 桌面端：`https://www.msc.com/assets/images/industries/agriculture/MSC15005487.jpg`
- 移动端：`https://www.msc.com/assets/images/industries/agriculture/MSC15005487.jpg`（相同）

**农机农具分类**：
- 桌面端：`https://www.msc.com/assets/images/industries/agriculture/MSC15005487.jpg`
- 移动端：`https://www.msc.com/assets/images/industries/agriculture/MSC15005487.jpg`

**工业设备分类**：
- 桌面端：`https://www.msc.com/assets/images/solutions/dry-cargo/MSC16006541-test4.jpg`
- 移动端：`https://www.msc.com/assets/images/solutions/dry-cargo/MSC16006541_m.webp`

---

### 12.2 内容区块图片（20+张）

**图片+文字组合区块**：
- 从MSC对应页面获取图片URL
- 每个分类页面需要2-3张图片

**标签页内容图片**：
- 从MSC对应标签页获取图片
- 每个标签页一张图片（约4-6张/页面）

**优势列表图标**：
- 使用Font Awesome图标或SVG图标
- 不需要占位图片

---

**注意**：所有占位图片都是临时使用，改版完成后需要替换为实际项目图片。

