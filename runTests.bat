echo off

.\vendor\bin\phpunit --bootstrap .\vendor\autoload.php --testdox tests > .\testOutput.txt

notepad.exe