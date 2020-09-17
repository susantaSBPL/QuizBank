import React, {Component} from 'react';
import axios from 'axios';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import DashboardScreen from './DashboardScreen';

const apiBaseUrl = "http://quizbank.com/api/";

class Login extends Component {
    constructor(props){
        super(props);
        this.state = {
            username:'',
            password:''
        }

        this.handleClick = this.handleClick.bind(this);
    }

    handleClick() {
        const self = this;
        const payload = {
            "email":this.state.username,
            "password":this.state.password
        }
        axios.post(apiBaseUrl+'login', payload)
            .then(function (response) {
                console.log(response);
                if (response.status === 200) {
                    let userScreen = [];
                    userScreen.push(<DashboardScreen parentContext={this}/>);
                    const userMessage = "Logged in Successfully";
                    self.props.parentContext.setState (
                        {
                            userScreen  : userScreen,
                            userMessage : userMessage,
                            buttonLabel : "Dashboard",
                            showLogin   : false,
                            isLoggedIn  : true
                        }
                    );
                    self.props.appContext.setState({isLoggedIn: true});
                } else if(response.status === 204){
                    console.log("Username password do not match");
                    alert("username password do not match")
                } else {
                    console.log("Username does not exists");
                    alert("Username does not exist");
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
                        <AppBar title="Login" showMenuIconButton={false} />
                        <TextField
                            hintText="Enter your Username"
                            floatingLabelText="Username"
                            onChange = {(event,newValue) => this.setState({username:newValue})}
                        />
                        <br/>
                        <TextField
                            type="password"
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
export default Login;