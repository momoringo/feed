

export default class Storage
{
	constructor() {
		if( !window.localStorage ) return;
		this.storage = window.localStorage;
	}
	
    isActive(id) {
      let f = this.getStorage();
      return f.indexOf(id) !== -1 ? 'active': '';
    }
    getStorage(num){
      if( !window.localStorage ) return;
      let key = localStorage.getItem("f");
      return key ? JSON.parse(key) : [];
    }
    setStorage(num){
      if( !window.localStorage ) return;
      let k = JSON.parse(_this.favorite);
      k.push(num);
      localStorage.setItem("f", JSON.stringify(k));
    }

    isObject(num){
    }
}