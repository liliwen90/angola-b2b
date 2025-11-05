@echo off
REM ngrok隧道启动脚本 - 用于分享Local站点
REM 使用方法：先配置ngrok authtoken，然后运行此脚本

echo ========================================
echo    Local站点外网访问工具 (ngrok)
echo ========================================
echo.
echo 请选择站点端口：
echo 1. 端口80 (默认)
echo 2. 端口10000
echo 3. 端口10001
echo 4. 端口10002
echo 5. 自定义端口
echo.

set /p choice="请输入选项 (1-5): "

if "%choice%"=="1" set PORT=80
if "%choice%"=="2" set PORT=10000
if "%choice%"=="3" set PORT=10001
if "%choice%"=="4" set PORT=10002
if "%choice%"=="5" (
    set /p PORT="请输入端口号: "
)

echo.
echo 正在启动ngrok隧道，端口: %PORT%
echo 请等待ngrok分配公网地址...
echo.
echo ⚠️  注意：首次使用需要先配置authtoken
echo    命令：ngrok authtoken YOUR_TOKEN
echo.

REM 检查ngrok是否在PATH中或当前目录
where ngrok >nul 2>&1
if %errorlevel% equ 0 (
    ngrok http %PORT%
) else (
    if exist "C:\ngrok\ngrok.exe" (
        C:\ngrok\ngrok.exe http %PORT%
    ) else (
        echo ❌ 错误：找不到ngrok.exe
        echo 请确保ngrok已安装并在PATH中，或修改脚本中的路径
        pause
    )
)

pause

