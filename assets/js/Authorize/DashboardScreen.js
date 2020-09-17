import React, { Component } from 'react';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import RaisedButton from 'material-ui/RaisedButton';
import axios from "axios";
import AppBar from "material-ui/AppBar";

const apiBaseUrl = "http://quizbank.com/api/";

class DashboardScreen extends Component {
    constructor(props){
        super(props);

        axios.get(apiBaseUrl+'profile')
            .then(function (response) {
                console.log(response)
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    render() {
        return (
            <div className="wrapper">
                <MuiThemeProvider>
                    <div>
                        <AppBar title="Dashboard" showMenuIconButton={false} />
                    </div>
                </MuiThemeProvider>
            </div>
        )
    }
}
export default DashboardScreen;