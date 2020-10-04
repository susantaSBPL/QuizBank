import React, { Component } from 'react';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import Checkbox from '@material-ui/core/Checkbox';
import FormControlLabel from '@material-ui/core/FormControlLabel';
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
            password:'',
            isMaster: true
        }

        this.handleIsMasterCheck = this.handleIsMasterCheck.bind(this);
    }

    handleIsMasterCheck(e) {
        this.setState({
            isMaster: !!e.target.checked
        });
    }

    handleClick() {
        //To be done:check for empty values before hitting submit
        const self = this;
        const payload = {
            "first_name": this.state.first_name,
            "last_name":this.state.last_name,
            "email":this.state.email,
            "password":this.state.password,
            "isMaster": this.state.isMaster
        };
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
                            className="text-field"
                            onChange = {(event,newValue) => this.setState({first_name:newValue})}
                        />
                        <br/>
                        <TextField
                            hintText="Enter your Last Name"
                            floatingLabelText="Last Name"
                            className="text-field"
                            onChange = {(event,newValue) => this.setState({last_name:newValue})}
                        />
                        <br/>
                        <TextField
                            hintText="Enter your Email"
                            type="email"
                            floatingLabelText="Email"
                            className="text-field"
                            onChange = {(event,newValue) => this.setState({email:newValue})}
                        />
                        <br/>
                        <TextField
                            type = "password"
                            hintText="Enter your Password"
                            floatingLabelText="Password"
                            className="text-field"
                            onChange = {(event,newValue) => this.setState({password:newValue})}
                        />
                        <br/>
                        <FormControlLabel
                            control={
                                <Checkbox
                                    checked={this.state.isMaster}
                                    onChange={this.handleIsMasterCheck}
                                    name="isMaster"
                                    color="primary"
                                />
                            }
                            label="Is Master ?"
                        />
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