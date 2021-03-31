

	
## comments - 
#### column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
id|bigint(20) unsigned|NO||PRI||auto_increment|
image_id|bigint(20)|NO||MUL|||
comment|text|NO|||||
created_at|timestamp|NO|current_timestamp()|MUL|||
updated_at|timestamp|NO|current_timestamp()|MUL||on update current_timestamp()|
deleted_at|timestamp|YES|NULL|MUL|||

#### index
name|column|multi|NULL|UNIQ
----|----|----|----|----
PRIMARY|id|1|NO|YES|
comments_image_id_foreign|image_id|1|NO|NO|
comments_created_at_index|created_at|1|NO|NO|
comments_updated_at_index|updated_at|1|NO|NO|
comments_deleted_at_index|deleted_at|1|YES|NO|


