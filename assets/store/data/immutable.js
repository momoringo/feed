import { createStore,bindActionCreators,combineReducers,applyMiddleware } from 'redux';
import { List, fromJS, Record } from 'immutable';


export default class Store 
{
	constructor(props) {
		this.init(createStore);
	}

	init(store) {

	}
}