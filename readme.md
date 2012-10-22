# Brackets PHP Shell
This is a shell for Adobe-Brakcets for host in a webserver made in PHP.

How to Install
--------------
Copy to the *`www`* in your webserver and in the folder **`adobe_brackets`**
put the *src* and *samples* folder from **`adobe_brackets`** project. 
The appshell_extensions.js is taken from the **`adobe_brackets-shell`** project. 
You have to add the corrects permission to the filesystem to work fine.

Warning
---------
**Is not secure put online this site.**

Get into the code
---------------
**`Brackets PHP Shell`** use **`codeigniter 2.1.3`**
some files are removed see `ci_removed.md` 
Have to controller `main` for the main layout and other views like the opendialog,
and `ajax_api` for the filesystem native calls.
The file `\assets\public\js\native.js` made Ajax calls to php..

