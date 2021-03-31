

	
## images - 
#### column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
id|bigint(20)|NO||PRI||auto_increment|
image_hash_id|bigint(20)|YES|NULL|MUL|image_hash.id||
basename|varchar(100)|NO||MUL|||
ext|varchar(10)|YES|NULL||||
t_ext|varchar(10)|YES|'jpg'||||
original|varchar(255)|YES|NULL||||
delkey|varchar(20)|YES|NULL||||
mimetype|varchar(100)|YES|NULL||||
width|int(11)|YES|NULL||||
height|int(11)|YES|NULL||||
size|int(11)|YES|NULL||||
comment|text|YES|NULL||||
ip|varchar(40)|YES|NULL||||
created_at|timestamp|NO|current_timestamp()|MUL|||
updated_at|timestamp|NO|current_timestamp()|MUL||on update current_timestamp()|
deleted_at|timestamp|YES|NULL|MUL|||

#### index
name|column|multi|NULL|UNIQ
----|----|----|----|----
PRIMARY|id|1|NO|YES|
images_created_at_index|created_at|1|NO|NO|
images_image_hash_id_foreign|image_hash_id|1|YES|NO|
images_basename_index|basename|1|NO|NO|
images_updated_at_index|updated_at|1|NO|NO|
images_deleted_at_index|deleted_at|1|YES|NO|


