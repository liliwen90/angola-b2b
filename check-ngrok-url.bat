@echo off
chcp 65001 >nul
echo ========================================
echo    ngrok状态查询
echo ========================================
echo.
echo 正在查询ngrok隧道状态...
echo.

curl -s http://127.0.0.1:4040/api/tunnels | findstr "public_url"
if %errorlevel% neq 0 (
    echo.
    echo ⚠️  无法获取隧道信息，可能ngrok未运行
    echo.
    echo 请访问 http://127.0.0.1:4040 查看详细信息
) else (
    echo.
    echo ✅ 公网URL已显示在上面
    echo    复制这个URL分享给其他人即可访问您的站点
)

echo.
pause

