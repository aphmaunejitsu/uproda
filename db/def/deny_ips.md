

	
# deny_ips - 
## column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
id|bigint(20)|NO||PRI||auto_increment|
ip|varchar(40)|YES|NULL|MUL|||
created_at|timestamp|NO|current_timestamp()|MUL|||
updated_at|timestamp|NO|current_timestamp()|MUL||on update current_timestamp()|
deleted_at|timestamp|YES|NULL|MUL|||

## index
name|column|multi|NULL|UNIQ
----|----|----|----|----
PRIMARY|id|1|NO|YES|
deny_ips_created_at_index|created_at|1|NO|NO|
deny_ips_ip_index|ip|1|YES|NO|
deny_ips_updated_at_index|updated_at|1|NO|NO|
deny_ips_deleted_at_index|deleted_at|1|YES|NO|


## Foreign Key
column|references|delete|update
----|----|----|----
