import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from 'react-router-dom';
import '../css/style.less';
import Home from './Components/Home';
import 'bootstrap/dist/css/bootstrap.min.css';

ReactDOM.render(<Router><Home /></Router>, document.getElementById('root'));