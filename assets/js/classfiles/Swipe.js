

import $ from 'jQuery';


const REGTRANSLATE = /.*\((\-?\d+)(?:px)?.+?\)/g;

const win = $('.slideWrap')[0].clientWidth;




(function() {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] 
                                   || window[vendors[x]+'CancelRequestAnimationFrame'];
    }
 
    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function() { callback(currTime + timeToCall); }, 
              timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };
 
    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        };
}());



export default class Swipe {

	constructor() {
		this.EVENT = {};
		this.REGOBJ = {};
		this.TOUCHSTART = {};
		this.TOUCHMOVE = {};
		this.TOUCHEND = {x:0};
		this.addSize();
		this.createEventType();
		this.addEvent();
		
		
		
		let tesrad = 2/4;

		var opv = this.getSelectorAll('.a');


		var tu = document.querySelector('.slideWrap');

		console.log(tu);


		opv.forEach(function(i,v){

			i.addEventListener('click', function(){

				
			})


			

		});

		


		let atan = Math.atan(tesrad);

		var mml = this.siblings(tu);

		console.log("\u3042");
		
	}


	getRad(){


	}

	getTan(){
			if(moveRate > Math.tan(15 * Math.PI/180)) {
				e.preventDefault();
			}
	}

	getPrefix(){

	let PRIFIX =  {1:'WebkitTransform', 2:'MozTransform', 3:'OTransform', 4:'msTransform'};

	let venderPrefix = (/webkit/i).test(navigator.appVersion) ? 'webkit' :
                        (/firefox/i).test(navigator.userAgent) ? 'moz' :
                        (/trident/i).test(navigator.userAgent) ? 'ms' :
                        'opera' in window ? 'O' : '';

         return venderPrefix;
	}

	isUa(){

		const u = navigator.userAgent.toLowerCase();

		return {

	    Tablet:(u.indexOf("windows") != -1 && u.indexOf("touch") != -1 && u.indexOf("tablet pc") == -1) 

	      || u.indexOf("ipad") != -1

	      || (u.indexOf("android") != -1 && u.indexOf("mobile") == -1)

	      || (u.indexOf("firefox") != -1 && u.indexOf("tablet") != -1)

	      || u.indexOf("kindle") != -1

	      || u.indexOf("silk") != -1

	      || u.indexOf("playbook") != -1,

	    Mobile:(u.indexOf("windows") != -1 && u.indexOf("phone") != -1)

	      || u.indexOf("iphone") != -1

	      || u.indexOf("ipod") != -1

	      || (u.indexOf("android") != -1 && u.indexOf("mobile") != -1)

	      || (u.indexOf("firefox") != -1 && u.indexOf("mobile") != -1)

	      || u.indexOf("blackberry") != -1
		 }
	}


	isSwipe(){
		
		return 'ontouchstart' in window;

	}

	createEventType(){

			const Flag = this.isSwipe();


		if (Flag) {

		    this.EVENT.TOUCH_START = 'touchstart';

		    this.EVENT.TOUCH_MOVE = 'touchmove';

		    this.EVENT.TOUCH_END = 'touchend';

			} else {

		    this.EVENT.TOUCH_START = 'mousedown';

		    this.EVENT.TOUCH_MOVE = 'mousemove';

		    this.EVENT.TOUCH_END = 'mouseup';

		}

	}

	addEvent(){
		this.touchEvent();
		this.touchMove();
		this.touchEnd();
	}

	touchEvent(){

		  const event = this.EVENT.TOUCH_START;

		  this.wraper.addEventListener(event,function(e){

		  		var touchObj = e.changedTouches[0];

		  		this.TOUCHSTART.x = touchObj.pageX;
		  		this.TOUCHSTART.y = touchObj.pageY;

		  		


/*

		  		if(this.TOUCHEND) {
console.log(parseInt(this.TOUCHEND) + this.TOUCHSTART.x);
		  			
		  			this.TOUCHSTART.x = parseInt(this.TOUCHEND) + parseInt(this.TOUCHSTART.x);
		  		}

		  	*/	



		  }.bind(this));




	}

	touchMove(){

		  const event = this.EVENT.TOUCH_MOVE;


		  this.wraper.addEventListener(event,function(e){
		  	e.preventDefault();


		  	var touchObj = e.changedTouches[0];

		  		this.moveX = (touchObj.pageX - this.TOUCHSTART.x) + this.TOUCHEND.x;
		  		

				this.getTranslate(this.moveX);

		  }.bind(this));

	}


	touchEnd(){

		  const event = this.EVENT.TOUCH_END;




		  this.wraper.addEventListener("webkitTransitionEnd",function(e){

				$(this.wraper).css('-webkit-transition', 'transform 0s ease-in-out');

				//this.wraper.removeEventListener("webkitTransitionEnd");

		  }.bind(this));



		  this.wraper.addEventListener(event,function(e){
			$(this.wraper).css('-webkit-transition', 'transform .9s ease-in-out');

			var touchObj = e.changedTouches[0];


		  this.TOUCHEND.x = this.getLate();

		  let ent = touchObj.pageX;


		  if( ent < this.TOUCHSTART.x ) {
		  	this.getTranslate(-win);

		  } else {
		  	console.log(1);
			this.getTranslate(win);

		  }



		  }.bind(this));

	}

	getPrifix(){

		const prifix = 1;


		return prifix;

	}

	getTranslate(translate){
//console.log(translate);

  		this.wraper.style['WebkitTransform'] = `translate3d(${translate}px,0,0)`;


  		//var YT = /.*\((\-?\d+)(?:px)?,\s?\d+(?:px)?,\s?\d+(?:px)?\)/g;
  		

  		//let ii = YT.exec(this.wraper.style['WebkitTransform']);

  		
  		//console.log(ii);

  		//return ii.splice(1,2)[0];
	}

	getLate(){
			

		let num = this.wraper.getBoundingClientRect().left;


		

		 return  num;
	}

	addSize(){

		this.getElement("#slide");	

		let width = this.childNode[0].clientWidth;



		let allWidth = width * this.childNode.length;


		this.wraper.style.width = `${allWidth}px`;

		


	}

	getSelector(el){

		const CLASSREGG = /^\./;
		const REMOVEHASH = /^#/;

		const elFlag = CLASSREGG.test(el);

		var EL = null;


		EL = !elFlag || elFlag === null ? el.replace(REMOVEHASH,'') : el;

		
		const getElement = !elFlag ?  document.getElementById(EL) : document.querySelector(EL);

		return getElement;

	}


	getSelectorAll(selector, context) {
	  context = context || document;
	  var elements = context.querySelectorAll(selector);
	  return Array.prototype.slice.call(elements);

	}

	getTagSelector(selector){

	  return document.getElementsByTagName(selector);

	}

	getElement(el){
		this.wraper = this.getSelector(el);
		
		this.childNode = this.wraper.children;
	}

	getClassSelector(selector){

	  return document.getElementsByClassName(selector);

	}

	

	contentLoad(doc,func){
		  doc.addEventListener("DOMContentLoaded",func);
	}

	getMousePotision(e){
		  	this.TOUCHSTART.x = e;
		  	this.TOUCHSTART.y = e;
	}


	siblings(taraget){
		var el = Array.prototype.filter.call(taraget.parentNode.children, function(child) {
			  return child !== taraget;
			});

		return el;

	}


}

