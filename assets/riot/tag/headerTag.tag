import riot from  "riot";
import './nav';
import './content';
<headerTag>
  <navi>
  </navi>

	<p>{lead}</p>



	<div onclick='{ts}'>押して</div>

<script>







this.lead = 1;


const mon = "おれだ！";

cli(e){
	this.lead = `rghtethnghnjtyno1は${mon}`;
	obser("start");
}

ts() {
	this.lead++;
	//this.update();
}

window.addEventListener("resize",function(){

	this.ts();

	
}.bind(this));


</script>

	



</headerTag>