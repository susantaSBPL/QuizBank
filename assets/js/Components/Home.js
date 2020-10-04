import React, {Component} from 'react';
import {BrowserRouter, Route, Switch, Redirect} from 'react-router-dom';
import LoginScreen from '../Authorize/LoginScreen';
import QuizHome from '../Components/QuizHome';
import Navigation from "./Navigation";
import DashboardScreen from "../Authorize/DashboardScreen";
import QuestionDashboard from './QuestionDashboard';
import Logout from "../Authorize/Logout";
import ActivateUser from "../Authorize/ActivateUser";
import Auth from '../Authorize/Auth';

const PrivateRoute = ({ component: Component, ...rest }) => (
    <Route
        {...rest}
        render={props =>
            Auth.getAuth() ? (
                <Component {...props} />
            ) : (
                <Redirect
                    to={{
                        pathname: "/"
                    }}
                />
            )
        }
    />
);

class Home extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoggedIn: Auth.getAuth(),
            role: Auth.getUserRole(),
        }
    }

    render() {
        return (
            <BrowserRouter>
                <div>
                    <Navigation isAuth={this.state.isLoggedIn} role={this.state.role} />
                    <Switch>
                        {this.state.isLoggedIn ? (
                                <Route>
                                    {this.state.role === 'ADMIN' ? (
                                        <PrivateRoute path="/question" component={QuestionDashboard}/>
                                    ) : (this.state.role === 'MASTER' ? (
                                        <PrivateRoute path="/dashboard" component={DashboardScreen}/>
                                    ) : null)
                                    }
                                    <Route path="/logout" component={Logout}/>
                                </Route>
                        ) : (
                            <Route>
                                <Route path="/" component={QuizHome} exact/>
                                <Route path="/login" component={LoginScreen}/>
                                <Route path="/activateUser/:verificationKey" component={ActivateUser}/>
                            </Route>
                        )}
                    </Switch>
                </div>
            </BrowserRouter>
        )
    }
}

export default Home;