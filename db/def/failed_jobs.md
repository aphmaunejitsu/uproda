

	
## failed_jobs - 
#### column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
id|bigint(20) unsigned|NO||PRI||auto_increment|
connection|text|NO|||||
queue|text|NO|||||
payload|longtext|NO|||||
exception|longtext|NO|||||
failed_at|timestamp|NO|current_timestamp()||||

#### index
name|column|multi|NULL|UNIQ
----|----|----|----|----
PRIMARY|id|1|NO|YES|


