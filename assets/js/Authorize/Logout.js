import React, {Component} from 'react';
import { Redirect } from 'react-router-dom';
import axios from 'axios';
import Auth from "./Auth";

const apiBaseUrl = "http://quizbank.com/api/";

const Logout = () => {
    axios.post(apiBaseUrl+'userLogout')
        .then((response) => { console.log(response);
            if (response.status === 200) {
                Auth.signout();
                window.location = '/';
            }
        });
}

export default Logout;