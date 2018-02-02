import { createStore,bindActionCreators,combineReducers,applyMiddleware } from 'redux';
import { List, fromJS, Record } from 'immutable';


const TodoRecord = Record({
  id: null,
  completed: null,
  text: null
})

export default class Store extends TodoRecord
{
	constructor() {
		super();
		this.t = 5;
	}

}