@echo off
echo.
echo ########################################
echo            Run PHPUnit tests
echo ########################################
cd ./phpunit/
FOR %%f IN (*.php) DO (
    echo.
    echo ****************************************
    echo %%f
    call phpunit %%f
    echo ****************************************
    echo.
)
echo.
echo ########################################
echo        Run codeception unit tests
echo ########################################
cd ../
php codecept.phar run unit %1 %2 %3
echo.
echo ########################################
echo     Run codeception acceptance tests
echo ########################################
php codecept.phar run acceptance %1 %2 %3
pause
