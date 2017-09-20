  
import riot from  "riot";

  <navi>
    <nav id="gNavi">
      <ul >
        <li each="{ key in naviGeation }"><a href="">{ key }</a></li>
      </ul>
    </nav>
    <div class="navContents">
      <yield />
    </div>
    <div if={ text }>
      <input type="submit" value="チェック">
    </div>   

    <button onclick={naviChange} class="naviChange">ナビチェンジ</button>
    <script>

    this.text = false;

    this.naviGeation = [
      'test1',
      'test2',
      'test3',
      'test4',
      'test5'
    ];
    this.naviGeation2 = [
      'chage1',
      'chage2',
      'chage3',
      'chage4',
      'chage5'
    ];

    naviChange() {
      this.naviGeation = this.naviGeation2;
      this.update();
    }

    this.on('mount',() => {
      this.text = true;
      this.update();
    });
    
    </script>
  </navi>