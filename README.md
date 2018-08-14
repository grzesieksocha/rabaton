# CODE GENERATOR

Run `composer install`  
Run `php -S 127.0.0.1:8000 -t public`  

## WWW
Head to `http://localhost:8000/`

## CLI
Run `php bin/console app:generate-codes --number-of-codes 1000 --length-of-code 6 --file-path /tmp/codes.txt`

### Extras
You want to quickly check if generated file has any duplicates? Use the below command in console.

`{ sort | uniq -d | grep . -qc; } < codes.txt; echo $?`

**Result:**  
1 - test passed - no duplicates.  
0 - there are duplicates!