# PHP-File-Upload-with-Class-usage
Basicly handling a file upload with a class carrying the weight of processing.

The form should allow the user to upload .png, .jpg,.gif, .doc, .xls, and .pdffiles. Into a folder named “uploads”(You
need to create this folder and include it with your files, remove any uploads before zipping so you do not go
overAngel’s file limit size)
Convert the file to all lowercase and strip out any bad characters, spaces,etc..
Check to make sure the filename is not already in your uploads folder, if it is make sure your plan is not to
overwrite the original.
Make sure you set the permissions on thisfolder (connect via FTP and set the permissions to 777, already done
for you inuniform server).
After uploading provide a link to another php file that list the filenames already in your uploads folder to the user
and provides a hyperlink to download them
