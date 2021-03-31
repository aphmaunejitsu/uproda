

	
## password_resets - 
#### column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
email|varchar(255)|NO||MUL|||
token|varchar(255)|NO|||||
created_at|timestamp|YES|NULL||||

#### index
name|column|multi|NULL|UNIQ
----|----|----|----|----
password_resets_email_index|email|1|NO|NO|


