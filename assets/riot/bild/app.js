import riot from  "riot";
import { createStore,bindActionCreators,combineReducers,applyMiddleware } from 'redux';

import superagent from 'superagent';
import route from 'riot-route';
import Util from '../../class/utilClass/Util';
import Store from '../../store/store';
import { createLogger } from 'redux-logger';
import riotReduxMixin from 'riot-redux-mixin';
import './modal';
import './meta';
import './row';
riot.tag2('app', '<modal hide="{showDetail}"></modal><div show="{showDetail}" class="article num-{index}" each="{d, index in　data}"><p>{d.title.rendered}</p><p>{d.originalExcerpt.content}</p><p if="{d.originalExcerpt.thumb}"><img riot-src="{d.originalExcerpt.thumb}"></p><p each="{d.originalExcerpt.meta}"><span>{meta_key}:</span><span>{meta_value}</span></p><p onclick="{like}" data-post-id="{d.id}" data-index="{index}">{likeStr}<span>{d.originalExcerpt.action}</span></p><button onclick="{moreContents}" data-id="{d.id}">続きを見る</button></div><p onclick="{more}" if="{totalFlag}">もっと見る</p><p if="{isLoad}">ローディング...</p>', '', '', function(opts) {


    const loadFeed = (data) =>  {
      return {
        type: 'test',
        'data': data
      };
    };

    const formReducer = (state = {}, action) => {
      switch (action.type) {
        case 'test':
          return Object.assign({}, state, {
            data: action.data
          });
        default:
          return state;
      }
    }
    const observer = opts.observer;
    const _this = this;

const loggers = createLogger();
const middle = applyMiddleware(loggers)(createStore);
const store = middle(formReducer);

    riot.mixin('redux', riotReduxMixin(store));

    const p = {

    }
    riot.mixin('observer',{p:opts.observer});
    this.mixin('redux');

    this.subscribe((state) => {
      const d = [].concat(_this.data,state.data);
      return { data: d }
    });

    _this.feedData = {
      data: [],
      isLoad: true,
      totalFlag: true,
      showDetail: true,
      likeStr: opts.likeStr
    };

    _this.data = [];
    _this.isLoad = true;
    _this.total = 0;
    _this.totalFlag = true;
    _this.showDetail = true;
    _this.likeStr = opts.likeStr;

    const count = _this.firstShow = parseInt(opts.numCount);
    const postType = opts.postType;

    const RESTURL = `${WP_API_Settings.root}wp/v2/${postType}s`;
    const LIKEURL = WP_API_Settings.likeCunt;

    let url = `${RESTURL}?per_page=${_this.firstShow}`;

    this.Ajax = function(url) {
      superagent
      .get(url, function(err, res){
        if (err) throw err;
        _this.total = res.headers['x-wp-total'];

        console.log(res.body);
        _this.isLoad = false;
        _this.dispatch(loadFeed(res.body));

      });
    }.bind(this)

    this.AjaxPost = function(param,event) {
     superagent
       .post(param.url)
       .type('form')
       .send(param.data)
       .set('Accept', 'application/json')
       .end((err, res) => {
         if (err || !res.ok) {
           console.log('Oh no! error');
         } else {
          _this.data[event.item.index]['originalExcerpt']['action'] = res.body.count;
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
        if( i.id === e.item.d.id ) {
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