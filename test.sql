select B.post_name 
		from wp_posts as B  

		join wp_postmeta as A 

		where B.ID = A.post_id;


insert into wp_sample_table_zuke (name,text,url) values ('a','b','c');
