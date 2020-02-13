# ers_dev_5_db_diff
Create DB Diff display tool using ers.dev.v.0.5

|Description|Screenshot|
----|----
|All tables in the specified DB will be displayed. <br>From there, <br>select the table you want to check for differences.|<img src="readme_imgs/select_tables.png" width="300">|
|The last ID of the playmary key of each table <br>and the current time are stored in a cookie <br>for checking.|<img src="readme_imgs/diff_record_before.png" width="300">|
|Start checking. <br>The inserted and updated records are displayed.|<img src="readme_imgs/diff_record_after.png" width="300">|

|Setup|Screenshot|File|
----|----|----
|Database|<img src="readme_imgs/setup_db.png" width="300">|db_diff/inc/db.inc.php|
|Sub directory|<img src="readme_imgs/setup_sub_directory.png" width="300">|db_diff/check_tables.inc.php|
|Group table|There is no problem if you do not fix it.|db_diff/inc/config.inc.php|
