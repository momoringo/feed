import riot from  "riot";
import { createStore,bindActionCreators,combineReducers,applyMiddleware } from 'redux';
import superagent from 'superagent';
import route from 'riot-route';
import Util from '../../class/utilClass/Util';
import Store from '../../store/store';
import './modal';
import './meta';
import './row';
riot.tag2('app', '<modal hide="{showDetail}"></modal><div show="{showDetail}" class="article num-{index}" each="{d, index in　data}"><p>{d.title.rendered}</p><p>{d.originalExcerpt}</p><p if="{d.originalThumbnail}"><img riot-src="{d.originalThumbnail}"></p><p each="{d.post_meta}"><span>{meta_key}:</span><span>{meta_value}</span></p><p onclick="{like}" data-post-id="{d.id}" data-index="{index}">いいね！<span>{d.likeCount}</span></p><button onclick="{moreContents}" data-id="{d.id}">続きを見る</button></div><p onclick="{more}" if="{totalFlag}">もっと見る</p><p if="{isLoad}">ローディング...</p>', '', '', function(opts) {


    const observer = opts.observer;
    const _this = this;

    _this.data = [];
    _this.isLoad = true;
    _this.total = 0;
    _this.totalFlag = true;
   _this.showDetail = true;

    const count = _this.firstShow = parseInt(opts.numCount);
    const postType = opts.postType;
    const RESTURL = `${WP_API_Settings.root}wp/v2/${postType}s`;
    const LIKEURL = WP_API_Settings.likeCunt;

    let url = `${RESTURL}?per_page=${_this.firstShow}`;

    const offsetPage = (page) => {
      let more = page - count;
      return more;
    }

    const metaLoop = (page) => {
      let more = page - count;
      return more;
    }

    this.Ajax = function(url) {
      superagent
      .get(url, function(err, res){
        if (err) throw err;
        _this.total = res.headers['x-wp-total'];
        _this.data = [].concat(_this.data,res.body);
        _this.isLoad = false;
        _this.update();
      });
    }.bind(this)

    this.AjaxPost = function(param,event) {
     superagent
       .post(param.url)
       .type('form')
       .send(param.data)
       .set('Accept', 'application/json')
       .end(function(err, res){
         if (err || !res.ok) {
           console.log('Oh no! error');
         } else {
          _this.data[event.item.index]['likeCount'] = res.body.count;
          _this.data = [].concat(_this.data);
          _this.update();
         }
       });
    }.bind(this)

    this.more = function(){
      url = `${RESTURL}?per_page=${count}&offset=${_this.firstShow}`;
      _this.firstShow += count;

      if(_this.firstShow >= _this.total) {
              _this.totalFlag = false;
      }
      _this.Ajax(url);
    }.bind(this)

    this.like = function(event){
      const param = {
        url: LIKEURL,
        data: {
          action: 'like',
          post_id: event.item.d.id
        }
      };

      _this.AjaxPost(param,event);
    }.bind(this)

    this.moreContents = function(e) {
      let detail = {};
      _this.data.forEach((i,s) => {
        if( i.id === e.item.id ) {
          detail.detailTitle = i.title.rendered;
          detail.detailContent = i.content.rendered;
        }
      });
      _this.showDetail = false;
      _this.update();
      observer.trigger('hoge',detail);
      observer.trigger('html',detail);
    }.bind(this)
    _this.Ajax(url);

    observer.on('showlist', function() {
      _this.showDetail = true;
      _this.update();
    });

});