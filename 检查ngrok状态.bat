@echo off
chcp 65001 >nul
echo ========================================
echo    ngrok状态检查工具
echo ========================================
echo.

REM 检查ngrok是否运行
tasklist /FI "IMAGENAME eq ngrok.exe" 2>NUL | find /I /N "ngrok.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo ✅ ngrok正在运行
    echo.
    echo 正在获取公网URL...
    echo.
    
    REM 等待ngrok启动
    timeout /t 2 /nobreak >nul
    
    REM 获取URL（需要curl或PowerShell）
    powershell -Command "try { $result = (Invoke-WebRequest -Uri 'http://127.0.0.1:4040/api/tunnels' -UseBasicParsing).Content | ConvertFrom-Json; $url = ($result.tunnels | Where-Object {$_.proto -eq 'https'} | Select-Object -First 1).public_url; Write-Host '公网URL:'; Write-Host $url -ForegroundColor Green; Write-Host ''; Write-Host '转发到:'; $result.tunnels | Where-Object {$_.proto -eq 'https'} | ForEach-Object { Write-Host $_.config.addr } } catch { Write-Host '无法获取信息，请稍候再试' }"
    
    echo.
    echo 也可以在浏览器中访问: http://127.0.0.1:4040
    echo.
) else (
    echo ❌ ngrok未运行
    echo.
    echo 请先运行"启动ngrok.bat"
    echo.
)

pause

