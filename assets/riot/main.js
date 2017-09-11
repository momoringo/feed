import riot from 'riot';
import route from 'riot-route';
import {createStore} from 'redux';
import './bild/app';
let observer = riot.observable();
riot.mount('app',{observer:observer});
