@echo off
chcp 65001 >nul
echo ========================================
echo    Local站点外网访问 (ngrok)
echo ========================================
echo.
echo ✅ ngrok已配置完成！
echo.
echo 请选择要分享的端口：
echo.
echo 1. 端口80 (angola-b2b.local - 默认)
echo 2. 端口10000
echo 3. 端口10001
echo 4. 端口10002
echo 5. 自定义端口
echo.

set /p choice="请输入选项 (1-5，默认1): "
if "%choice%"=="" set choice=1

if "%choice%"=="1" set PORT=80
if "%choice%"=="2" set PORT=10000
if "%choice%"=="3" set PORT=10001
if "%choice%"=="4" set PORT=10002
if "%choice%"=="5" (
    set /p PORT="请输入端口号: "
)

echo.
echo ════════════════════════════════════════
echo  正在启动ngrok隧道...
echo  端口: %PORT%
echo ════════════════════════════════════════
echo.
echo 📌 重要提示：
echo   - ngrok会显示一个公网URL（如 https://xxxx.ngrok-free.app）
echo   - 将这个URL分享给其他人即可访问您的Local站点
echo   - 按 Ctrl+C 可以停止隧道
echo.
echo ════════════════════════════════════════
echo.

C:\ngrok\ngrok.exe http %PORT%

pause

