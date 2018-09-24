@echo off
FOR %%f IN (*.php) DO (
    echo.
    echo ########################################
    echo %%f
    call phpunit %%f
    echo ########################################
    echo.
)
