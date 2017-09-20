import superagent from 'superagent';
import jsonp from 'superagent-jsonp';

const URL = 'http://localhost/public/json/test.json';





export default  class Jax {

	constructor() {
		
		
	}

	test(){
		return 8;
	}

	fetch(callback){
		
		superagent
		  .get('http://localhost:5555/public/json/test.json')
		  .responseType('json')
		  .end(function(err, res){
		  	console.log(err);
		     if (res.ok) {
		     	

		     	callback(res.body);
		       console.log(res.body);
		       console.log('success');
		     } else {
		       console.error('error');
		     }
		    console.log('complete');
		  });
	}
};