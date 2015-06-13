These scripts compare three different styles of programming:

1. `fast.php` is quick to both implement and execute, but difficult to read, understand and maintain.
2. `readable.php` is easy to read, understand and maintain, but slower to execute and to implement.
3. `both.php` is easy to read, understand and maintain.  It is fast to execute, but slow to implement.

File | Technical debt | Implementation time | Execution time
--- | --- | --- | ---
`fast.php` | 3 units | ~1 hour | 0.5s
`readable.php` | 1-2 units | ~2 hours | 10s
`both.php` | 1 unit | ~3 hours | 0.5s
