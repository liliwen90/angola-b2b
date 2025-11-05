@echo off
chcp 65001 >nul
echo ========================================
echo    Local站点外网访问 (ngrok) - 修复版
echo ========================================
echo.
echo ✅ ngrok已配置完成！
echo.
echo 🔧 配置说明：
echo    Local使用虚拟主机，需要通过域名访问
echo    站点域名: angola-b2b.local
echo.
echo ════════════════════════════════════════
echo  正在启动ngrok隧道...
echo  ⚠️  这次会正确配置域名转发
echo ════════════════════════════════════════
echo.
echo 📌 重要提示：
echo   - ngrok会显示一个公网URL（如 https://xxxx.ngrok-free.app）
echo   - 将这个URL分享给其他人即可访问您的Local站点
echo   - 按 Ctrl+C 可以停止隧道
echo.
echo ════════════════════════════════════════
echo.

REM 使用host-header参数将请求转发到正确的域名
C:\ngrok\ngrok.exe http 80 --host-header=angola-b2b.local:80

pause

