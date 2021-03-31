

	
## deny_words - 
#### column
name|type|NULL|default|key|comment|Extra
----|----|----|----|----|---|---|
id|bigint(20) unsigned|NO||PRI||auto_increment|
word|varchar(200)|NO||MUL|||
created_at|timestamp|NO|current_timestamp()|MUL|||
updated_at|timestamp|NO|current_timestamp()|MUL||on update current_timestamp()|
deleted_at|timestamp|YES|NULL|MUL|||

#### index
name|column|multi|NULL|UNIQ
----|----|----|----|----
PRIMARY|id|1|NO|YES|
deny_words_created_at_index|created_at|1|NO|NO|
deny_words_word_index|word|1|NO|NO|
deny_words_updated_at_index|updated_at|1|NO|NO|
deny_words_deleted_at_index|deleted_at|1|YES|NO|


