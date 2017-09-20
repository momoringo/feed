  
import riot from  "riot";

riot.tag2('meta', '<div class="meta" each="{metaData}"></div>', '', '', function(opts) {
    const _this = this;
    const observer = this.parent.opts.observer;

    _this.metaData = opts.meta;

    this.on('mount',function(){

    });

    observer.on('metas', function(a) {
      console.log(a);
    });
});