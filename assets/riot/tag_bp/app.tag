  
import riot from  "riot";
import superagent from 'superagent';
import route from 'riot-route';
import ogg from  "../../obs/observa";
import Util from '../../class/utilClass/Util';
import './att';
import './headerTag';
import './footerTag';
import './content';


  <app>

    <div data-is="att">
      yeidyeidyeidyeidyeidyeid
    </div>
    <div data-is="att">
      rrrrrrr
    </div>
    <div data-is="headertag" ja="8" en="Bushitsu Inc." />

    <h1>{time}</h1>
    <div data-is="content" ja="{oj}" en="Bushitsu Inc." />
    <ul class="r">
      <li each='{list}' class='{done:done}'>{ title }</li>
    </ul>
    

    


    <button onclick='{hoge_event}'>{oj.testTag}</button>
    <div data-is="footertag" ja="株式会社部室" en="Bushitsu Inc." />

    <style>
      .r {
        display: flex;
      }
      li.done {
        color: green;
        
        text-decoration: line-through;
      }
      #nm {
        width: 500px;
        height: 500px;
        background: green;
      }
    </style>



    <script>




     

  const U = new Util();



  U.test();


 





    const deepClone = obj => {
        let r = {}
        for(var name in obj){
  
            if(isObject(obj[name])){
                r[name] = deepClone(obj[name])
            }else{
                r[name] = obj[name]
            }
        }
        return r
    }


    function isObject (item) {
      return typeof item === 'object' && item !== null && !isArray(item);
    }

    function isArray (item) {
      return Object.prototype.toString.call(item) === '[object Array]';
    }


this.oj = deepClone(opts);

    hhh(e) {
      e.preventDefault();
    
      this.refs.my_nested_tag.unmount();



    }
    hoge_event(e) {

      this.oj.testTag = "変更"
     this.update();

    }
 
    this.list = [
      {
        title: 'Hello, world!っすaaaaaaaaaaaaaaaaa',
        done: 1,
      },
      {
        title: '牛乳を買う',
        done: true,
      },
      {
        title: '豚肉を買う',
        done: false,
      },

      {
        title: 'Hello, world!',
        done: false,
      },
      {
        title: '牛乳を買う',
        done: true,
      },
      {
        title: '豚肉を買う',
        done: false,
      },

      {
        title: 'Hello, world!',
        done: false,
      },
      {
        title: '牛乳を買う',
        done: true,
      },
      {
        title: '豚肉を買う',
        done: false,
      },


      {
        title: 'Hello, world!',
        done: false,
      },
      {
        title: '牛乳を買う',
        done: true,
      },
      {
        title: '豚肉を買う',
        done: false,
      },


      {
        title: 'Hello, world!',
        done: false,
      },
      {
        title: '牛乳を買う',
        done: true,
      },
      {
        title: '豚肉を買う',
        done: false,
      },


      {
        title: 'Hello, world!',
        done: false,
      },
      {
        title: '牛乳を買う',
        done: true,
      },
      {
        title: '豚肉を買う',
        done: false,
      }

];





   var child = this.tags;

    this.on('mount',() => {
        console.log(Util);
        console.log(this.oj);
    });


riot.mixin('cd', child);



var THIS = this;
route('/fruit/*', function(name) {


//riot.mount('route', collection || 'home', {id: id});
THIS.unmount(true);
riot.mount('div#root', 'att');

}.bind(riot))



 this.submit = (e) => {

     e.preventDefault();
    let canSubmit = true;

  
    if(!canSubmit){
      return false;
    }


  };




    route('/ab/*', function(name) {
    THIS.unmount(true);
    riot.mount('div#root', 'headertag');

    }.bind(riot))


    route.start();









    </script>
  </app>