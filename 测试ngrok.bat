@echo off
chcp 65001 >nul
echo ========================================
echo    测试ngrok连接
echo ========================================
echo.
echo 正在检查ngrok状态...
echo.
timeout /t 3 /nobreak >nul

echo 方法1: 检查进程
tasklist /FI "IMAGENAME eq ngrok.exe" 2>NUL | find /I /N "ngrok.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [OK] ngrok进程正在运行
) else (
    echo [错误] ngrok进程未运行
    echo.
    echo 请先运行"启动ngrok.bat"
    goto end
)

echo.
echo 方法2: 检查Web接口
powershell -NoProfile -Command "try { $r = Invoke-WebRequest -Uri http://127.0.0.1:4040/api/tunnels -UseBasicParsing -TimeoutSec 3; $t = $r.Content | ConvertFrom-Json; $h = $t.tunnels | Where-Object {$_.proto -eq 'https'} | Select-Object -First 1; Write-Host '[OK] ngrok Web接口可访问'; Write-Host ''; Write-Host '公网URL:'; Write-Host $h.public_url; Write-Host ''; Write-Host '转发到:'; Write-Host $h.config.addr; Write-Host ''; Write-Host '域名头:'; if ($h.config.host_header) { Write-Host $h.config.host_header } else { Write-Host '未设置' } } catch { Write-Host '[错误] 无法连接到ngrok Web接口' }"

echo.
echo ========================================
echo 提示：也可以访问 http://127.0.0.1:4040
echo 在浏览器中查看ngrok状态
echo ========================================

:end
echo.
pause
