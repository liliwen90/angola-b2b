# 服务器 Git 部署步骤

## ✅ 已完成

1. ✅ GitHub 仓库已创建：`https://github.com/liliwen90/angola-b2b`
2. ✅ 代码已推送到 GitHub

---

## 🚀 服务器端部署步骤

### 步骤1：在服务器上安装 Git

#### 方法1：通过宝塔面板（推荐）⭐⭐⭐

1. 登录宝塔面板：`http://8.208.30.159:8888`
2. 进入：**软件商店** → **运行环境**
3. 搜索：**Git**
4. 点击 **"安装"**
5. 等待安装完成（通常1-2分钟）

#### 方法2：通过 SSH 终端

```bash
# 连接到服务器
ssh root@8.208.30.159

# 安装 Git
yum install -y git

# 验证安装
git --version
```

---

### 步骤2：在服务器上克隆仓库

#### 通过宝塔终端执行

1. 登录宝塔面板
2. 进入：**文件** → 找到 `/www/wwwroot/www.unibroint.com/wp-content/themes/`
3. 点击右上角的 **"终端"** 按钮
4. 在终端中执行以下命令：

```bash
# 进入主题目录
cd /www/wwwroot/www.unibroint.com/wp-content/themes/

# 如果 angola-b2b 目录已存在，先备份
mv angola-b2b angola-b2b-backup

# 克隆仓库
git clone https://github.com/liliwen90/angola-b2b.git

# 设置文件权限
chown -R www:www angola-b2b/
chmod -R 755 angola-b2b/
```

#### 或通过 SSH 执行

```bash
# 连接到服务器
ssh root@8.208.30.159

# 进入主题目录
cd /www/wwwroot/www.unibroint.com/wp-content/themes/

# 备份现有主题（如果存在）
mv angola-b2b angola-b2b-backup

# 克隆仓库
git clone https://github.com/liliwen90/angola-b2b.git

# 设置权限
chown -R www:www angola-b2b/
chmod -R 755 angola-b2b/
```

---

### 步骤3：验证主题已部署

1. 登录 WordPress 后台：`https://www.unibroint.com/wp-admin`
2. 进入：**外观** → **主题**
3. 确认 **angola-b2b** 主题已存在
4. 如果未激活，点击 **"启用"**
5. 访问首页，检查是否正常显示

---

### 步骤4：迁移数据库和媒体文件

主题代码已部署，现在需要迁移内容：

#### 4.1 在本地运行迁移脚本

1. 双击运行：`migrate-to-server.bat`
2. 选择导出方式（推荐：WordPress CLI）
3. 按照提示完成数据库导出和URL替换
4. 自动打包媒体文件

#### 4.2 上传到服务器

1. **上传 database.sql**：
   - 宝塔面板 → 文件 → `/www/wwwroot/www.unibroint.com/`
   - 上传 `migration-package/database.sql`

2. **上传 uploads.zip**：
   - 宝塔面板 → 文件 → `/www/wwwroot/www.unibroint.com/wp-content/`
   - 上传 `migration-package/uploads.zip`
   - 右键点击 ZIP 文件 → **解压**

#### 4.3 导入数据库

1. 宝塔面板 → **数据库**
2. 找到 `www_unibroint_com` 数据库
3. 点击 **"管理"** 或 **"phpMyAdmin"**
4. 选择数据库
5. 点击 **"导入"** 标签
6. 选择 `database.sql` 文件
7. 点击 **"执行"**

#### 4.4 设置文件权限

```bash
# 在宝塔终端或SSH中执行
cd /www/wwwroot/www.unibroint.com/

# 设置上传目录权限
chown -R www:www wp-content/uploads/
chmod -R 755 wp-content/uploads/
```

#### 4.5 更新固定链接

1. 登录 WordPress 后台
2. 进入：**设置** → **固定链接**
3. 点击 **"保存更改"**（刷新固定链接）

---

## 🔄 后续更新流程

### 在本地开发并推送

```powershell
cd "F:\011 Projects\UnibroWeb\Unirbro\wp-content\themes\angola-b2b"
git add .
git commit -m "更新描述"
git push origin master
```

### 在服务器上更新

```bash
cd /www/wwwroot/www.unibroint.com/wp-content/themes/angola-b2b
git pull origin master
```

**或者通过宝塔终端**：
1. 宝塔面板 → 文件 → 进入主题目录
2. 点击右上角 **"终端"**
3. 执行：`git pull origin master`

---

## ⚠️ 重要提示

### 1. 如果是私有仓库

如果仓库是私有的，克隆时需要认证：

**方法1：使用 Personal Access Token**

```bash
git clone https://liliwen90:YOUR_TOKEN@github.com/liliwen90/angola-b2b.git
```

**方法2：配置 SSH 密钥（推荐）**

```bash
# 在服务器上生成SSH密钥
ssh-keygen -t ed25519 -C "your_email@example.com"

# 查看公钥
cat ~/.ssh/id_ed25519.pub

# 复制公钥，添加到GitHub：
# Settings → SSH and GPG keys → New SSH key
```

然后使用SSH地址克隆：
```bash
git clone git@github.com:liliwen90/angola-b2b.git
```

### 2. 文件权限

确保主题目录权限正确：
```bash
chown -R www:www /www/wwwroot/www.unibroint.com/wp-content/themes/angola-b2b/
chmod -R 755 /www/wwwroot/www.unibroint.com/wp-content/themes/angola-b2b/
```

### 3. 备份

在克隆新主题前，建议备份现有主题：
```bash
mv angola-b2b angola-b2b-backup-$(date +%Y%m%d)
```

---

## 📋 完整检查清单

- [ ] Git 已安装在服务器上
- [ ] 仓库已克隆到服务器
- [ ] 文件权限已设置正确
- [ ] 主题已在 WordPress 后台激活
- [ ] 数据库已导入
- [ ] 媒体文件已解压
- [ ] 固定链接已更新
- [ ] 首页能正常访问

---

## 🎯 快速命令参考

### 服务器端

```bash
# 安装Git
yum install -y git

# 克隆仓库
cd /www/wwwroot/www.unibroint.com/wp-content/themes/
git clone https://github.com/liliwen90/angola-b2b.git

# 设置权限
chown -R www:www angola-b2b/
chmod -R 755 angola-b2b/

# 更新主题
cd angola-b2b
git pull origin master
```

### 本地端

```powershell
# 推送更新
cd "F:\011 Projects\UnibroWeb\Unirbro\wp-content\themes\angola-b2b"
git add .
git commit -m "更新描述"
git push origin master
```

---

**准备好了吗？开始服务器部署吧！** 🚀

