@REM �Ƨ�CSS
php csscomb.php -i public\css

@REM �M�z�Ȧs�Ŷ�
del /q .\app\storage\cache\*.*
del /q .\app\storage\logs\*.*
del /q .\app\storage\views\*.*
del /q .\app\storage\sessions\*.*

@REM ��sLaravel�MComposer
call composer self-update
call composer update