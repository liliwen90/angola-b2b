# GitHub 仓库创建和推送指南

## 📋 步骤概览

1. ✅ 本地代码已准备就绪（已提交）
2. 🔄 创建 GitHub 仓库
3. 🔄 添加远程仓库
4. 🔄 推送代码到 GitHub
5. 🔄 在服务器上克隆仓库

---

## 步骤1：创建 GitHub 仓库

### 1.1 访问 GitHub

1. 打开浏览器，访问：**https://github.com/new**
2. 如果没有账号，先注册（免费）

### 1.2 填写仓库信息

- **Repository name**（仓库名）：`angola-b2b` 或 `unibro-wordpress-theme`
- **Description**（描述）：`Unibro B2B WordPress Theme - Angola Market`
- **Visibility**（可见性）：
  - ✅ **Private**（私有）- 推荐，代码不公开
  - ⚠️  **Public**（公开）- 任何人都能看到代码

### 1.3 重要：不要初始化仓库

⚠️ **不要勾选以下选项**：
- ❌ Add a README file
- ❌ Add .gitignore
- ❌ Choose a license

**原因**：我们本地已有代码和 `.gitignore`，不需要GitHub初始化。

### 1.4 创建仓库

点击 **"Create repository"** 按钮

---

## 步骤2：获取仓库地址

创建成功后，GitHub会显示仓库页面，你会看到：

```
Quick setup — if you've done this kind of thing before
https://github.com/YOUR_USERNAME/angola-b2b.git
```

**复制这个地址**，稍后会用到。

---

## 步骤3：在本地添加远程仓库并推送

### 3.1 打开 PowerShell

在项目根目录打开 PowerShell，或使用 Cursor 的终端。

### 3.2 进入主题目录

```powershell
cd "F:\011 Projects\UnibroWeb\Unirbro\wp-content\themes\angola-b2b"
```

### 3.3 添加远程仓库

**替换 `YOUR_USERNAME` 和 `YOUR_REPO_NAME`**：

```powershell
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
```

**示例**：
```powershell
git remote add origin https://github.com/yourusername/angola-b2b.git
```

### 3.4 验证远程仓库

```powershell
git remote -v
```

应该显示：
```
origin  https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git (fetch)
origin  https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git (push)
```

### 3.5 推送代码

```powershell
git push -u origin master
```

**如果提示输入用户名和密码**：
- **用户名**：你的 GitHub 用户名
- **密码**：使用 **Personal Access Token**（不是GitHub密码）

#### 如何创建 Personal Access Token：

1. 访问：https://github.com/settings/tokens
2. 点击 **"Generate new token"** → **"Generate new token (classic)"**
3. 填写：
   - **Note**：`WordPress Theme Deployment`
   - **Expiration**：选择过期时间（或 No expiration）
   - **Scopes**：勾选 `repo`（完整仓库访问权限）
4. 点击 **"Generate token"**
5. **复制token**（只显示一次，务必保存）
6. 在推送时，密码处粘贴这个token

---

## 步骤4：验证推送成功

推送成功后，刷新 GitHub 仓库页面，应该能看到所有文件。

---

## 步骤5：在服务器上安装 Git

### 方法1：通过宝塔面板（推荐）

1. 登录宝塔面板
2. 进入：**软件商店** → **运行环境**
3. 搜索：**Git**
4. 点击 **"安装"**
5. 等待安装完成

### 方法2：通过 SSH 终端

```bash
# 连接到服务器
ssh root@8.208.30.159

# 安装 Git
yum install -y git

# 验证安装
git --version
```

---

## 步骤6：在服务器上克隆仓库

### 6.1 通过宝塔终端或 SSH

```bash
# 进入 WordPress 主题目录
cd /www/wwwroot/www.unibroint.com/wp-content/themes/

# 如果 angola-b2b 目录已存在，先备份
mv angola-b2b angola-b2b-backup

# 克隆仓库（替换 YOUR_USERNAME 和 YOUR_REPO_NAME）
git clone https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git angola-b2b

# 设置文件权限
chown -R www:www angola-b2b/
chmod -R 755 angola-b2b/
```

### 6.2 如果是私有仓库

如果仓库是私有的，需要配置认证：

**方法1：使用 Personal Access Token**

```bash
# 克隆时输入用户名和token
git clone https://YOUR_USERNAME:YOUR_TOKEN@github.com/YOUR_USERNAME/YOUR_REPO_NAME.git angola-b2b
```

**方法2：配置 SSH 密钥（推荐，更安全）**

```bash
# 在服务器上生成SSH密钥
ssh-keygen -t ed25519 -C "your_email@example.com"

# 查看公钥
cat ~/.ssh/id_ed25519.pub

# 复制公钥内容，添加到GitHub：
# Settings → SSH and GPG keys → New SSH key
```

然后使用SSH地址克隆：
```bash
git clone git@github.com:YOUR_USERNAME/YOUR_REPO_NAME.git angola-b2b
```

---

## 步骤7：验证主题已部署

1. 登录 WordPress 后台
2. 进入：**外观** → **主题**
3. 确认 **angola-b2b** 主题已激活
4. 访问首页，检查是否正常显示

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

---

## ⚠️ 常见问题

### 问题1：推送时提示 "remote: Support for password authentication was removed"

**解决**：使用 Personal Access Token 代替密码

### 问题2：克隆时提示 "Permission denied"

**解决**：
- 私有仓库需要使用 Personal Access Token 或 SSH 密钥
- 检查仓库地址是否正确

### 问题3：服务器上 Git 命令不存在

**解决**：
- 通过宝塔面板安装 Git
- 或通过 SSH 执行：`yum install -y git`

---

## 📝 下一步

完成 Git 部署后，还需要：

1. ✅ 迁移数据库（使用 `migrate-to-server.bat`）
2. ✅ 迁移媒体文件（使用 `migrate-to-server.bat`）
3. ✅ 导入数据库到服务器
4. ✅ 解压媒体文件到服务器

---

## 🎯 快速命令参考

### 本地推送
```powershell
cd "F:\011 Projects\UnibroWeb\Unirbro\wp-content\themes\angola-b2b"
git add .
git commit -m "更新描述"
git push origin master
```

### 服务器更新
```bash
cd /www/wwwroot/www.unibroint.com/wp-content/themes/angola-b2b
git pull origin master
```

---

**准备好了吗？开始创建 GitHub 仓库吧！** 🚀

