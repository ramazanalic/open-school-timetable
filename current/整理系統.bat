@REM 排序CSS
php csscomb.php -i public\css

@REM 清理暫存空間
del /q .\app\storage\cache\*.*
del /q .\app\storage\logs\*.*
del /q .\app\storage\views\*.*
del /q .\app\storage\sessions\*.*

@REM 更新Laravel和Composer
call composer self-update
call composer update