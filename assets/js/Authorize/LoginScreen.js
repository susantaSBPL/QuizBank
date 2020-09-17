import React, { Component } from 'react';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import RaisedButton from 'material-ui/RaisedButton';
import Login from './Login';
import Register from './Register';
import Dashboard from './DashboardScreen';
import DashboardScreen from "./DashboardScreen";

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
        loginScreen.push(<Login parentContext={this} appContext={this.props.parentContext}/>);
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
            userScreen.push(<Register parentContext={this}/>);
            userMessage = "Already registered.Go to Login";

            this.setState({
                userScreen  : userScreen,
                userMessage : userMessage,
                buttonLabel : "Login",
                showLogin   : false
            })
        } else if(!this.state.showLogin && this.state.isLoggedIn) {
            let userScreen = [];
            userScreen.push(<DashboardScreen parentContext={this}/>);
            this.setState({
                userScreen  : userScreen,
                userMessage : '',
                buttonLabel : "Dashboard",
                showLogin   : false,
                isLoggedIn  : true
            })
        } else {
            let userScreen = [];
            userScreen.push(<Login parentContext={this}/>);
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
        return (
            <div className="wrapper">
                <div className="authorize-block">
                    {this.state.userScreen}
                    {!this.state.isLoggedIn ? (
                        <div>
                            {this.state.userMessage}
                            <MuiThemeProvider>
                                <div className="button-div">
                                    <RaisedButton className="button" key={this.state.userScreen} label={this.state.buttonLabel} primary={true} style={style} onClick={(event) => this.handleClick(event)}/>
                                </div>
                            </MuiThemeProvider>
                        </div>
                    ) : (
                        <div>
                            {this.state.userMessage}
                        </div>
                    )}
                </div>
            </div>
        );
    }
}
const style = {
    margin: 15,
};
export default LoginScreen;