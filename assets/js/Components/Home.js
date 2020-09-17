import React, {Component} from 'react';
import {BrowserRouter, Route, Switch} from 'react-router-dom';
import LoginScreen from '../Authorize/LoginScreen';
import QuizHome from '../Components/QuizHome';
import Navigation from "./Navigation";
import DashboardScreen from "../Authorize/DashboardScreen";
import QuestionDashboard from './QuestionDashboard';

class Home extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoggedIn  : false
        }
    }
    render() {
        return (
            <BrowserRouter>
                <div>
                    <Navigation isAuth={this.state.isLoggedIn} />
                    <Switch>
                        <Route path="/" component={QuizHome} exact/>
                        {this.state.isLoggedIn ? (
                            <Route path="/dashboard" component={DashboardScreen}/>
                        ) : (
                            <Route path="/login" component={LoginScreen}/>
                        )}
                        <Route path="/question" component={QuestionDashboard}/>
                    </Switch>
                </div>
            </BrowserRouter>
        )
    }
}

export default Home;