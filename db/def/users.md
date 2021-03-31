

	
## users - 
#### column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
id|bigint(20) unsigned|NO||PRI||auto_increment|
username|varchar(50)|NO|||||
password|varchar(255)|NO|||||
group|int(11)|NO|1||||
email|varchar(255)|NO||UNI|||
email_verified_at|timestamp|YES|NULL||||
last_login|varchar(25)|NO|||||
login_hash|varchar(255)|NO|||||
profile_fields|text|NO|||||
remember_token|varchar(100)|YES|NULL||||
created_at|timestamp|NO|current_timestamp()|MUL|||
updated_at|timestamp|NO|current_timestamp()|MUL||on update current_timestamp()|
deleted_at|timestamp|YES|NULL|MUL|||

#### index
name|column|multi|NULL|UNIQ
----|----|----|----|----
PRIMARY|id|1|NO|YES|
users_email_unique|email|1|NO|YES|
idx_created_at|created_at|1|NO|NO|
idx_updated_at|updated_at|1|NO|NO|
idx_deleted_at|deleted_at|1|YES|NO|


