@echo off
chcp 65001 >nul
echo ========================================
echo    ngrok启动脚本 - 完整版
echo ========================================
echo.
echo 请选择配置方式：
echo.
echo 1. 使用80端口 + 域名转发 (推荐)
echo 2. 使用其他端口 (如果Local使用了特定端口)
echo 3. 查看当前运行的Local站点端口
echo.

set /p choice="请输入选项 (1-3): "

if "%choice%"=="1" (
    echo.
    echo 正在启动ngrok (端口80 + angola-b2b.local域名转发)...
    echo.
    C:\ngrok\ngrok.exe http 80 --host-header=angola-b2b.local:80
    goto end
)

if "%choice%"=="2" (
    set /p PORT="请输入端口号: "
    echo.
    echo 正在启动ngrok (端口%PORT% + angola-b2b.local域名转发)...
    echo.
    C:\ngrok\ngrok.exe http %PORT% --host-header=angola-b2b.local:%PORT%
    goto end
)

if "%choice%"=="3" (
    echo.
    echo 正在检查Local站点端口...
    echo.
    netstat -ano | findstr LISTENING | findstr :10
    echo.
    echo 请查看上面的端口号，然后重新运行脚本选择选项2
    pause
    goto end
)

:end
pause

