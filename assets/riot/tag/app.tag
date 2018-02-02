import riot from  "riot";
import { createStore,bindActionCreators,combineReducers,applyMiddleware } from 'redux';
import superagent from 'superagent';
import Store from '../../store/store';
import Storage from '../../js/Storage';

import { createLogger } from 'redux-logger';
import riotReduxMixin from 'riot-redux-mixin';
import 'intersection-observer';
import './modal';
import './meta';
import './row';
<app>

  <modal if={showDetail}/>
  
  <button class="favarite-list">お気に入り記事一覧</button>
  <div class="article num-{index}" each={d, index in　data}>
      <p>{d.title.rendered}</p>
      <p>{d.originalExcerpt.content}</p>
      <p if={d.originalExcerpt.thumb}><img src="{d.originalExcerpt.thumb}" /></p>
      <p each={d.originalExcerpt.meta}>
        <span>{meta_key}:</span>
        <span>{meta_value}</span>
      </p>
      <p onclick={like} data-post-id={d.id} data-index={index} class="{isActive(d.id)}">{likeStr}<span>{d.originalExcerpt.action}</span></p>
      <button onclick={moreContents} data-id={d.id} data-count={d.originalExcerpt.action}>続きを見る</button>
  </div>
  <p onclick={more} if={totalFlag}>もっと見る</p>
  <p if={isLoad}>ローディング...</p>


  <script>
var localStream;
//ベンダープリフィックスの有無を吸収

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
    const store = createStore(formReducer);

    riot.mixin('redux', riotReduxMixin(store));
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

    _this.storage = new Storage();
    _this.data = [];
    _this.isLoad = true;
    _this.total = 0;
    _this.totalFlag = true;
    _this.showDetail = false;
    _this.likeStr = opts.likeStr;
    _this.favorite = localStorage.getItem("f");

    const count = _this.firstShow = parseInt(opts.numCount);
    const postType = opts.postType;

    const RESTURL = `${WP_API_Settings.root}wp/v2/${postType}s`;
    const LIKEURL = WP_API_Settings.likeCunt;
    let url = `${RESTURL}?per_page=${_this.firstShow}`;


    Ajax(url) {
      superagent
      .get(url)
      .set('Content-Type', 'application/json')
      .set('X-Requested-With”,”XMLHttpRequest')
      .end((err, res) => {
        if (err) throw err;
        _this.total = res.headers['x-wp-total'];
        _this.isLoad = false;
        _this.dispatch(loadFeed(res.body));
      });
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



    AjaxPost(param,event) {
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
          _this.setStorage(param.data.post_id);
          _this.data = [].concat(_this.data);
          _this.update();
         }
       });
    }

    more(){
      url = `${RESTURL}?per_page=${count}&offset=${_this.firstShow}`;
      _this.firstShow += count;
      if(_this.firstShow >= _this.total) {
          _this.totalFlag = false;
      }
      _this.Ajax(url);
    }

    like(event){
      const param = {
        url: LIKEURL,
        data: {
          action: 'like',
          post_id: event.item.d.id,
          _ajax_nonce: WP_API_Settings.nonce
        }
      };

      _this.AjaxPost(param,event);
    }

    moreContents(e) {

      let detail = {};
      _this.data.forEach((i,s) => {
        if( i.id === e.item.d.id ) {
          detail.detailTitle = i.title.rendered;
          detail.detailContent = i.content.rendered;
        }
      });
      detail.likeStr = _this.likeStr;
      detail.active = _this.isActive(e.item.d.id);

      detail.count = e.target.dataset.count;
      detail.id = e.item.d.id;
      detail.index = e.item.index;


      _this.showDetail = true;
      _this.update();
      observer.trigger('hoge',detail);
      observer.trigger('html',detail);
    }

    _this.Ajax(url);




    like2(obj){
      const param = {
        url: LIKEURL,
        data: {
          action: 'like',
          post_id: obj.id,
          _ajax_nonce: WP_API_Settings.nonce
        }
      };

      _this.AjaxPost2(param,obj.index);
    }


    AjaxPost2(param,index) {
     superagent
       .post(param.url)
       .type('form')
       .send(param.data)
       .set('Accept', 'application/json')
       .end((err, res) => {
         if (err || !res.ok) {
           console.log('Oh no! error');
         } else {
          _this.data[index]['originalExcerpt']['action'] = res.body.count;
          _this.setStorage(param.data.post_id);
          _this.data = [].concat(_this.data);
          //_this.update();
          riot.update()
         }
       });
    }

    observer.on('ajax', function(e,r) {
        _this.like2(e);
    });


    observer.on('showlist', function() {
      _this.showDetail = false;
      _this.update(); 
    });

  </script>

</app>