@echo off
chcp 65001 >nul
title ngrok - Local站点外网访问
color 0A

echo.
echo ╔═══════════════════════════════════════╗
echo ║   ngrok - Local站点外网访问工具      ║
echo ╚═══════════════════════════════════════╝
echo.
echo ✅ ngrok已配置完成！
echo.
echo 📋 站点信息：
echo    域名: angola-b2b.local
echo    端口: 80
echo.
echo ════════════════════════════════════════
echo  正在启动ngrok隧道...
echo ════════════════════════════════════════
echo.
echo 📌 重要提示：
echo   - ngrok会显示一个公网URL（如 https://xxxx.ngrok-free.app）
echo   - 将这个URL分享给其他人即可访问您的Local站点
echo   - 按 Ctrl+C 可以停止隧道
echo   - 关闭此窗口也会停止隧道
echo.
echo ⚠️  确保Local应用中的 angola-b2b 站点正在运行！
echo.
echo ════════════════════════════════════════
echo.
echo 🔧 正在检查并停止旧的ngrok进程...
taskkill /F /IM ngrok.exe >nul 2>&1
timeout /t 2 /nobreak >nul
echo ✅ 准备启动新的隧道...
echo.
echo 正在启动，请稍候...
echo.

REM 切换到ngrok目录并启动
cd /d C:\ngrok
ngrok.exe http 80 --host-header=rewrite angola-b2b.local:80

REM 如果上面的命令失败，尝试这个
if errorlevel 1 (
    echo.
    echo ⚠️  启动失败，尝试备用方式...
    ngrok.exe http 80 --host-header=angola-b2b.local:80
)

pause
