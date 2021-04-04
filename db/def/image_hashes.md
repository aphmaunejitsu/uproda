

	
# image_hashes - 
## column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
id|bigint(20)|NO||PRI||auto_increment|
hash|varchar(256)|NO|||||
comment|text|YES|NULL||||
ng|tinyint(4)|YES|0||||
created_at|timestamp|NO|current_timestamp()|MUL|||
updated_at|timestamp|NO|current_timestamp()|MUL||on update current_timestamp()|
deleted_at|timestamp|YES|NULL|MUL|||

## index
name|column|multi|NULL|UNIQ
----|----|----|----|----
PRIMARY|id|1|NO|YES|
image_hashes_created_at_index|created_at|1|NO|NO|
image_hashes_updated_at_index|updated_at|1|NO|NO|
image_hashes_deleted_at_index|deleted_at|1|YES|NO|


## Foreign Key
column|references|delete|update
----|----|----|----
