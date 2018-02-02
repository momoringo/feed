import riot from  "riot";
riot.tag2('raw', '', '', '', function(opts) {

  	this.mixin('observer');

  	console.log(this);
	const parentObserble = (obj) => {
		if(!obj.parent.opts.hasOwnProperty('observer')) {
			return parentObserble(obj.parent);
		} else {
			return obj.parent.opts.observer;
		}
	}

	const observer = parentObserble(this);
  	const _this = this;
  	_this.data = opts.html;

    observer.on('html', function(article) {
    	_this.root.innerHTML = opts.html;
    });

    _this.root.innerHTML = opts.html;
});
