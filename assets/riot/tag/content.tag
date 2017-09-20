  
import riot from  "riot";
import superagent from 'superagent';

  <content>
    <div each={ blog , key in content } class="{ 'is-more' : key >= 10 , 'test' : true }">
      <h2>{key}</h2>
      <p>いい</p>


      <row html='h' if={ key%3 == 0 }>ffff</row>

      <row html='h' if={ key == 8 }>bbbbbbb</row>

    </div>
    <button if={ content.length > 20 } onclick={contentMore}>もっとみる</button>

    <style> 
      .is-more {
        opacity: 1;
        -webkit-transition: all 2s;
        transition: all 2s;
      }
      .is-show {
        -webkit-transition: all 2s;
        transition: all 2s;
        opacity: 1;        
      }      
    </style>

    <script>

    const url = "http://api.twitcasting.tv/api/hotlist?type=json";
    this.content = [];

    this.on('mount',() => {
        this.contentMore();   
    })

    contentMore() {
      let self = this;
      superagent
        .get(url)
        .end(function(err, res){
          

          let y = JSON.parse(res.text);

          self.content = [].concat(self.content,y);
          self.update();
          //self.waitContent();
        });
    }

    waitContent() {
      let elements = document.getElementsByClassName('is-more');
      let elementsArr = Array.prototype.slice.call(elements);

      setTimeout(function(){
        elementsArr.forEach(function(i,s){
          //i.classList.add('is-show');
        });
      },100);
    }

    </script>
  </content>