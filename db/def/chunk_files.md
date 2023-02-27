

	
# chunk_files - 
## column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
id|bigint(20) unsigned|NO||PRI||auto_increment|
uuid|varchar(255)|NO||UNI|uuid||
is_uploaded|tinyint(1)|NO|0|MUL|upload success||
is_fail|tinyint(1)|NO|0|MUL|upload fail||
created_at|timestamp|NO|current_timestamp()||||
updated_at|timestamp|NO|current_timestamp()|||on update current_timestamp()|

## index
name|column|multi|NULL|UNIQ
----|----|----|----|----
PRIMARY|id|1|NO|YES|
chunk_files_uuid_unique|uuid|1|NO|YES|
chunk_files_is_uploaded_index|is_uploaded|1|NO|NO|
chunk_files_is_fail_index|is_fail|1|NO|NO|


## Foreign Key
column|references|delete|update
----|----|----|----
