import riot from 'riot';
import {createStore} from 'redux';
import './bild/app';
import './bild/nav';
let observer = riot.observable();

riot.mixin('home', {
    myfunc() {
        console.log(555);
        alert(999);
    }

});

riot.mount('app',{observer:observer});
