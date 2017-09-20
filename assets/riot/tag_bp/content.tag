  
import riot from  "riot";
import superagent from 'superagent';

  <content>
    <div each={ blog , key in content } class="{ 'is-more' : key >= 0 , 'test' : true }">
      <h2>{blog.title}</h2>
      <p>{blog.contentLead}</p>


      <row html='h'></row>

      <p if={blog.title === 'タイトル3'}>


        <a href={blog.title}>リンク</a>
      </p>
    </div>
    <button if={ content.length < 20 } onclick={contentMore}>もっとみる</button>

    <style> 
      .is-more {
        opacity: 0;
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

    const url = './json/test.json';
    this.content = [];

    this.on('mount',() => {
        this.contentMore();   
    })

    contentMore() {
      let self = this;
      superagent
        .get(url)
        .end(function(err, res){
          self.content = [].concat(self.content,res.body.blog);
          self.update();
          self.waitContent();
        });
    }

    waitContent() {
      let elements = document.getElementsByClassName('is-more');
      let elementsArr = Array.prototype.slice.call(elements);

      setTimeout(function(){
        elementsArr.forEach(function(i,s){
          i.classList.add('is-show');
        });
      },100);
    }

    </script>
  </content>