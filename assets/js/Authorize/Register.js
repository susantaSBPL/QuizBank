import React, { Component } from 'react';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import axios from 'axios';
import Login from './Login';

const apiBaseUrl = "http://quizbank.com/api";

class Register extends Component {
    constructor(props){
        super(props);
        this.state = {
            first_name:'',
            last_name:'',
            email:'',
            password:''
        }
    }

    handleClick() {
        //To be done:check for empty values before hitting submit
        const self = this;
        const payload = {
            "first_name": this.state.first_name,
            "last_name":this.state.last_name,
            "email":this.state.email,
            "password":this.state.password
        }
        axios.post(apiBaseUrl+'/register', payload)
            .then(function (response) {
                console.log(response);
                if(response.status === 200) {
                    let userScreen = [];
                    userScreen.push(<Login parentContext={this}/>);
                    const userMessage = "Not Registered yet.Go to registration";
                    self.props.parentContext.setState (
                        {
                            userScreen  : userScreen,
                            userMessage : userMessage,
                            buttonLabel : "Register",
                            showLogin   : true
                        }
                    );
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }
    render() {
        return (
            <div>
                <MuiThemeProvider>
                    <div>
                        <AppBar title="Register" showMenuIconButton={false} />
                        <TextField
                            hintText="Enter your First Name"
                            floatingLabelText="First Name"
                            onChange = {(event,newValue) => this.setState({first_name:newValue})}
                        />
                        <br/>
                        <TextField
                            hintText="Enter your Last Name"
                            floatingLabelText="Last Name"
                            onChange = {(event,newValue) => this.setState({last_name:newValue})}
                        />
                        <br/>
                        <TextField
                            hintText="Enter your Email"
                            type="email"
                            floatingLabelText="Email"
                            onChange = {(event,newValue) => this.setState({email:newValue})}
                        />
                        <br/>
                        <TextField
                            type = "password"
                            hintText="Enter your Password"
                            floatingLabelText="Password"
                            onChange = {(event,newValue) => this.setState({password:newValue})}
                        />
                        <br/>
                        <div className="button-div">
                            <RaisedButton className="button" label="Submit" primary={true} style={style} onClick={(event) => this.handleClick(event)}/>
                        </div>
                    </div>
                </MuiThemeProvider>
            </div>
        );
    }
}
const style = {
    margin: 15,
};
export default Register;