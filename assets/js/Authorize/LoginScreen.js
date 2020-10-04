import React, { Component } from 'react';
import {Redirect} from 'react-router-dom';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import RaisedButton from 'material-ui/RaisedButton';
import Login from './Login';
import Register from './Register';
import Auth from './Auth';
import QuestionDashboard from '../Components/QuestionDashboard';

class LoginScreen extends Component {
    constructor(props){
        super(props);
        this.state = {
            username    : '',
            password    : '',
            userScreen  : [],
            userMessage : '',
            buttonLabel : 'Register',
            showLogin   : true,
            isLoggedIn  : false
        }
    }
    componentWillMount() {
        let loginScreen = [];
        loginScreen.push(<Login key="login" parentContext={this} appContext={this.props.parentContext}/>);
        const loginMessage = "Not registered yet, Register Now";
        this.setState({
            userScreen  : loginScreen,
            userMessage : loginMessage
        })
    }

    handleClick(){
        // console.log("event",event);
        let userMessage;
        if(this.state.showLogin) {
            let userScreen = [];
            userScreen.push(<Register key="register" parentContext={this}/>);
            userMessage = "Already registered.Go to Login";

            this.setState({
                userScreen  : userScreen,
                userMessage : userMessage,
                buttonLabel : "Login",
                showLogin   : false
            })
        } else {
            let userScreen = [];
            userScreen.push(<Login key="login" parentContext={this}/>);
            userMessage = "Not Registered yet.Go to registration";
            this.setState({
                userScreen  : userScreen,
                userMessage : userMessage,
                buttonLabel : "Register",
                showLogin   : true
            })
        }
    }

    render() {
        if (Auth.getAuth() && Auth.getUserRole() === 'ADMIN') {
            return (
                <QuestionDashboard />
            )
        } else {
            return (
                <div className="wrapper">
                    <div className="authorize-block">
                        {this.state.userScreen}
                        {!this.state.isLoggedIn ? (
                            <div key="action" className={"text-center"}>
                                <h6 key="userMessage">{this.state.userMessage}</h6>
                                <MuiThemeProvider>
                                    <div className="button-div">
                                        <RaisedButton className="button" key={this.state.userScreen} label={this.state.buttonLabel} primary={true} style={style} onClick={(event) => this.handleClick(event)} />
                                    </div>
                                </MuiThemeProvider>
                            </div>
                        ) : (
                            <div key="action">
                                <h6>{this.state.userMessage}</h6>
                            </div>
                        )}
                    </div>
                </div>
            );
        }
    }
}
const style = {
    margin: 15,
};
export default LoginScreen;