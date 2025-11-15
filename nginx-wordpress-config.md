# Nginx WordPress Rewrite规则配置指南

## 服务器信息
- **服务器类型**: Nginx
- **操作系统**: Alibaba Cloud Linux 3.2104 LTS 64位
- **网站域名**: www.unibroint.com

---

## 步骤1：找到Nginx配置文件

### 方法1：查找网站配置文件（推荐）
```bash
# 通常WordPress网站的配置文件在以下位置之一：
/etc/nginx/sites-available/www.unibroint.com
/etc/nginx/sites-available/default
/etc/nginx/conf.d/www.unibroint.com.conf
/etc/nginx/nginx.conf
```

### 方法2：通过SSH查找
```bash
# SSH连接到服务器
ssh root@8.208.30.159

# 查找包含域名unibroint的配置文件
grep -r "unibroint" /etc/nginx/

# 或者查找所有配置文件
ls -la /etc/nginx/sites-available/
ls -la /etc/nginx/conf.d/
```

---

## 步骤2：编辑Nginx配置文件

找到配置文件后，编辑它（使用nano或vi）：
```bash
# 使用nano编辑器（推荐新手）
nano /etc/nginx/sites-available/www.unibroint.com

# 或使用vi编辑器
vi /etc/nginx/sites-available/www.unibroint.com
```

---

## 步骤3：添加WordPress Rewrite规则

在server块中，找到`location /`部分，确保配置如下：

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name www.unibroint.com unibroint.com;
    
    # 如果使用SSL，取消注释以下行
    # listen 443 ssl http2;
    # listen [::]:443 ssl http2;
    # ssl_certificate /path/to/certificate.crt;
    # ssl_certificate_key /path/to/private.key;
    
    root /var/www/html;  # 修改为你的WordPress实际安装路径
    index index.php index.html index.htm;
    
    # WordPress永久链接支持 - 核心配置
    location / {
        try_files $uri $uri/ /index.php?$args;
    }
    
    # PHP文件处理
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;  # 或 fastcgi_params;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;  # 或 127.0.0.1:9000
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # 禁止访问隐藏文件
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }
    
    # 禁止访问敏感文件
    location ~* /(?:uploads|files)/.*\.php$ {
        deny all;
    }
    
    # 静态文件缓存（可选，提升性能）
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
}
```

---

## 步骤4：测试配置

保存文件后，测试Nginx配置是否正确：
```bash
nginx -t
```

如果看到 `syntax is ok` 和 `test is successful`，说明配置正确。

---

## 步骤5：重启Nginx

配置测试通过后，重启Nginx服务：
```bash
# 重新加载配置（推荐，不会中断服务）
systemctl reload nginx

# 或完全重启
systemctl restart nginx

# 检查Nginx状态
systemctl status nginx
```

---

## 步骤6：验证修复

1. 访问首页：https://www.unibroint.com/
2. 点击5大分类卡片，检查是否正常打开
3. 点击新闻资讯链接，检查是否正常

---

## 常见问题排查

### 问题1：找不到配置文件
```bash
# 查看Nginx主配置文件
cat /etc/nginx/nginx.conf

# 查看include的配置文件
grep -r "include" /etc/nginx/nginx.conf
```

### 问题2：PHP-FPM未运行
```bash
# 检查PHP-FPM状态
systemctl status php-fpm
# 或
systemctl status php8.1-fpm  # 根据PHP版本调整

# 如果未运行，启动它
systemctl start php-fpm
```

### 问题3：权限问题
```bash
# 确保WordPress目录权限正确
chown -R www-data:www-data /var/www/html
find /var/www/html -type d -exec chmod 755 {} \;
find /var/www/html -type f -exec chmod 644 {} \;
```

### 问题4：SELinux阻止（如果启用）
```bash
# 检查SELinux状态
getenforce

# 如果返回Enforcing，需要设置上下文
chcon -R -t httpd_sys_content_t /var/www/html
chcon -R -t httpd_sys_rw_content_t /var/www/html/wp-content
```

---

## 完整配置示例

如果你的配置文件是空的或需要完整示例，可以使用以下完整配置：

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name www.unibroint.com unibroint.com;
    
    # 重定向HTTP到HTTPS（如果使用SSL）
    # return 301 https://$server_name$request_uri;
    
    root /var/www/html;
    index index.php index.html index.htm;
    
    # 日志文件
    access_log /var/log/nginx/unibroint-access.log;
    error_log /var/log/nginx/unibroint-error.log;
    
    # WordPress永久链接支持
    location / {
        try_files $uri $uri/ /index.php?$args;
    }
    
    # PHP处理
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    
    # 禁止访问隐藏文件
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }
    
    # 禁止执行PHP文件在uploads目录
    location ~* /(?:uploads|files)/.*\.php$ {
        deny all;
    }
    
    # 静态文件缓存
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
    
    # 限制上传文件大小
    client_max_body_size 64M;
}
```

---

## 重要提示

1. **备份配置文件**：修改前先备份
   ```bash
   cp /etc/nginx/sites-available/www.unibroint.com /etc/nginx/sites-available/www.unibroint.com.backup
   ```

2. **确认WordPress路径**：确保`root`指令指向正确的WordPress安装目录

3. **确认PHP-FPM socket路径**：根据你的PHP版本，socket路径可能不同：
   - PHP 7.4: `/var/run/php/php7.4-fpm.sock`
   - PHP 8.0: `/var/run/php/php8.0-fpm.sock`
   - PHP 8.1: `/var/run/php/php8.1-fpm.sock`
   
   或者使用TCP连接：`127.0.0.1:9000`

4. **如果使用SSL**：需要配置SSL证书并启用HTTPS

---

## 快速检查清单

- [ ] 找到Nginx配置文件
- [ ] 添加`try_files $uri $uri/ /index.php?$args;`规则
- [ ] 配置PHP-FPM处理
- [ ] 测试配置：`nginx -t`
- [ ] 重启Nginx：`systemctl reload nginx`
- [ ] 测试前端链接是否正常

